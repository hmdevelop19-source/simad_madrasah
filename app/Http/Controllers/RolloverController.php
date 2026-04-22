<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Curriculum;
use App\Models\EducationLevel;
use App\Models\GradeLevel;
use App\Models\Student;
use App\Models\StudentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


/**
 * RolloverController — Year-End Rollover Wizard (3 langkah)
 *
 * Step 1: Review tahun ajaran aktif + ringkasan data
 * Step 2: Penetapan status kenaikan per santri (Naik / Tinggal / Lulus / Mutasi)
 * Step 3: Konfirmasi + eksekusi — buat tahun ajaran baru + promote santri
 *
 * ⚠️  Step 3 adalah IRREVERSIBLE. Selalu tampilkan peringatan sebelum eksekusi.
 */
class RolloverController extends Controller
{
    // =========================================================================
    // LANDING PAGE
    // =========================================================================

    public function index()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();

        if (!$activeYear) {
            return view('rollover.index', ['activeYear' => null]);
        }

        // Hitung summary untuk landing page
        $totalSantriAktif    = Student::where('status_aktif', 'Aktif')->count();
        $totalDitempatkan     = StudentHistory::where('academic_year_id', $activeYear->id)->count();
        $totalBelumDitentukan = StudentHistory::where('academic_year_id', $activeYear->id)
            ->where('status_kenaikan', 'Belum Ditentukan')->count();
        $totalKelas           = Classroom::count();

