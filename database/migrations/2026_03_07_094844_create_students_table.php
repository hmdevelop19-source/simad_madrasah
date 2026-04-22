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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 10)->unique()->nullable();
            $table->string('no_kk', 16);
            $table->string('nik', 16)->unique();
            $table->string('nama_lengkap');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            
            // Posisi jenjang saat ini
            $table->foreignId('current_level_id')->constrained('education_levels');
            
            // Relasi ke tabel wali_santri (Pastikan migration wali_santri dibuat sebelum ini)
            $table->foreignId('wali_id')->constrained('wali_santri')->onDelete('restrict'); 
            // -> Di-comment dulu jika tabel wali belum dibuat
            
            $table->enum('status_aktif', ['Aktif', 'Cuti', 'Lulus', 'Mutasi', 'Keluar'])->default('Aktif');
            $table->timestamps();
            $table->softDeletes(); // Menyembunyikan data tanpa menghapus permanen
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
