<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Academic Years - Hapus periode
        Schema::table('academic_years', function (Blueprint $table) {
            if (Schema::hasColumn('academic_years', 'periode')) {
                $table->dropColumn('periode');
            }
        });

        // 2. Grades - Tambah academic_term_id
        Schema::table('grades', function (Blueprint $table) {
            $table->foreignId('academic_term_id')->nullable()->after('student_id')->constrained('academic_terms')->nullOnDelete();
        });

        // 3. Personality Grades - Tambah academic_term_id
        Schema::table('personality_grades', function (Blueprint $table) {
            $table->foreignId('academic_term_id')->nullable()->after('student_id')->constrained('academic_terms')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('personality_grades', function (Blueprint $table) {
            $table->dropConstrainedForeignId('academic_term_id');
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->dropConstrainedForeignId('academic_term_id');
        });

        Schema::table('academic_years', function (Blueprint $table) {
            $table->enum('periode', ['Ganjil', 'Genap', 'Kuartal 1', 'Kuartal 2', 'Kuartal 3', 'Kuartal 4'])->default('Ganjil');
        });
    }
};
