<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Curriculum;
use App\Models\GradeLevel;
use App\Models\Subject;
use Illuminate\Database\Seeder;

/**
 * CurriculumSeeder — Pemetaan kurikulum untuk tahun ajaran aktif
 *
 * Logika:
 * - Ambil tahun ajaran aktif (2025/2026 Ganjil)
 * - Buat kombinasi: Tingkat Kelas × Mata Pelajaran dengan KKM masing-masing
 */
class CurriculumSeeder extends Seeder
{
    public function run(): void
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            $this->command->error('❌ Tidak ada tahun ajaran aktif!');
            return;
        }

        // KKM default per mata pelajaran
        $kkmPerMapel = [
            'MTK'  => 70, 'BIN'  => 75, 'BING' => 65, 'IPA' => 70, 'IPS' => 70,
            'PJOK' => 75, 'SBK'  => 75,
            'BAS'  => 70, 'FIQH' => 75, 'AQID' => 78, 'QHAD'=> 78,
            'SKI'  => 75, 'NAHW' => 65, 'TAHF' => 80, 'TKJD'=> 75,
        ];

        // Mapel per unit (kode)
        $mapelPerUnit = [
            'TK'   => ['AQID', 'TAHF', 'TKJD', 'BAS'],
            'MI'   => ['MTK', 'BIN', 'IPA', 'IPS', 'BAS', 'FIQH', 'AQID', 'QHAD', 'SKI', 'PJOK', 'SBK'],
            'MTS'  => ['MTK', 'BIN', 'BING', 'IPA', 'IPS', 'BAS', 'FIQH', 'AQID', 'QHAD', 'SKI', 'NAHW', 'PJOK'],
            'ULYA' => ['BAS', 'FIQH', 'AQID', 'QHAD', 'SKI', 'NAHW', 'TAHF', 'TKJD', 'MTK', 'BING'],
        ];

        $subjects    = Subject::pluck('id', 'kode_mapel')->toArray();
        $gradeLevels = GradeLevel::with('educationLevel')->get();

        $total = 0;
        foreach ($gradeLevels as $gl) {
            $unitKode = $gl->educationLevel?->kode;
            $mapelList = $mapelPerUnit[$unitKode] ?? [];

            foreach ($mapelList as $kodeMapel) {
                $subjectId = $subjects[$kodeMapel] ?? null;
                if (!$subjectId) continue;

                $kkm = $kkmPerMapel[$kodeMapel] ?? 70;

                Curriculum::firstOrCreate(
                    [
                        'academic_year_id' => $activeYear->id,
                        'grade_level_id'   => $gl->id,
                        'subject_id'       => $subjectId,
                    ],
                    ['kkm' => $kkm]
                );
                $total++;
            }
        }

        $this->command->info("✅ Kurikulum berhasil di-seed ({$total} entri untuk {$activeYear->nama}).");
    }
}
