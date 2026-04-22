<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Seeder: PermissionSyncSeeder
 *
 * Menghubungkan label menu di Matrix UI dengan Permission asli di database.
 * Melengkapi seluruh permission yang diperlukan sidebar namun belum ada di DB.
 */
class PermissionSyncSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar Slugs yang digunakan di Sidebar & Role Matrix
        $modules = [
            'dashboard',
            // Master Utama
            'unit', 'guru', 'santri', 'wali', 'tingkat', 'mapel', 'rombel',
            // Akademik
            'tahun', 'kuartal', 'katalog', 'distribusi', 'rollover',
            // Evaluasi
            'presensi', 'nilai', 'kepribadian', 'raport',
            // Sistem
            'hak-akses', 'role', 'user', 'profil-induk', 'profil-app'
        ];

        $actions = ['view', 'create', 'edit', 'delete', 'approve'];

        foreach ($modules as $m) {
            foreach ($actions as $a) {
                $permName = $a . '-' . $m;
                Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
            }
        }

        // 1. Roles: Guru (Hanya modul evaluasi - VIEW saja awal)
        /** @var Role $guruRole */
        $guruRole = Role::where('name', 'guru')->first();
        if ($guruRole) {
            $guruRole->syncPermissions([
                'view-dashboard', 'view-presensi', 'create-presensi', 
                'view-nilai', 'create-nilai', 
                'view-kepribadian', 'create-kepribadian'
            ]);
        }

        // 2. Roles: Wali Kelas (Evaluasi + Data Santri/Rombel VIEW)
        /** @var Role $waliRole */
        $waliRole = Role::where('name', 'wali_kelas')->first();
        if ($waliRole) {
            $waliRole->syncPermissions([
                'view-dashboard', 'view-santri', 'view-rombel', 
                'view-presensi', 'create-presensi', 
                'view-nilai', 'create-nilai', 
                'view-kepribadian', 'create-kepribadian', 
                'view-raport'
            ]);
        }

        // 3. Roles: Kepala Sekolah (Hampir semuanya - VIEW)
        /** @var Role $ksRole */
        $ksRole = Role::where('name', 'kepala_sekolah')->first();
        if ($ksRole) {
            $ksPermissions = [];
            foreach (['unit', 'guru', 'santri', 'wali', 'tingkat', 'mapel', 'rombel', 'tahun', 'kuartal', 'katalog', 'distribusi', 'presensi', 'nilai', 'raport'] as $m) {
                $ksPermissions[] = 'view-' . $m;
            }
            $ksPermissions[] = 'view-dashboard';
            $ksRole->syncPermissions($ksPermissions);
        }

        $this->command->info('✅ Seluruh permissions (100+ item) berhasil disinkronkan dan dihubungkan ke role.');
    }
}
