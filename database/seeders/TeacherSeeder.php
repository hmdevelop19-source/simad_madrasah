<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * TeacherSeeder — 10 data guru dengan akun login
 */
class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = [
            [
                'nip'          => '197803012005011002',
                'nama_lengkap' => 'Ustadz Ahmad Fauzi, S.Pd.I',
                'no_hp'        => '082111222333',
                'alamat'       => 'Jl. Pesantren No. 12, Kec. Ciawi',
                'email'        => 'ahmad.fauzi@simad.sch.id',
            ],
            [
                'nip'          => '198205152008012001',
                'nama_lengkap' => 'Ustadzah Siti Maryam, S.Ag',
                'no_hp'        => '081234567001',
                'alamat'       => 'Jl. Mawar No. 5, Kec. Ciawi',
                'email'        => 'siti.maryam@simad.sch.id',
            ],
            [
                'nip'          => '199001102015031004',
                'nama_lengkap' => 'Ustadz Hasan Basri, M.Pd',
                'no_hp'        => '085678901234',
                'alamat'       => 'Jl. Melati No. 8, Kec. Megamendung',
                'email'        => 'hasan.basri@simad.sch.id',
            ],
            [
                'nip'          => '198712202010012003',
                'nama_lengkap' => 'Ustadzah Fatimah Az-Zahra',
                'no_hp'        => '087890123456',
                'alamat'       => 'Jl. Anggrek Blok C no. 3',
                'email'        => 'fatimah.azzahra@simad.sch.id',
            ],
            [
                'nip'          => '199203052018031002',
                'nama_lengkap' => 'Ustadz Muhammad Iqbal',
                'no_hp'        => '089012345678',
                'alamat'       => 'Perumahan Griya Santri Blok A1',
                'email'        => 'muhammad.iqbal@simad.sch.id',
            ],
            [
                'nip'          => null,
                'nama_lengkap' => 'Ustadz Abdurrahman Al-Hafidz',
                'no_hp'        => '081198765432',
                'alamat'       => 'Pondok Pesantren Al-Hikmah, Kamar 7',
                'email'        => 'abdurrahman@simad.sch.id',
            ],
            [
                'nip'          => null,
                'nama_lengkap' => 'Ustadzah Nur Aini, S.Pd',
                'no_hp'        => '082211335577',
                'alamat'       => 'Jl. Kenanga No. 22',
                'email'        => 'nur.aini@simad.sch.id',
            ],
            [
                'nip'          => '198911102016031001',
                'nama_lengkap' => 'Bapak Rizki Ramadhan, S.Si',
                'no_hp'        => '083322446688',
                'alamat'       => 'Jl. Cempaka No. 11',
                'email'        => 'rizki.ramadhan@simad.sch.id',
            ],
            [
                'nip'          => null,
                'nama_lengkap' => 'Ibu Dewi Rahayu, S.Pd',
                'no_hp'        => '084433557799',
                'alamat'       => 'Jl. Dahlia No. 7',
                'email'        => null, // tidak punya akun login
            ],
            [
                'nip'          => null,
                'nama_lengkap' => 'Ustadz Khairul Umam',
                'no_hp'        => '085544668800',
                'alamat'       => 'Asrama Guru Lantai 2',
                'email'        => null, // tidak punya akun login
            ],
        ];

        foreach ($teachers as $t) {
            // Cek apakah sudah ada (cegah duplikasi saat re-seed)
            if (Teacher::where('nama_lengkap', $t['nama_lengkap'])->exists()) continue;

            $userId = null;
            if ($t['email']) {
                $user = User::firstOrCreate(
                    ['email' => $t['email']],
                    [
                        'name'     => $t['nama_lengkap'],
                        'password' => Hash::make('guru@simad2025'),
                    ]
                );
                
                // Assign role via Spatie
                $user->assignRole('guru');
                $userId = $user->id;
            }

            Teacher::create([
                'user_id'      => $userId,
                'nip'          => $t['nip'],
                'nama_lengkap' => $t['nama_lengkap'],
                'email'        => $t['email'], // Sinkronisasi email ke tabel guru
                'no_hp'        => $t['no_hp'],
                'alamat'       => $t['alamat'],
                'is_active'    => true,
            ]);
        }

        $this->command->info('✅ Data Guru berhasil di-seed (10 guru, 8 dengan akun login).');
        $this->command->line('   📧 Password akun guru: <comment>guru@simad2025</comment>');
    }
}
