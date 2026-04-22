<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\EducationLevel;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * DashboardController — Halaman Utama Setelah Login
 *
 * Mengambil data statistik real dari database dan mengirimkannya ke view.
 * Data yang ditampilkan DISESUAIKAN dengan role user yang login:
 *
 * - super_admin    : Statistik global seluruh unit pendidikan
 * - kepala_sekolah : Statistik unit yang dipimpinnya saja
 * - guru/wali_kelas: Statistik minimal (hanya unit yang diampu)
 */
class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load('educationLevel');

        // ── Ambil Tahun Ajaran Aktif ──────────────────────────────────────────
        // Dipakai di topbar layout dan bisa dipakai di view untuk konteks data
        $activeTahunAjaran = AcademicYear::where('is_active', true)->first();

        // ── Statistik berdasarkan Role ────────────────────────────────────────
        if ($user->isSuperAdmin()) {
            // Super Admin: lihat semua data lintas unit
            $stats = $this->getGlobalStats();
        } else {
            // Role lain: filter berdasarkan education_level_id user
            $stats = $this->getUnitStats($user->education_level_id);
        }

        // ── Data per Unit (untuk kartu ringkasan di dashboard Super Admin) ──
        $educationLevels = [];
        if ($user->hasRole('super_admin')) {
            $stats['teachers'] = Teacher::count();
            // Ambil semua unit beserta jumlah santri aktifnya
            // withCount() → menambahkan kolom 'students_count' secara efisien (1 query)
            $educationLevels = EducationLevel::withCount([
                // Hanya hitung santri dengan status aktif
                'students' => fn($q) => $q->where('status_aktif', 'Aktif')
            ])->get();
        }

        return view('dashboard', compact(
            'user',
            'stats',
            'activeTahunAjaran',
            'educationLevels'
        ));
    }

    /**
     * Statistik Global — untuk Super Admin
     * Menghitung jumlah dari SEMUA unit pendidikan tanpa filter.
     *
     * Menggunakan query terpisah agar mudah dipahami dan dioptimasi nanti.
     */
    private function getGlobalStats(): array
    {
        return [
            // Santri dengan status aktif di database (bukan soft-deleted)
            'total_santri'  => Student::where('status_aktif', 'Aktif')->count(),

            // Guru yang aktif (is_active = true dan belum di-soft-delete)
            'total_guru'    => Teacher::where('is_active', true)->count(),

            // Total ruang kelas yang terdaftar
            'total_kelas'   => Classroom::count(),

            // Total mata pelajaran di katalog global
            'total_mapel'   => Subject::count(),
        ];
    }

    /**
     * Statistik Per Unit — untuk Kepala Sekolah, Guru, Wali Kelas
     * Memfilter data hanya pada unit pendidikan yang sesuai.
     *
     * @param int|null $educationLevelId — ID unit pendidikan user
     */
    private function getUnitStats(?int $educationLevelId): array
    {
        // Jika education_level_id null (seharusnya tidak terjadi untuk non-super-admin)
        // return stats kosong untuk menghindari error
        if (!$educationLevelId) {
            return ['total_santri' => 0, 'total_guru' => 0, 'total_kelas' => 0, 'total_mapel' => 0];
        }

        return [
            // Filter santri berdasarkan unit pendidikan mereka saat ini
            'total_santri' => Student::where('status_aktif', 'Aktif')
                ->where('current_level_id', $educationLevelId)
                ->count(),

            // Guru tidak langsung terikat ke unit, so gunakan global untuk simplifikasi
            // Nanti di Fase 3+ bisa difilter via TeacherAssignment
            'total_guru'   => Teacher::where('is_active', true)->count(),

            // Kelas difilter via GradeLevel yang terhubung ke education_level_id
            'total_kelas'  => Classroom::whereHas('gradeLevel', function ($q) use ($educationLevelId) {
                $q->where('education_level_id', $educationLevelId);
            })->count(),

            'total_mapel'  => Subject::count(),
        ];
    }
}
