<?php

namespace Database\Seeders;

use App\Models\EducationLevel;
use Illuminate\Database\Seeder;

/**
 * Seeder: EducationLevelSeeder
 *
 * Fungsi: Mengisi tabel 'education_levels' dengan 4 unit pendidikan
 * yang ada di Pesantren/Madrasah sesuai PRD.
 *
 * Kenapa di-seed? Karena data ini adalah "pondasi" dari seluruh sistem.
 * Hampir semua tabel lain (students, users, grade_levels, dll.)
 * ber-FK ke tabel ini. Harus ada sebelum data lain bisa dimasukkan.
 *
 * Menggunakan firstOrCreate() agar aman dijalankan BERULANG KALI
 * tanpa menyebabkan duplikasi data.
 */
class EducationLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            // 'kode' dipakai sebagai singkatan di berbagai tampilan
            ['kode' => 'TK',    'nama' => 'Taman Kanak-Kanak'],
            ['kode' => 'MI',    'nama' => 'Madrasah Ibtidaiyah'],
            ['kode' => 'MTS',   'nama' => 'Madrasah Tsanawiyah'],
            ['kode' => 'ULYA',  'nama' => 'Madrasah Aliyah / Ulya'],
        ];

        foreach ($levels as $level) {
            // firstOrCreate: Cari data dengan kondisi 'kode', jika tidak ada → buat baru.
            // Aman untuk diulang: tidak akan duplikat jika seeder dijalankan lagi.
            EducationLevel::firstOrCreate(
                ['kode' => $level['kode']],    // Kondisi pencarian
                ['nama' => $level['nama']]      // Data yang diisi jika dibuat baru
            );
        }

        $this->command->info('✅ Education Levels berhasil di-seed: TK, MI, MTs, Ulya');
    }
}
