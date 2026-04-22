<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

/**
 * Seeder: AcademicYearSeeder
 *
 * Fungsi: Membuat satu record Tahun Ajaran aktif sebagai titik awal sistem.
 * Tanpa Tahun Ajaran aktif, sebagian besar fitur (presensi, nilai, kurikulum)
 * tidak akan bisa berjalan karena semua data transaksional berelasi ke sini.
 *
 * Hanya SATU tahun ajaran yang boleh is_active = true pada satu waktu.
 * Logika ini akan ditegakkan di AcademicYearController nanti.
 */
class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan tidak ada duplikasi: cek apakah sudah ada yang aktif
        if (AcademicYear::where('is_active', true)->exists()) {
            $this->command->warn('⚠️  Tahun Ajaran aktif sudah ada, skip seeder ini.');
            return;
        }

        AcademicYear::create([
            'nama'      => '2025/2026',
            'periode'   => 'Ganjil',    // Enum: Ganjil, Genap, Kuartal 1-4
            'is_active' => true,
        ]);

        $this->command->info('✅ Tahun Ajaran 2025/2026 Semester Ganjil berhasil dibuat (AKTIF).');
    }
}
