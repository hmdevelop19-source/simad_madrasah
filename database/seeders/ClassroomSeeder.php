<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\GradeLevel;
use Illuminate\Database\Seeder;

/**
 * ClassroomSeeder — Kelas per tingkat
 * Setiap tingkat diberi 2 rombel (A dan B) kecuali RA yang hanya 1
 */
class ClassroomSeeder extends Seeder
{
    public function run(): void
    {
        $gradeLevels = GradeLevel::all();
        $total = 0;

        foreach ($gradeLevels as $gl) {
            // RA: hanya 1 kelas tanpa suffix
            if (str_contains($gl->nama_tingkat, 'RA')) {
                Classroom::firstOrCreate(
                    ['grade_level_id' => $gl->id, 'nama_kelas' => $gl->nama_tingkat],
                    ['wali_kelas_id' => null]
                );
                $total++;
            } else {
                // Semua lainnya: 2 rombel A dan B
                foreach (['A', 'B'] as $rombel) {
                    // Ambil nomor kelas dari nama, misal "Kelas 7" → "7-A"
                    $nomor = preg_replace('/[^0-9]/', '', $gl->nama_tingkat);
                    $nama  = "Kelas {$nomor}-{$rombel}";

                    Classroom::firstOrCreate(
                        ['grade_level_id' => $gl->id, 'nama_kelas' => $nama],
                        ['wali_kelas_id' => null]
                    );
                    $total++;
                }
            }
        }

        $this->command->info("✅ Kelas berhasil di-seed ({$total} kelas).");
    }
}
