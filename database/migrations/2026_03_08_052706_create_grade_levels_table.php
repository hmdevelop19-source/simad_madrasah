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
        Schema::create('grade_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('education_level_id')->constrained('education_levels')->onDelete('cascade'); // Hubungan ke MTs/Ulya
            $table->string('nama_tingkat'); // Contoh: 'Kelas 7' atau '10 Ulya Keagamaan'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_levels');
    }
};
