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
        Schema::create('personality_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('grade_level_id')->constrained()->cascadeOnDelete();
            $table->string('aspek'); // Kelakuan, Kerajinan, Kebersihan
            $table->string('predikat')->nullable(); // Sangat Baik / Baik / Cukup / Kurang, dll
            
            // Satu santri hanya punya satu nilai per aspek per tahun ajaran
            $table->unique(['student_id', 'academic_year_id', 'aspek'], 'personality_unique');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personality_grades');
    }
};
