<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: create_academic_years_table
 *
 * Menyimpan periode tahun ajaran.
 * Hanya SATU record yang boleh is_active = true.
 *
 * Kolom:
 * - nama   : Format "2025/2026"
 * - periode: Enum dinamis — mendukung Semester (Ganjil/Genap) dan Kuartal (1-4)
 * - is_active: Boolean, true = tahun ajaran sedang berjalan
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('nama');                          // Contoh: '2025/2026'
            $table->enum('periode', [                        // Dinamis: Semester ATAU Kuartal
                'Ganjil',                                   // Semester 1
                'Genap',                                    // Semester 2
                'Kuartal 1',                                // Q1
                'Kuartal 2',                                // Q2
                'Kuartal 3',                                // Q3
                'Kuartal 4',                                // Q4
            ])->default('Ganjil');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};
