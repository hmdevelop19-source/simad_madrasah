<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder: UserSeeder
 *
 * Fungsi: Membuat akun Super Admin pertama untuk bisa login ke sistem.
 * Tanpa akun ini, tidak ada yang bisa masuk ke aplikasi.
 *
 * Super Admin (role = 'super_admin'):
 * - education_level_id = NULL → Berarti akses ke SEMUA unit pendidikan.
 * - Bisa mengelola semua data: guru, santri, kurikulum, dll.
 * - Credentials ini HARUS diganti setelah pertama kali login!
 *
 * KEAMANAN: Password di-hash menggunakan bcrypt via Hash::make().
 * Password tidak pernah disimpan dalam bentuk plain text.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin (akses global, education_level_id = NULL)
        $admin = User::firstOrCreate(
            ['email' => 'superadmin@simad.sch.id'],  // Kondisi: cari berdasarkan email
            [
                'name'               => 'Super Administrator',
                'password'           => Hash::make('simad@admin2025'),  // GANTI SETELAH LOGIN PERTAMA!
                'education_level_id' => null,           // NULL = akses semua unit
                'email_verified_at'  => now(),          // Langsung verified agar bisa login
            ]
        );

        // Assign Role via Spatie
        $admin->assignRole('super_admin');

        $this->command->info('✅ Super Admin berhasil dibuat.');
        $this->command->line('   📧 Email    : superadmin@simad.sch.id');
        $this->command->line('   🔑 Password : simad@admin2025');
        $this->command->warn('   ⚠️  GANTI PASSWORD setelah login pertama!');
    }
}
