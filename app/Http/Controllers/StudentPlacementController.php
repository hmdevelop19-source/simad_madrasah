<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\EducationLevel;
use App\Models\GradeLevel;
use App\Models\Student;
use App\Models\StudentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * StudentPlacementController
 *
 * Mengelola penempatan santri ke kelas per tahun ajaran.
 * Setiap penempatan = 1 baris di tabel `student_histories`.
 *
 * Alur kerja:
 *   1. Admin buka halaman → lihat semua santri aktif + status penempatan di tahun ajaran aktif
 *   2. Santri yang belum ditempatkan di-highlight
 *   3. Admin assign santri ke kelas (satu-satu atau bulk)
 */
class StudentPlacementController extends Controller
{
    /**
     * Tampilkan semua santri aktif beserta status penempatan di tahun ajaran aktif.
     */
    public function index(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();

        if (!$activeYear) {
            return back()->with('error', 'Belum ada Tahun Ajaran yang aktif. Silakan aktifkan terlebih dahulu.');
        }

        $levelFilter   = $request->get('education_level_id');
        $classFilter   = $request->get('classroom_id');
        $statusFilter  = $request->get('status', 'semua');  // semua | ditempatkan | belum
        $kenaikanFilter = $request->get('kenaikan', 'semua'); // semua | naik | tinggal | lulus | baru

        // ── Ambil tahun ajaran SEBELUMNYA (non-aktif, paling baru) ──────────
        $prevYear = AcademicYear::where('is_active', false)
            ->orderByDesc('updated_at')
            ->first();

        // Map: student_id → [status_kenaikan, kelas_lama] dari tahun lalu
        $prevStatusMap = [];
        if ($prevYear) {
            StudentHistory::where('academic_year_id', $prevYear->id)
                ->with('classroom:id,nama_kelas')
                ->get()
                ->each(function ($h) use (&$prevStatusMap) {
                    $prevStatusMap[$h->student_id] = [
                        'status'    => $h->status_kenaikan,
                        'kelas'     => $h->classroom?->nama_kelas,
                    ];
                });
        }

        // ── IDs santri yang sudah ditempatkan di tahun aktif ───────────────
        $placedIds = StudentHistory::where('academic_year_id', $activeYear->id)
            ->pluck('student_id');

        $query = Student::with([
                'educationLevel',
                'histories' => fn($q) => $q->where('academic_year_id', $activeYear->id)->with('classroom.gradeLevel'),
            ])
            ->where('status_aktif', 'Aktif')
            ->orderBy('nama_lengkap');

        if ($levelFilter) {
            $query->where('current_level_id', $levelFilter);
        }

        if ($statusFilter === 'belum') {
            $query->whereNotIn('id', $placedIds);
        } elseif ($statusFilter === 'ditempatkan') {
            $query->whereIn('id', $placedIds);
        }

        // Filter berdasarkan status kenaikan tahun lalu
        if ($kenaikanFilter !== 'semua' && $prevYear) {
            $filteredByKenaikan = collect($prevStatusMap)
                ->filter(fn($v) => match($kenaikanFilter) {
                    'naik'   => $v['status'] === 'Naik Kelas',
                    'tinggal'=> $v['status'] === 'Tinggal Kelas',
                    'lulus'  => $v['status'] === 'Lulus',
                    'baru'   => false, // dihandle terpisah di bawah
                    default  => true,
                })
                ->keys();

            if ($kenaikanFilter === 'baru') {
                // Santri baru = tidak ada di prevStatusMap
                $query->whereNotIn('id', array_keys($prevStatusMap));
            } else {
                $query->whereIn('id', $filteredByKenaikan);
            }
        }

        $students        = $query->paginate(25)->withQueryString();
        $educationLevels = EducationLevel::orderBy('nama')->get();
        $classrooms      = Classroom::with('gradeLevel.educationLevel')
            ->when($levelFilter, fn($q) => $q->whereHas('gradeLevel', fn($q2) => $q2->where('education_level_id', $levelFilter)))
            ->orderBy('nama_kelas')->get();

        // Summary counts
        $totalAktif       = Student::where('status_aktif', 'Aktif')->count();
        $totalDitempatkan  = $placedIds->count();
        $totalBelum       = $totalAktif - $totalDitempatkan;

        // Summary kenaikan
        $summaryKenaikan = [
            'naik'    => collect($prevStatusMap)->where('status', 'Naik Kelas')->count(),
            'tinggal' => collect($prevStatusMap)->where('status', 'Tinggal Kelas')->count(),
            'lulus'   => collect($prevStatusMap)->where('status', 'Lulus')->count(),
            'baru'    => $totalAktif - count($prevStatusMap),
        ];

        return view('student-placements.index', compact(
            'students', 'activeYear', 'prevYear', 'educationLevels', 'classrooms',
            'totalAktif', 'totalDitempatkan', 'totalBelum',
            'levelFilter', 'classFilter', 'statusFilter', 'kenaikanFilter',
            'prevStatusMap', 'summaryKenaikan'
        ));
    }


    /**
     * Assign satu santri ke kelas tertentu.
     */
    public function store(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        $validated = $request->validate([
            'student_id'   => 'required|exists:students,id',
            'class_id'     => 'required|exists:classrooms,id',
        ]);

        // Cek apakah sudah ada penempatan di tahun ini
        $existing = StudentHistory::where('student_id', $validated['student_id'])
            ->where('academic_year_id', $activeYear->id)
            ->first();

        if ($existing) {
            $existing->update(['class_id' => $validated['class_id']]);
            $msg = 'Penempatan diperbarui.';
        } else {
            StudentHistory::create([
                'student_id'       => $validated['student_id'],
                'academic_year_id' => $activeYear->id,
                'class_id'         => $validated['class_id'],
                'status_kenaikan'  => 'Belum Ditentukan',
            ]);
            $msg = 'Santri berhasil ditempatkan ke kelas.';
        }

        return back()->with('success', $msg);
    }

    /**
     * Bulk assign: banyak santri ke satu kelas sekaligus.
     */
    public function bulkStore(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        $validated = $request->validate([
            'class_id'    => 'required|exists:classrooms,id',
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
        ]);

        $count = 0;
        DB::transaction(function () use ($validated, $activeYear, &$count) {
            foreach ($validated['student_ids'] as $studentId) {
                StudentHistory::updateOrCreate(
                    ['student_id' => $studentId, 'academic_year_id' => $activeYear->id],
                    ['class_id' => $validated['class_id'], 'status_kenaikan' => 'Belum Ditentukan']
                );
                $count++;
            }
        });

        return back()->with('success', "{$count} santri berhasil ditempatkan ke kelas.");
    }

    /**
     * Pindahkan santri ke kelas lain.
     */
    public function update(Request $request, StudentHistory $studentPlacement)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classrooms,id',
        ]);

        $studentPlacement->update(['class_id' => $validated['class_id']]);

        return back()->with('success', 'Santri berhasil dipindahkan ke kelas baru.');
    }

    /**
     * Hapus penempatan santri dari kelas (hanya tahun aktif).
     */
    public function destroy(StudentHistory $studentPlacement)
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        if ($studentPlacement->academic_year_id !== $activeYear->id) {
            return back()->with('error', 'Hanya penempatan tahun ajaran aktif yang bisa dihapus.');
        }

        $studentPlacement->delete();
        return back()->with('success', 'Penempatan santri dihapus.');
    }
}
