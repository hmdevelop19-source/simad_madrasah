<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

/**
 * SubjectSeeder — Data dummy mata pelajaran khas Madrasah Diniyah
 */
class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            // Umum
            ['kode_mapel' => 'MTK',   'nama_mapel' => 'Matematika'],
            ['kode_mapel' => 'BIN',   'nama_mapel' => 'Bahasa Indonesia'],
            ['kode_mapel' => 'BING',  'nama_mapel' => 'Bahasa Inggris'],
            ['kode_mapel' => 'IPA',   'nama_mapel' => 'Ilmu Pengetahuan Alam'],
            ['kode_mapel' => 'IPS',   'nama_mapel' => 'Ilmu Pengetahuan Sosial'],
            ['kode_mapel' => 'PJOK',  'nama_mapel' => 'Pendidikan Jasmani & Olahraga'],
            ['kode_mapel' => 'SBK',   'nama_mapel' => 'Seni Budaya & Keterampilan'],
            // Agama & Madrasah
            ['kode_mapel' => 'BAS',   'nama_mapel' => 'Bahasa Arab'],
            ['kode_mapel' => 'FIQH',  'nama_mapel' => 'Fiqih'],
            ['kode_mapel' => 'AQID',  'nama_mapel' => "Aqidah Akhlaq"],
            ['kode_mapel' => 'QHAD',  'nama_mapel' => "Qur'an Hadits"],
            ['kode_mapel' => 'SKI',   'nama_mapel' => 'Sejarah Kebudayaan Islam'],
            ['kode_mapel' => 'NAHW',  'nama_mapel' => 'Nahwu Shorof'],
            ['kode_mapel' => 'TAHF',  'nama_mapel' => "Tahfidz Al-Qur'an"],
            ['kode_mapel' => 'TKJD',  'nama_mapel' => 'Tajwid'],
        ];

        foreach ($subjects as $s) {
            Subject::firstOrCreate(['kode_mapel' => $s['kode_mapel']], $s);
        }

        $this->command->info('✅ Mata Pelajaran berhasil di-seed (' . count($subjects) . ' mapel).');
    }
}
