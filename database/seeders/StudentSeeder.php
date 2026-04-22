<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\EducationLevel;
use App\Models\Student;
use App\Models\WaliSantri;
use Illuminate\Database\Seeder;

/**
 * StudentSeeder — 30 data santri tersebar di 4 unit pendidikan
 */
class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $waliIds  = WaliSantri::pluck('id')->toArray();
        $levelMap = EducationLevel::pluck('id', 'kode')->toArray(); // ['TK'=>1, 'MI'=>2, ...]

        $students = [
            // ===== TK / RA (5 santri)
            ['nisn'=>null,       'nik'=>'3201010100150001','no_kk'=>'3201010100150001','nama_lengkap'=>'Fathiya Rahma Aulia',      'tempat_lahir'=>'Bogor','tanggal_lahir'=>'2021-03-10','jenis_kelamin'=>'P','kode_unit'=>'TK','wali_idx'=>0],
            ['nisn'=>null,       'nik'=>'3201010200150002','no_kk'=>'3201010200150001','nama_lengkap'=>'Muhammad Dzakir Al-Faruq',  'tempat_lahir'=>'Bogor','tanggal_lahir'=>'2021-07-15','jenis_kelamin'=>'L','kode_unit'=>'TK','wali_idx'=>1],
            ['nisn'=>null,       'nik'=>'3201010300150003','no_kk'=>'3201010300150001','nama_lengkap'=>'Aisyah Putri Utami',        'tempat_lahir'=>'Depok','tanggal_lahir'=>'2021-05-22','jenis_kelamin'=>'P','kode_unit'=>'TK','wali_idx'=>2],
            ['nisn'=>null,       'nik'=>'3201010400150004','no_kk'=>'3201010400150001','nama_lengkap'=>'Yahya Naufal Hamid',        'tempat_lahir'=>'Bogor','tanggal_lahir'=>'2020-11-05','jenis_kelamin'=>'L','kode_unit'=>'TK','wali_idx'=>3],
            ['nisn'=>null,       'nik'=>'3201010500150005','no_kk'=>'3201010500150001','nama_lengkap'=>'Khadijah Salsabila',        'tempat_lahir'=>'Ciawi','tanggal_lahir'=>'2020-09-18','jenis_kelamin'=>'P','kode_unit'=>'TK','wali_idx'=>4],
            // ===== MI (10 santri)
            ['nisn'=>'0123456780','nik'=>'3201010600130001','no_kk'=>'3201010100130001','nama_lengkap'=>'Ahmad Rizki Maulana',       'tempat_lahir'=>'Bogor','tanggal_lahir'=>'2015-04-12','jenis_kelamin'=>'L','kode_unit'=>'MI','wali_idx'=>5],
            ['nisn'=>'0123456781','nik'=>'3201010700130002','no_kk'=>'3201010200130001','nama_lengkap'=>'Nurul Fadhilah',            'tempat_lahir'=>'Sukabumi','tanggal_lahir'=>'2014-08-30','jenis_kelamin'=>'P','kode_unit'=>'MI','wali_idx'=>6],
            ['nisn'=>'0123456782','nik'=>'3201010800130003','no_kk'=>'3201010300130001','nama_lengkap'=>'Umar Faruq Hamdani',        'tempat_lahir'=>'Bogor','tanggal_lahir'=>'2015-01-25','jenis_kelamin'=>'L','kode_unit'=>'MI','wali_idx'=>7],
            ['nisn'=>'0123456783','nik'=>'3201010900130004','no_kk'=>'3201010400130001','nama_lengkap'=>'Syifa Nur Auliya',          'tempat_lahir'=>'Ciawi','tanggal_lahir'=>'2016-06-14','jenis_kelamin'=>'P','kode_unit'=>'MI','wali_idx'=>8],
            ['nisn'=>'0123456784','nik'=>'3201011000130005','no_kk'=>'3201010500130001','nama_lengkap'=>'Bilal Rasyid Ibrahim',      'tempat_lahir'=>'Bogor','tanggal_lahir'=>'2014-12-03','jenis_kelamin'=>'L','kode_unit'=>'MI','wali_idx'=>9],
            ['nisn'=>'0123456785','nik'=>'3201011100140001','no_kk'=>'3201010600140001','nama_lengkap'=>'Zahra Amalia Putri',        'tempat_lahir'=>'Jakarta','tanggal_lahir'=>'2014-03-18','jenis_kelamin'=>'P','kode_unit'=>'MI','wali_idx'=>10],
            ['nisn'=>'0123456786','nik'=>'3201011200140002','no_kk'=>'3201010700140001','nama_lengkap'=>'Abdurrahman Azzam',         'tempat_lahir'=>'Bogor','tanggal_lahir'=>'2016-09-07','jenis_kelamin'=>'L','kode_unit'=>'MI','wali_idx'=>11],
            ['nisn'=>'0123456787','nik'=>'3201011300140003','no_kk'=>'3201010800140001','nama_lengkap'=>'Maryam Ummu Kultsum',       'tempat_lahir'=>'Ciawi','tanggal_lahir'=>'2015-11-22','jenis_kelamin'=>'P','kode_unit'=>'MI','wali_idx'=>12],
            ['nisn'=>'0123456788','nik'=>'3201011400140004','no_kk'=>'3201010900140001','nama_lengkap'=>'Ibrahim Khalilullah',       'tempat_lahir'=>'Bogor','tanggal_lahir'=>'2013-07-15','jenis_kelamin'=>'L','kode_unit'=>'MI','wali_idx'=>0],
            ['nisn'=>'0123456789','nik'=>'3201011500140005','no_kk'=>'3201011000140001','nama_lengkap'=>'Annisa Fitri Ramadhani',    'tempat_lahir'=>'Depok','tanggal_lahir'=>'2013-02-28','jenis_kelamin'=>'P','kode_unit'=>'MI','wali_idx'=>1],
            // ===== MTs (10 santri)
            ['nisn'=>'1023456780','nik'=>'3201010601100001','no_kk'=>'3201010100100001','nama_lengkap'=>'Zaid bin Tsabit Al-Hifzi',  'tempat_lahir'=>'Bogor','tanggal_lahir'=>'2011-05-10','jenis_kelamin'=>'L','kode_unit'=>'MTS','wali_idx'=>2],
            ['nisn'=>'1023456781','nik'=>'3201010701100002','no_kk'=>'3201010200100001','nama_lengkap'=>'Hafsah Ummu Mukminin',      'tempat_lahir'=>'Ciawi','tanggal_lahir'=>'2012-08-25','jenis_kelamin'=>'P','kode_unit'=>'MTS','wali_idx'=>3],
            ['nisn'=>'1023456782','nik'=>'3201010801100003','no_kk'=>'3201010300100001','nama_lengkap'=>'Muadz bin Jabal',           'tempat_lahir'=>'Bogor','tanggal_lahir'=>'2010-03-17','jenis_kelamin'=>'L','kode_unit'=>'MTS','wali_idx'=>4],
            ['nisn'=>'1023456783','nik'=>'3201010901100004','no_kk'=>'3201010400100001','nama_lengkap'=>'Asma binti Abu Bakar',      'tempat_lahir'=>'Sukabumi','tanggal_lahir'=>'2011-11-30','jenis_kelamin'=>'P','kode_unit'=>'MTS','wali_idx'=>5],
            ['nisn'=>'1023456784','nik'=>'3201011001100005','no_kk'=>'3201010500100001','nama_lengkap'=>'Salman Al-Farisi Santoso',  'tempat_lahir'=>'Bogor','tanggal_lahir'=>'2012-01-04','jenis_kelamin'=>'L','kode_unit'=>'MTS','wali_idx'=>6],
            ['nisn'=>'1023456785','nik'=>'3201011101110001','no_kk'=>'3201010600110001','nama_lengkap'=>'Fatimah Az-Zahra Budiman',  'tempat_lahir'=>'Ciawi','tanggal_lahir'=>'2010-09-12','jenis_kelamin'=>'P','kode_unit'=>'MTS','wali_idx'=>7],
            ['nisn'=>'1023456786','nik'=>'3201011201110002','no_kk'=>'3201010700110001','nama_lengkap'=>'Khalid bin Walid Purnama',  'tempat_lahir'=>'Bogor','tanggal_lahir'=>'2011-04-20','jenis_kelamin'=>'L','kode_unit'=>'MTS','wali_idx'=>8],
            ['nisn'=>'1023456787','nik'=>'3201011301110003','no_kk'=>'3201010800110001','nama_lengkap'=>'Rufaida Al-Aslamiyah',      'tempat_lahir'=>'Jakarta','tanggal_lahir'=>'2012-07-08','jenis_kelamin'=>'P','kode_unit'=>'MTS','wali_idx'=>9],
            ['nisn'=>'1023456788','nik'=>'3201011401110004','no_kk'=>'3201010900110001','nama_lengkap'=>'Jabir bin Hayyan Firmansyah','tempat_lahir'=>'Bogor','tanggal_lahir'=>'2010-12-25','jenis_kelamin'=>'L','kode_unit'=>'MTS','wali_idx'=>10],
            ['nisn'=>'1023456789','nik'=>'3201011501110005','no_kk'=>'3201011000110001','nama_lengkap'=>'Ummu Salamah Andriani',     'tempat_lahir'=>'Depok','tanggal_lahir'=>'2011-06-15','jenis_kelamin'=>'P','kode_unit'=>'MTS','wali_idx'=>11],
            // ===== ULYA (5 santri)
            ['nisn'=>'2023456780','nik'=>'3201010600080001','no_kk'=>'3201010100080001','nama_lengkap'=>'Ali bin Abi Thalib Nugraha', 'tempat_lahir'=>'Bogor','tanggal_lahir'=>'2007-02-14','jenis_kelamin'=>'L','kode_unit'=>'ULYA','wali_idx'=>12],
            ['nisn'=>'2023456781','nik'=>'3201010700080002','no_kk'=>'3201010200080001','nama_lengkap'=>'Khadijah Khuwailid Wulandari','tempat_lahir'=>'Ciawi','tanggal_lahir'=>'2008-05-30','jenis_kelamin'=>'P','kode_unit'=>'ULYA','wali_idx'=>13],
            ['nisn'=>'2023456782','nik'=>'3201010800080003','no_kk'=>'3201010300080001','nama_lengkap'=>'Hasan bin Ali Kurniawan',    'tempat_lahir'=>'Bogor','tanggal_lahir'=>'2007-10-12','jenis_kelamin'=>'L','kode_unit'=>'ULYA','wali_idx'=>14],
            ['nisn'=>'2023456783','nik'=>'3201010900080004','no_kk'=>'3201010400080001','nama_lengkap'=>'Subaidah binti Zubair',      'tempat_lahir'=>'Jakarta','tanggal_lahir'=>'2008-08-08','jenis_kelamin'=>'P','kode_unit'=>'ULYA','wali_idx'=>0],
            ['nisn'=>'2023456784','nik'=>'3201011000080005','no_kk'=>'3201010500080001','nama_lengkap'=>'Husain bin Ali Prasetyo',    'tempat_lahir'=>'Sukabumi','tanggal_lahir'=>'2007-06-20','jenis_kelamin'=>'L','kode_unit'=>'ULYA','wali_idx'=>1],
        ];

        foreach ($students as $s) {
            if (Student::where('nik', $s['nik'])->exists()) continue;

            $levelId = $levelMap[$s['kode_unit']] ?? null;
            $waliId  = $waliIds[$s['wali_idx']] ?? null;

            Student::create([
                'nisn'             => $s['nisn'],
                'nik'              => $s['nik'],
                'no_kk'            => $s['no_kk'],
                'nama_lengkap'     => $s['nama_lengkap'],
                'tempat_lahir'     => $s['tempat_lahir'],
                'tanggal_lahir'    => $s['tanggal_lahir'],
                'jenis_kelamin'    => $s['jenis_kelamin'],
                'current_level_id' => $levelId,
                'wali_id'          => $waliId,
                'status_aktif'     => 'Aktif',
            ]);
        }

        $this->command->info('✅ Data Santri berhasil di-seed (30 santri di 4 unit pendidikan).');
    }
}
