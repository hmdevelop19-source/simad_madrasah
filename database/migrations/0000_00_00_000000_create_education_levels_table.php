<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: create_education_levels_table
 *
 * Pertama kali dijalankan (timestamp 0000) karena semua tabel lain
 * bergantung (FK) ke tabel ini.
 *
 * Kolom:
 * - kode : Kode singkat unit (ex: 'MTS', 'ULYA') — unique
 * - nama : Nama lengkap unit (ex: 'Madrasah Tsanawiyah')
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('education_levels', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique(); // misal: 'TK', 'MI', 'MTS', 'ULYA'
            $table->string('nama');               // misal: 'Madrasah Tsanawiyah'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('education_levels');
    }
};
