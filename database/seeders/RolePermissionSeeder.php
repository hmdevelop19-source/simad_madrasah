<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Create Permissions
        $permissions = [
            // Dashboard
            'view-dashboard',
            'view-stats-santri',
            'view-stats-pelanggaran',
            'view-stats-izin',
            'view-stats-presensi',

            // Master Utama
            'view-unit', 'create-unit', 'edit-unit', 'delete-unit', 'approve-unit',
            'view-guru', 'create-guru', 'edit-guru', 'delete-guru', 'approve-guru',
            'view-santri', 'create-santri', 'edit-santri', 'delete-santri', 'approve-santri',
            'view-wali', 'create-wali', 'edit-wali', 'delete-wali', 'approve-wali',
            'view-tingkat', 'create-tingkat', 'edit-tingkat', 'delete-tingkat', 'approve-tingkat',
            'view-mapel', 'create-mapel', 'edit-mapel', 'delete-mapel', 'approve-mapel',
            'view-rombel', 'create-rombel', 'edit-rombel', 'delete-rombel', 'approve-rombel',

            // Akademik
            'view-tahun', 'create-tahun', 'edit-tahun', 'delete-tahun', 'approve-tahun',
            'view-kuartal', 'create-kuartal', 'edit-kuartal', 'delete-kuartal', 'approve-kuartal',
            'view-katalog', 'create-katalog', 'edit-katalog', 'delete-katalog', 'approve-katalog',
            'view-distribusi', 'create-distribusi', 'edit-distribusi', 'delete-distribusi', 'approve-distribusi',
            'view-rollover', 'create-rollover', 'edit-rollover', 'delete-rollover', 'approve-rollover',

            // Evaluasi & Nilai
            'view-presensi', 'create-presensi', 'edit-presensi', 'delete-presensi', 'approve-presensi',
            'view-nilai', 'create-nilai', 'edit-nilai', 'delete-nilai', 'approve-nilai',
            'view-kepribadian', 'create-kepribadian', 'edit-kepribadian', 'delete-kepribadian', 'approve-kepribadian',
            'view-raport', 'create-raport', 'edit-raport', 'delete-raport', 'approve-raport',

            // Sistem & Keamanan
            'manage-hak-akses',
            'manage-role',
            'manage-profil-induk',
            'manage-profil-app'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3. Create Roles and Assign Permissions
        
        // Super Admin (Semua Hak Akses)
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->syncPermissions(Permission::all());

        // Kepala Sekolah (Monitoring Utama)
        $kepalaSekolah = Role::firstOrCreate(['name' => 'kepala_sekolah']);
        $kepalaSekolah->syncPermissions([
            'view-dashboard',
            'view-stats-santri',
            'view-stats-presensi',
            'view-raport',
        ]);

        // Guru (Operasional Input)
        $guru = Role::firstOrCreate(['name' => 'guru']);
        $guru->syncPermissions([
            'view-dashboard',
            'view-presensi', 'create-presensi', 'edit-presensi',
            'view-nilai', 'create-nilai', 'edit-nilai',
        ]);

        // Wali Kelas (Guru + Raport)
        $waliKelas = Role::firstOrCreate(['name' => 'wali_kelas']);
        $waliKelas->syncPermissions([
            'view-dashboard',
            'view-presensi', 'create-presensi', 'edit-presensi',
            'view-nilai', 'create-nilai', 'edit-nilai',
            'view-raport', 'create-raport', 'print-reports', // keeping legacy for compat during transition if needed
            'view-kepribadian', 'create-kepribadian',
        ]);

        // 4. Migrate Existing Users
        $users = User::all();
        foreach ($users as $user) {
            if ($user->role) {
                // If the user has a role string, assign the corresponding Spatie role
                $user->assignRole($user->role);
            }
        }

        $this->command->info('✅ Roles and Permissions seeded and migrated successfully.');
    }
}
