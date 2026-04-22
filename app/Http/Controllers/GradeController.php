<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Curriculum;
use App\Models\GradeLevel;
use App\Models\StudentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    // =========================================================================
    // INDEX — form input nilai
    // =========================================================================

    public function index(Request $request)
    {
        $activeYear    = AcademicYear::where('is_active', true)->first();
        $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('created_at')->get();
        $gradeLevels   = GradeLevel::with('educationLevel')->orderBy('education_level_id')->get();

        $yearId        = $request->get('academic_year_id', $activeYear?->id);
        $selectedYear  = $yearId ? AcademicYear::with('terms')->find($yearId) : $activeYear;
        $terms         = $selectedYear?->terms ?? collect();
        $termId        = $request->get('academic_term_id', $selectedYear?->activeTerm()?->id);
        
        $gradeLevelId  = $request->get('grade_level_id');
        $curriculumId  = $request->get('curriculum_id');

        // ── Komponen Nilai — disimpan di session per grade_level ────────────
        // Kunci session: grades.komponen.{gradeLevelId}
        $sessionKey = 'grades.komponen.' . ($gradeLevelId ?: 'default');

        if ($request->has('komponen')) {
            // Admin baru saja memilih komponen → simpan ke session
            $pilihan = array_values(array_filter(
                (array)$request->get('komponen'),
                fn($k) => in_array($k, Grade::JENIS_NILAI)
            ));
            session([$sessionKey => $pilihan]);
            $komponenAktif = $pilihan;
        } else {
            // Baca dari session; jika belum ada, default semua komponen
            $komponenAktif = session($sessionKey, Grade::JENIS_NILAI);
        }
        $komponenAktif = array_values((array)$komponenAktif);

        // Kurikulum list untuk dropdown
        $kurikulumList = $gradeLevelId
            ? Curriculum::with('subject')
                ->where('academic_year_id', $yearId)
                ->where('grade_level_id', $gradeLevelId)
                ->orderBy('subject_id')
                ->get()
            : collect();

        $curriculum = $curriculumId
            ? Curriculum::with(['subject', 'gradeLevel.educationLevel', 'academicYear'])->find($curriculumId)
            : null;

        $students        = collect();
        $existingMap     = [];  // [jenis_nilai][student_id] = nilai
        $existingRowMap  = [];  // [jenis_nilai][student_id] = Grade model (untuk catatan)

        if ($curriculum && $gradeLevelId) {
            $classroomIds = Classroom::where('grade_level_id', $gradeLevelId)->pluck('id');

            $students = StudentHistory::with('student')
                ->whereIn('class_id', $classroomIds)
                ->where('academic_year_id', $yearId)
                ->get()
                ->pluck('student')
                ->filter()
                ->unique('id')
                ->sortBy('nama_lengkap');

            // Ambil seluruh nilai yang sudah ada untuk kurikulum ini DAN KUARTAL ini
            $existingGrades = Grade::where('curriculum_id', $curriculumId)
                ->where('academic_term_id', $termId)
                ->whereIn('student_id', $students->pluck('id'))
                ->whereIn('jenis_nilai', Grade::JENIS_NILAI)
                ->get();

            foreach ($existingGrades as $g) {
                $existingMap[$g->jenis_nilai][$g->student_id]    = $g->nilai;
                $existingRowMap[$g->jenis_nilai][$g->student_id] = $g;
            }
        }

        return view('grades.index', compact(
            'academicYears', 'gradeLevels', 'kurikulumList', 'curriculum',
            'yearId', 'gradeLevelId', 'curriculumId', 'terms', 'termId',
            'komponenAktif', 'students', 'existingMap', 'existingRowMap'
        ));
    }

    // =========================================================================
    // STORE — simpan / update nilai bulk per komponen yang dipilih
    // =========================================================================

    public function store(Request $request)
    {
        $request->validate([
            'curriculum_id'    => 'required|exists:curriculums,id',
            'academic_term_id' => 'required|exists:academic_terms,id',
            'komponen_aktif'   => 'required|array|min:1',
            'komponen_aktif.*' => 'in:UH,UTS,UAS,Tugas,Akhir',
            'nilais'           => 'array',
        ]);

        $curriculumId   = $request->curriculum_id;
        $termId         = $request->academic_term_id;
        $komponenAktif = $request->komponen_aktif;
        // nilais[jenis_nilai][student_id] = nilai
        $nilais        = $request->nilais ?? [];
        $catatan       = $request->catatan ?? [];

        DB::transaction(function () use ($curriculumId, $termId, $komponenAktif, $nilais, $catatan) {
            foreach ($komponenAktif as $jenis) {
                $nilaisJenis = $nilais[$jenis] ?? [];
                foreach ($nilaisJenis as $studentId => $nilai) {
                    if ($nilai === null || $nilai === '') continue;
                    Grade::updateOrCreate(
                        [
                            'student_id'       => $studentId,
                            'curriculum_id'    => $curriculumId,
                            'academic_term_id' => $termId,
                            'jenis_nilai'      => $jenis,
                        ],
                        [
                            'nilai'        => $nilai,
                            'catatan_guru' => $catatan[$studentId] ?? null,
                        ]
                    );
                }
            }
        });

        return redirect()->route('grades.index', [
            'academic_year_id' => $request->academic_year_id,
            'academic_term_id' => $termId,
            'grade_level_id'   => $request->grade_level_id,
            'curriculum_id'    => $curriculumId,
            'komponen'         => $komponenAktif,
        ])->with('success', '✅ Nilai berhasil disimpan.');
    }


    // =========================================================================
    // RECAP — tabel pivot semua mapel per santri
    // =========================================================================

    public function recap(Request $request)
    {
        $activeYear    = AcademicYear::where('is_active', true)->first();
        $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('created_at')->get();
        $gradeLevels   = GradeLevel::with('educationLevel')->orderBy('education_level_id')->get();

        $yearId       = $request->get('academic_year_id', $activeYear?->id);
        $selectedYear = $yearId ? AcademicYear::with('terms')->find($yearId) : $activeYear;
        $terms        = $selectedYear?->terms ?? collect();
        $termId       = $request->get('academic_term_id', $selectedYear?->activeTerm()?->id);

        $gradeLevelId = $request->get('grade_level_id');
        $jenisNilai   = $request->get('jenis_nilai', 'UH');

        $students     = collect();
        $curricula    = collect();
        $gradeMap     = [];  // [student_id][curriculum_id] = nilai

        if ($gradeLevelId && $yearId) {
            $classroomIds = Classroom::where('grade_level_id', $gradeLevelId)->pluck('id');

            $students = StudentHistory::with('student')
                ->whereIn('class_id', $classroomIds)
                ->where('academic_year_id', $yearId)
                ->get()
                ->pluck('student')
                ->filter()
                ->unique('id')
                ->sortBy('nama_lengkap');

            $curricula = Curriculum::with('subject')
                ->where('academic_year_id', $yearId)
                ->where('grade_level_id', $gradeLevelId)
                ->orderBy('subject_id')
                ->get();

            $grades = Grade::where('jenis_nilai', $jenisNilai)
                ->where('academic_term_id', $termId)
                ->whereIn('curriculum_id', $curricula->pluck('id'))
                ->whereIn('student_id', $students->pluck('id'))
                ->get();

            foreach ($grades as $g) {
                $gradeMap[$g->student_id][$g->curriculum_id] = $g->nilai;
            }
        }

        return view('grades.recap', compact(
            'academicYears', 'gradeLevels', 'yearId', 'gradeLevelId', 'terms', 'termId',
            'jenisNilai', 'students', 'curricula', 'gradeMap'
        ));
    }
}
