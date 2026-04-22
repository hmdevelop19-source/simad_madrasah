<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;

/**
 * Model User (Akun Login)
 *
 * Mewakili "SIAPA yang bisa login" ke sistem.
 * Bukan data profil manusia — itu ada di Teacher, WaliSantri, dll.
 * User hanya menyimpan credential (email, password) dan HAK AKSES (role).
 *
 * Role yang tersedia (Spatie Permission):
 * - 'super_admin'   : Akses penuh seluruh sistem
 * - 'kepala_sekolah': Read-only dashboard unit yang dipimpinnya
 * - 'guru'          : Input presensi & nilai untuk mapel yang diampu
 * - 'wali_kelas'    : Hak guru + validasi nilai & generate e-raport
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * Kolom yang boleh diisi massal.
     * Ditambahkan 'role' dan 'education_level_id' dari PRD.
     * 'role' menentukan hak akses user.
     * 'education_level_id' membatasi scope data yang bisa diakses user
     * (NULL = akses semua unit, seperti Super Admin).
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'education_level_id',
    ];

    /**
     * Kolom yang disembunyikan saat model dikonversi ke JSON/array.
     * Penting: password dan token tidak boleh terekspos ke response API.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data otomatis.
     * 'password' di-cast ke 'hashed' → Laravel otomatis hash saat disimpan.
     * 'email_verified_at' di-cast ke 'datetime' → jadi object Carbon.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    // =========================================================================
    // HELPER METHOD
    // =========================================================================

    /**
     * Cek apakah user adalah Super Admin.
     * Digunakan di middleware dan view untuk kontrol akses.
     * Contoh penggunaan: if ($user->isSuperAdmin()) { ... }
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    /**
     * Cek apakah user adalah Guru atau Wali Kelas.
     * Wali Kelas memiliki semua hak Guru, jadi pengecekan ini menggabungkan keduanya.
     */
    public function isGuru(): bool
    {
        return $this->hasAnyRole(['guru', 'wali_kelas']);
    }

    // =========================================================================
    // RELASI ELOQUENT
    // =========================================================================

    /**
     * Relasi: User ini ditugaskan/bernaung di unit pendidikan mana?
     * Tipe: BelongsTo — User dimiliki (ber-FK ke) satu EducationLevel.
     * Jika NULL, user adalah Super Admin (akses semua unit).
     */
    public function educationLevel(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class, 'education_level_id');
    }

    /**
     * Relasi: Apakah user ini terhubung dengan data profil Guru?
     * Tipe: HasOne — Satu User PALING BANYAK punya satu data Teacher.
     * Jika NULL, user ini bukan guru (misal: admin tanpa mengajar).
     */
    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }
}
