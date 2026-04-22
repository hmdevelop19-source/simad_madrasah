<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Institution Group
            ['key' => 'institution.name',    'value' => 'Pondok Pesantren Al-Hikmah', 'group' => 'institution', 'type' => 'text'],
            ['key' => 'institution.head',    'value' => 'K.H. Ahmad Dahlan',         'group' => 'institution', 'type' => 'text'],
            ['key' => 'institution.address', 'value' => 'Jl. Raya Pesantren No. 123, Sukabumi, Jawa Barat', 'group' => 'institution', 'type' => 'text'],
            ['key' => 'institution.logo',    'value' => null,                        'group' => 'institution', 'type' => 'file'],
            
            // App Group
            ['key' => 'app.name',       'value' => 'SIMAD Terpadu',    'group' => 'app', 'type' => 'text'],
            ['key' => 'app.short_name', 'value' => 'SIMAD',            'group' => 'app', 'type' => 'text'],
            ['key' => 'app.version',    'value' => 'Classic v3.0',     'group' => 'app', 'type' => 'text'],
            ['key' => 'app.favicon',    'value' => null,               'group' => 'app', 'type' => 'file'],
            ['key' => 'app.footer',     'value' => 'SIMAD Terpadu. Al-Hikmah Premium v3.0', 'group' => 'app', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            \App\Models\SystemSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
