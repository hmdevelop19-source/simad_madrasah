<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder — Orchestrator Seeder Utama
 *
 * Urutan eksekusi sesuai ketergantungan Foreign Key:
 *
 *   1.  EducationLevels → Fondasi utama (TK, MI, MTs, Ulya)
 *   2.  Users           → Super Admin
 *   3.  AcademicYear    → Tahun ajaran aktif
 *   4.  Subjects        → Mata pelajaran (15 mapel)
 *   5.  Teachers        → Data guru (10 guru, 8 punya akun)
 *   6.  GradeLevels     → Tingkat kelas (FK → education_levels)
 *   7.  Classrooms      → Kelas/rombel (FK → grade_levels)
 *   8.  WaliSantri      → Data wali (15 wali)
 *   9.  Students        → Santri (30 santri, FK → education_levels + wali_santri)
 *   10. Curriculums     → Pemetaan kurikulum (FK → academic_years, grade_levels, subjects)
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Memulai seeding database SIMAD...');
        $this->command->newLine();

        $this->call([
            EducationLevelSeeder::class,  // 1️⃣  Unit Pendidikan (TK, MI, MTs, Ulya)
            RolePermissionSeeder::class,  // 2️⃣  Roles and Permissions
            UserSeeder::class,            // 3️⃣  Akun Super Admin
            AcademicYearSeeder::class,    // 4️⃣  Tahun Ajaran aktif 2025/2026
            SubjectSeeder::class,         // 5️⃣  Mata Pelajaran (15 mapel)
            TeacherSeeder::class,         // 6️⃣  Data Guru (10 guru, 8 dengan akun login)
            GradeLevelSeeder::class,      // 7️⃣  Tingkat Kelas (2 per unit kecuali RA)
            ClassroomSeeder::class,       // 8️⃣  Kelas/Rombel (A & B per tingkat)
            WaliSantriSeeder::class,      // 9️⃣  Data Wali Santri (15 wali)
            StudentSeeder::class,         // 🔟 Data Santri (30 santri)
            CurriculumSeeder::class,      // 11 Pemetaan Kurikulum
        ]);

        $this->command->newLine();
        $this->command->info('✅ Seeding selesai! Aplikasi SIMAD siap digunakan.');
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->line('🌐 URL    : http://127.0.0.1:8000');
        $this->command->line('📧 Admin  : superadmin@simad.sch.id  |  🔑 simad@admin2025');
        $this->command->line('📧 Guru   : [nama]@simad.sch.id       |  🔑 guru@simad2025');
        $this->command->warn('⚠️  Segera ganti password setelah login pertama!');
    }
}