<?php

namespace Database\Seeders;

use App\Models\WaliSantri;
use Illuminate\Database\Seeder;

/**
 * WaliSantriSeeder — 15 data wali santri realistis
 */
class WaliSantriSeeder extends Seeder
{
    public function run(): void
    {
        $waliList = [
            [
                'nik'                 => '3201010102800001',
                'nama_lengkap'        => 'Bapak Hendra Kurniawan',
                'hubungan_keluarga'   => 'Ayah',
                'pendidikan_terakhir' => 'S1',
                'pekerjaan'           => 'Pegawai Swasta',
                'penghasilan_bulanan' => '3-5 Juta',
                'no_whatsapp'         => '081234560001',
                'alamat_lengkap'      => 'Jl. Merdeka No. 15, RT 03/02, Desa Ciawi, Kec. Ciawi, Kab. Bogor',
            ],
            [
                'nik'                 => '3201010203750002',
                'nama_lengkap'        => 'Ibu Rina Marlina',
                'hubungan_keluarga'   => 'Ibu',
                'pendidikan_terakhir' => 'SMA/MA',
                'pekerjaan'           => 'Ibu Rumah Tangga',
                'penghasilan_bulanan' => '< 1 Juta',
                'no_whatsapp'         => '081234560002',
                'alamat_lengkap'      => 'Kp. Babakan RT 05/01, Desa Megamendung',
            ],
            [
                'nik'                 => '3201010304850003',
                'nama_lengkap'        => 'Bapak Agus Setiawan',
                'hubungan_keluarga'   => 'Ayah',
                'pendidikan_terakhir' => 'S1',
                'pekerjaan'           => 'Guru',
                'penghasilan_bulanan' => '3-5 Juta',
                'no_whatsapp'         => '082345670003',
                'alamat_lengkap'      => 'Jl. Pahlawan No. 8, Kec. Cisarua, Kab. Bogor',
            ],
            [
                'nik'                 => '3201010405780004',
                'nama_lengkap'        => 'Bapak Deden Suherman',
                'hubungan_keluarga'   => 'Ayah',
                'pendidikan_terakhir' => 'SMP/MTs',
                'pekerjaan'           => 'Petani',
                'penghasilan_bulanan' => '< 1 Juta',
                'no_whatsapp'         => '083456780004',
                'alamat_lengkap'      => 'Kp. Pasir Pogor, Desa Bojong Murni, Kec. Ciawi',
            ],
            [
                'nik'                 => '3201010506820005',
                'nama_lengkap'        => 'Bapak Yusuf Hidayat',
                'hubungan_keluarga'   => 'Ayah',
                'pendidikan_terakhir' => 'S1',
                'pekerjaan'           => 'Wiraswasta',
                'penghasilan_bulanan' => '5-10 Juta',
                'no_whatsapp'         => '084567890005',
                'alamat_lengkap'      => 'Perumahan Taman Cimanggu Blok D5, Kota Bogor',
            ],
            [
                'nik'                 => '3201010607900006',
                'nama_lengkap'        => 'Ibu Siti Komariah',
                'hubungan_keluarga'   => 'Ibu',
                'pendidikan_terakhir' => 'SMA/MA',
                'pekerjaan'           => 'Pedagang',
                'penghasilan_bulanan' => '1-3 Juta',
                'no_whatsapp'         => '085678900006',
                'alamat_lengkap'      => 'Jl. Raya Puncak No. 33, Kec. Cisarua',
            ],
            [
                'nik'                 => '3201010708750007',
                'nama_lengkap'        => 'Bapak Bambang Wahyudi',
                'hubungan_keluarga'   => 'Ayah',
                'pendidikan_terakhir' => 'S2',
                'pekerjaan'           => 'PNS',
                'penghasilan_bulanan' => '5-10 Juta',
                'no_whatsapp'         => '086789010007',
                'alamat_lengkap'      => 'Jl. Veteran No. 22, Kab. Bogor',
            ],
            [
                'nik'                 => '3201010809880008',
                'nama_lengkap'        => 'Bapak Rudi Hartono',
                'hubungan_keluarga'   => 'Ayah',
                'pendidikan_terakhir' => 'D3',
                'pekerjaan'           => 'Teknisi',
                'penghasilan_bulanan' => '3-5 Juta',
                'no_whatsapp'         => '087890120008',
                'alamat_lengkap'      => 'Perum Griya Indah No. 17, Kec. Ciawi',
            ],
            [
                'nik'                 => '3201010900820009',
                'nama_lengkap'        => 'Bapak Usman Hakim',
                'hubungan_keluarga'   => 'Wali Lainnya',
                'pendidikan_terakhir' => 'S1',
                'pekerjaan'           => 'Ustadz / Da\'i',
                'penghasilan_bulanan' => '1-3 Juta',
                'no_whatsapp'         => '088901230009',
                'alamat_lengkap'      => 'Pondok Pesantren Al-Hidayah, Kec. Megamendung',
            ],
            [
                'nik'                 => '3201011001860010',
                'nama_lengkap'        => 'Ibu Dewi Susanti',
                'hubungan_keluarga'   => 'Ibu',
                'pendidikan_terakhir' => 'S1',
                'pekerjaan'           => 'Bidan',
                'penghasilan_bulanan' => '3-5 Juta',
                'no_whatsapp'         => '089012340010',
                'alamat_lengkap'      => 'Jl. Flamboyan No. 9, Kel. Ciawi',
            ],
            [
                'nik'                 => '3201011102790011',
                'nama_lengkap'        => 'Bapak Iwan Firdaus',
                'hubungan_keluarga'   => 'Ayah',
                'pendidikan_terakhir' => 'SMA/MA',
                'pekerjaan'           => 'Supir',
                'penghasilan_bulanan' => '1-3 Juta',
                'no_whatsapp'         => '081123450011',
                'alamat_lengkap'      => 'Kp. Cibitung RT 02/04, Desa Cibedug',
            ],
            [
                'nik'                 => '3201011203910012',
                'nama_lengkap'        => 'Bapak Fajar Nugroho',
                'hubungan_keluarga'   => 'Ayah',
                'pendidikan_terakhir' => 'S1',
                'pekerjaan'           => 'Programmer',
                'penghasilan_bulanan' => '5-10 Juta',
                'no_whatsapp'         => '082234560012',
                'alamat_lengkap'      => 'Perum Bukit Sentul Blok A3, Kab. Bogor',
            ],
            [
                'nik'                 => '3201011304840013',
                'nama_lengkap'        => 'Bapak Suharto Priyatno',
                'hubungan_keluarga'   => 'Kakek',
                'pendidikan_terakhir' => 'SD/MI',
                'pekerjaan'           => 'Pensiunan',
                'penghasilan_bulanan' => '1-3 Juta',
                'no_whatsapp'         => '083345670013',
                'alamat_lengkap'      => 'Jl. Bougenvile No. 4, Kec. Ciawi',
            ],
            [
                'nik'                 => '3201011405870014',
                'nama_lengkap'        => 'Ibu Neng Haerunnisa',
                'hubungan_keluarga'   => 'Ibu',
                'pendidikan_terakhir' => 'D3',
                'pekerjaan'           => 'Perawat',
                'penghasilan_bulanan' => '3-5 Juta',
                'no_whatsapp'         => '084456780014',
                'alamat_lengkap'      => 'Jl. Ciawi Baru No. 12, Kab. Bogor',
            ],
            [
                'nik'                 => '3201011506930015',
                'nama_lengkap'        => 'Bapak Didin Jamaludin',
                'hubungan_keluarga'   => 'Ayah',
                'pendidikan_terakhir' => 'S1',
                'pekerjaan'           => 'Kontraktor',
                'penghasilan_bulanan' => '> 10 Juta',
                'no_whatsapp'         => '085567890015',
                'alamat_lengkap'      => 'Jl. Raya Tajur No. 88, Kota Bogor',
            ],
        ];

        foreach ($waliList as $w) {
            WaliSantri::firstOrCreate(['nik' => $w['nik']], $w);
        }

        $this->command->info('✅ Data Wali Santri berhasil di-seed (15 wali).');
    }
}
