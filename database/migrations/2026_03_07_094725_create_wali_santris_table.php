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
        Schema::create('wali_santri', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel users untuk login aplikasi Mobile (Flutter) nanti.
            // Dibuat nullable karena saat didaftarkan admin, wali mungkin belum punya akun aplikasi.
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->string('nik', 16)->unique();
            $table->string('nama_lengkap');
            $table->enum('hubungan_keluarga', ['Ayah', 'Ibu', 'Kakek', 'Nenek', 'Paman', 'Bibi', 'Wali Lainnya']);
            $table->enum('pendidikan_terakhir', ['SD/MI', 'SMP/MTs', 'SMA/MA', 'D3', 'S1', 'S2', 'S3', 'Lainnya'])->nullable();
            $table->string('pekerjaan')->nullable();
            $table->enum('penghasilan_bulanan', ['< 1 Juta', '1-3 Juta', '3-5 Juta', '5-10 Juta', '> 10 Juta'])->nullable();
            $table->string('no_whatsapp')->unique(); // Penting untuk OTP / Notif
            $table->text('alamat_lengkap');
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes(); // Wajib agar data tidak hilang permanen
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wali_santris');
    }
};
