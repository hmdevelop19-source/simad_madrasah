<?php

namespace Database\Seeders;

use App\Models\EducationLevel;
use App\Models\GradeLevel;
use Illuminate\Database\Seeder;

/**
 * GradeLevelSeeder — Tingkat kelas per unit pendidikan
 */
class GradeLevelSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'TK'   => ['RA Kecil', 'RA Besar'],
            'MI'   => ['Kelas 1', 'Kelas 2', 'Kelas 3', 'Kelas 4', 'Kelas 5', 'Kelas 6'],
            'MTS'  => ['Kelas 7', 'Kelas 8', 'Kelas 9'],
            'ULYA' => ['Kelas 10', 'Kelas 11', 'Kelas 12'],
        ];

        $total = 0;
        foreach ($data as $kode => $tingkatan) {
            $level = EducationLevel::where('kode', $kode)->first();
            if (!$level) continue;

            foreach ($tingkatan as $nama_tingkat) {
                GradeLevel::firstOrCreate(
                    ['education_level_id' => $level->id, 'nama_tingkat' => $nama_tingkat]
                );
                $total++;
            }
        }

        $this->command->info("✅ Tingkat Kelas berhasil di-seed ({$total} tingkat).");
    }
}
