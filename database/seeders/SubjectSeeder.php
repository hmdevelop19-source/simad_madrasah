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
            ['kode_mapel' => 'MTK',   'nama_mapel' => 'Matematika', 'kategori_mapel' => 'Nasional'],
            ['kode_mapel' => 'BIN',   'nama_mapel' => 'Bahasa Indonesia', 'kategori_mapel' => 'Nasional'],
            ['kode_mapel' => 'BING',  'nama_mapel' => 'Bahasa Inggris', 'kategori_mapel' => 'Nasional'],
            ['kode_mapel' => 'IPA',   'nama_mapel' => 'Ilmu Pengetahuan Alam', 'kategori_mapel' => 'Nasional'],
            ['kode_mapel' => 'IPS',   'nama_mapel' => 'Ilmu Pengetahuan Sosial', 'kategori_mapel' => 'Nasional'],
            ['kode_mapel' => 'PJOK',  'nama_mapel' => 'Pendidikan Jasmani & Olahraga', 'kategori_mapel' => 'Nasional'],
            ['kode_mapel' => 'SBK',   'nama_mapel' => 'Seni Budaya & Keterampilan', 'kategori_mapel' => 'Nasional'],
            // Agama & Madrasah
            ['kode_mapel' => 'BAS',   'nama_mapel' => 'Bahasa Arab', 'kategori_mapel' => 'Kepesantrenan'],
            ['kode_mapel' => 'FIQH',  'nama_mapel' => 'Fiqih', 'kategori_mapel' => 'Kepesantrenan'],
            ['kode_mapel' => 'AQID',  'nama_mapel' => "Aqidah Akhlaq", 'kategori_mapel' => 'Kepesantrenan'],
            ['kode_mapel' => 'QHAD',  'nama_mapel' => "Qur'an Hadits", 'kategori_mapel' => 'Kepesantrenan'],
            ['kode_mapel' => 'SKI',   'nama_mapel' => 'Sejarah Kebudayaan Islam', 'kategori_mapel' => 'Kepesantrenan'],
            ['kode_mapel' => 'NAHW',  'nama_mapel' => 'Nahwu Shorof', 'kategori_mapel' => 'Kepesantrenan'],
            ['kode_mapel' => 'TAHF',  'nama_mapel' => "Tahfidz Al-Qur'an", 'kategori_mapel' => 'Kepesantrenan'],
            ['kode_mapel' => 'TKJD',  'nama_mapel' => 'Tajwid', 'kategori_mapel' => 'Kepesantrenan'],
        ];

        foreach ($subjects as $s) {
            Subject::firstOrCreate(['kode_mapel' => $s['kode_mapel']], $s);
        }

        $this->command->info('✅ Mata Pelajaran berhasil di-seed (' . count($subjects) . ' mapel).');
    }
}
