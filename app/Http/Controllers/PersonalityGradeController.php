<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\GradeLevel;
use App\Models\Classroom;
use App\Models\StudentHistory;
use App\Models\PersonalityGrade;
use Illuminate\Http\Request;

class PersonalityGradeController extends Controller
{
    // Opsi Kategori Kepribadian
    const ASPEK = ['Kelakuan', 'Kerajinan', 'Kebersihan'];
    // Opsi Predikat
    const PREDIKAT = ['Sangat Baik', 'Baik', 'Cukup', 'Kurang'];

    public function index(Request $request)
    {
        $activeYear   = AcademicYear::where('is_active', true)->first();
        $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('created_at')->get();
        $gradeLevels  = GradeLevel::with('educationLevel')->orderBy('education_level_id')->get();

        $yearId        = $request->get('academic_year_id', $activeYear?->id);
        $selectedYear  = $yearId ? AcademicYear::with('terms')->find($yearId) : $activeYear;
        $terms         = $selectedYear?->terms ?? collect();
        $termId        = $request->get('academic_term_id', $selectedYear?->activeTerm()?->id);
        
        $gradeLevelId  = $request->get('grade_level_id');
        $classId       = $request->get('class_id');

        // Dropdown Kelas sesuai tingkatan
        $classrooms = $gradeLevelId && $yearId
            ? Classroom::where('grade_level_id', $gradeLevelId)->orderBy('nama_kelas')->get()
            : collect();

        $students = collect();
        $existingMap = []; // [student_id][aspek] = predikat

        if ($classId && $yearId) {
            $students = StudentHistory::with('student')
                ->where('class_id', $classId)
                ->where('academic_year_id', $yearId)
                ->get()
                ->pluck('student')
                ->filter()
                ->unique('id')
                ->sortBy('nama_lengkap');

            $studentIds = $students->pluck('id')->toArray();

            $existing = PersonalityGrade::where('academic_term_id', $termId)
                ->whereIn('student_id', $studentIds)
                ->get();

            foreach ($existing as $g) {
                $existingMap[$g->student_id][$g->aspek] = $g->predikat;
            }
        }

        return view('personality.index', compact(
            'academicYears', 'gradeLevels', 'classrooms', 'terms', 'termId',
            'yearId', 'gradeLevelId', 'classId',
            'students', 'existingMap'
        ))->with('aspeks', self::ASPEK)->with('predikats', self::PREDIKAT);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'academic_term_id' => 'required|exists:academic_terms,id',
            'grade_level_id'   => 'required|exists:grade_levels,id',
            'class_id'         => 'required|exists:classrooms,id',
            'nilai'            => 'array'
        ]);

        $yearId       = $validated['academic_year_id'];
        $termId       = $validated['academic_term_id'];
        $gradeLevelId = $validated['grade_level_id'];
        $classId      = $validated['class_id'];
        $nilaiData    = $validated['nilai'] ?? [];

        foreach ($nilaiData as $studentId => $aspeks) {
            foreach ($aspeks as $aspekName => $predikatVal) {
                if (!empty($predikatVal)) {
                    PersonalityGrade::updateOrCreate(
                        [
                            'student_id'       => $studentId,
                            'academic_term_id' => $termId,
                            'aspek'            => $aspekName
                        ],
                        [
                            'academic_year_id' => $yearId, // Tetap simpan year_id untuk kompatibilitas/query cepat
                            'grade_level_id'   => $gradeLevelId,
                            'predikat'         => $predikatVal
                        ]
                    );
                } else {
                    // Jika dikosongkan, hapus recordnya
                    PersonalityGrade::where('student_id', $studentId)
                        ->where('academic_term_id', $termId)
                        ->where('aspek', $aspekName)
                        ->delete();
                }
            }
        }

        return redirect()->route('personality.index', [
            'academic_year_id' => $yearId,
            'academic_term_id' => $termId,
            'grade_level_id'   => $gradeLevelId,
            'class_id'         => $classId
        ])->with('success', 'Nilai Kepribadian berhasil disimpan.');
    }
}
