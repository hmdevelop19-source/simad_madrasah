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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('curriculum_id')->constrained('curriculums')->cascadeOnDelete();
            $table->enum('jenis_nilai', ['Tugas', 'UTS', 'UAS', 'Praktik']);
            $table->decimal('nilai', 5, 2); // Mendukung nilai desimal seperti 85.50
            $table->text('catatan_guru')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
