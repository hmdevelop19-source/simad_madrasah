<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL: alter enum column
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE grades MODIFY COLUMN jenis_nilai ENUM('UH','UTS','UAS','Tugas','Akhir') NOT NULL");
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE grades MODIFY COLUMN jenis_nilai ENUM('Tugas','UTS','UAS','Praktik') NOT NULL");
        }
    }
};