        return view('rollover.index', compact(
            'activeYear', 'totalSantriAktif', 'totalDitempatkan',
            'totalBelumDitentukan', 'totalKelas'
        ));
    }

    // =========================================================================
    // STEP 1 — Review & Konfirmasi Siap Rollover
    // =========================================================================

    public function showStep1()
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        $summary = [
            'santri_aktif'       => Student::where('status_aktif', 'Aktif')->count(),
            'santri_ditempatkan' => StudentHistory::where('academic_year_id', $activeYear->id)->count(),
            'santri_belum'       => Student::where('status_aktif', 'Aktif')->count()
                                    - StudentHistory::where('academic_year_id', $activeYear->id)->count(),
            'kelas'              => Classroom::count(),
            'status_belum'       => StudentHistory::where('academic_year_id', $activeYear->id)
                                    ->where('status_kenaikan', 'Belum Ditentukan')->count(),
        ];

        return view('rollover.step1', compact('activeYear', 'summary'));
    }

    public function processStep1(Request $request)
    {
        // Step 1 hanya konfirmasi — tidak ada data processing
        // (user hanya baca ringkasan dan menekan "Lanjut ke Step 2")
        return redirect()->route('rollover.step2')
            ->with('info', 'Silakan tetapkan status kenaikan untuk setiap santri.');
    }

    // =========================================================================
    // STEP 2 — Penetapan Status Kenaikan Per Santri
    // =========================================================================

    public function showStep2()
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        // Ambil semua santri yang sudah ditempatkan di tahun ini
        $histories = StudentHistory::with(['student.educationLevel', 'classroom.gradeLevel'])
            ->where('academic_year_id', $activeYear->id)
            ->orderBy('class_id')
            ->get()
            ->groupBy('class_id');

        $statusOptions = ['Naik Kelas', 'Tinggal Kelas', 'Lulus', 'Mutasi'];

        $belumDitempatkan = Student::where('status_aktif', 'Aktif')
            ->whereNotIn('id', StudentHistory::where('academic_year_id', $activeYear->id)->pluck('student_id'))
            ->count();

        return view('rollover.step2', compact('activeYear', 'histories', 'statusOptions', 'belumDitempatkan'));
    }

    public function processStep2(Request $request)
    {
        $validated = $request->validate([
            'statuses'   => 'required|array',
            'statuses.*' => 'in:Naik Kelas,Tinggal Kelas,Lulus,Mutasi,Belum Ditentukan',
        ]);

        $count = 0;
        DB::transaction(function () use ($validated, &$count) {
            foreach ($validated['statuses'] as $historyId => $status) {
                StudentHistory::where('id', $historyId)->update(['status_kenaikan' => $status]);
                $count++;
            }
        });

        return redirect()->route('rollover.step3')
            ->with('success', "Status kenaikan {$count} santri berhasil disimpan. Lanjutkan ke langkah terakhir.");
    }

    // =========================================================================
    // STEP 3 — Preview & Eksekusi Rollover
    // =========================================================================

    public function showStep3()
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        // Preview data yang akan diproses
        $preview = [
            'naik'            => StudentHistory::where('academic_year_id', $activeYear->id)->where('status_kenaikan', 'Naik Kelas')->count(),
            'tinggal'         => StudentHistory::where('academic_year_id', $activeYear->id)->where('status_kenaikan', 'Tinggal Kelas')->count(),
            'lulus'           => StudentHistory::where('academic_year_id', $activeYear->id)->where('status_kenaikan', 'Lulus')->count(),
            'mutasi'          => StudentHistory::where('academic_year_id', $activeYear->id)->where('status_kenaikan', 'Mutasi')->count(),
            'belum'           => StudentHistory::where('academic_year_id', $activeYear->id)->where('status_kenaikan', 'Belum Ditentukan')->count(),
            'kelas'           => Classroom::count(),
            'kurikulum'       => Curriculum::where('academic_year_id', $activeYear->id)->count(),
        ];

        return view('rollover.step3', compact('activeYear', 'preview'));
    }

    public function processStep3(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        $validated = $request->validate([
            'nama_tahun_baru' => 'required|string|max:20|unique:academic_years,nama',
            'konfirmasi'      => 'required|accepted',
        ], [
            'nama_tahun_baru.unique' => 'Tahun ajaran dengan nama ini sudah ada.',
            'konfirmasi.accepted'    => 'Anda harus mencentang kotak konfirmasi untuk melanjutkan.',
        ]);

        $curriculumDuplikat = 0;

        DB::transaction(function () use ($activeYear, $validated, &$curriculumDuplikat) {

            // 1. Nonaktifkan tahun ajaran lama
            $activeYear->update(['is_active' => false]);

            // 2. Buat tahun ajaran baru
            $newYear = AcademicYear::create([
                'nama'      => $validated['nama_tahun_baru'],
                'is_active' => true,
            ]);

            // 2b. Auto-generate 4 Quarters
            foreach (\App\Models\AcademicTerm::PERIODE_KUARTAL as $index => $termName) {
                \App\Models\AcademicTerm::create([
                    'academic_year_id' => $newYear->id,
                    'nama'             => $termName,
                    'is_active'        => $index === 0,
                ]);
            }

            // 3. Auto-duplikasi seluruh kurikulum (mapel + KKM) ke tahun ajaran baru
            //    Admin tinggal hapus mapel yang tidak dipakai di tahun baru.
            $oldCurriculums = Curriculum::where('academic_year_id', $activeYear->id)->get();
            foreach ($oldCurriculums as $cur) {
                $exists = Curriculum::where('academic_year_id', $newYear->id)
                    ->where('grade_level_id', $cur->grade_level_id)
                    ->where('subject_id', $cur->subject_id)
                    ->exists();
                if (!$exists) {
                    Curriculum::create([
                        'academic_year_id' => $newYear->id,
                        'grade_level_id'   => $cur->grade_level_id,
                        'subject_id'       => $cur->subject_id,
                        'kkm'              => $cur->kkm,
                    ]);
                    $curriculumDuplikat++;
                }
            }

            // 4. Update status santri berdasarkan status kenaikan
            $histories = StudentHistory::where('academic_year_id', $activeYear->id)
                ->with(['student', 'classroom.gradeLevel'])
                ->get();

            foreach ($histories as $history) {
                $student = $history->student;
                if (!$student) continue;

                switch ($history->status_kenaikan) {
                    case 'Naik Kelas':
                        // Cari tingkat kelas berikutnya (dalam unit yang sama)
                        $currentGrade = $history->classroom?->gradeLevel;
                        if ($currentGrade) {
                            $nextGrade = GradeLevel::where('education_level_id', $currentGrade->education_level_id)
                                ->where('id', '>', $currentGrade->id)
                                ->orderBy('id')
                                ->first();
                            if ($nextGrade) {
                                $student->update(['current_level_id' => $nextGrade->education_level_id]);
                            }
                        }
                        break;

                    case 'Lulus':
                        $student->update(['status_aktif' => 'Lulus']);
                        break;

                    case 'Mutasi':
                        $student->update(['status_aktif' => 'Mutasi']);
                        break;

                    case 'Tinggal Kelas':
                        // Santri tetap di kelas yang sama → tidak perlu perubahan
                        break;
                }
            }
        });

        $msg = "🎉 Year-End Rollover berhasil! Tahun ajaran '{$validated['nama_tahun_baru']}' kini aktif.";
        if ($curriculumDuplikat > 0) {
            $msg .= " {$curriculumDuplikat} entri kurikulum otomatis diduplikat — silakan sesuaikan mapel yang tidak dipakai di halaman Kurikulum.";
        }

        return redirect()->route('rollover.index')->with('success', $msg);
    }
}
