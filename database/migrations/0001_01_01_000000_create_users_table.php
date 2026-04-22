<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: create_users_table
 *
 * Tabel Login & RBAC. Dipisah dari data profil (Teacher, WaliSantri).
 *
 * Kolom penting:
 * - name  : Nama tampilan user
 * - email : Email untuk login (unique) — dipakai Laravel Auth default
 * - role  : Hak akses (enum). NULL tidak diizinkan, semua user punya role.
 * - education_level_id : Scope unit sekolah. NULL = Super Admin (akses global).
 * - softDeletes : Agar akun bisa dinonaktifkan tanpa benar-benar terhapus.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Kolom RBAC — menentukan hak akses user
            $table->enum('role', ['super_admin', 'kepala_sekolah', 'guru', 'wali_kelas']);

            // Scope unit pendidikan — NULL = Super Admin (akses semua unit)
            $table->foreignId('education_level_id')
                ->nullable()
                ->constrained('education_levels')
                ->onDelete('set null');

            $table->rememberToken();
            $table->softDeletes(); // Wajib untuk entitas manusia sesuai PRD
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
