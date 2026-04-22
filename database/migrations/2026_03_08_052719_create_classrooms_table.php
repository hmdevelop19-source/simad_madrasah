<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: create_classrooms_table
 *
 * Tabel 'classrooms' menyimpan ruang kelas fisik / rombongan belajar.
 * Contoh: '7-A', '8-B', 'MA Kelas 10-C'.
 *
 * Catatan: Nama tabel disamakan menjadi 'classrooms' (bukan 'classes')
 * agar konsisten dengan nama Model Classroom dan konvensi Laravel.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_level_id')->constrained('grade_levels')->onDelete('cascade');
            $table->foreignId('wali_kelas_id')->nullable()->constrained('teachers')->onDelete('set null');
            $table->string('nama_kelas'); // Contoh: '7-A'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
