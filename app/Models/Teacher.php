<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Teacher (Guru)
 *
 * Mewakili data PROFIL guru sebagai entitas akademik.
 * Dipisah dari model User karena konsep User adalah "akun login",
 * sedangkan Teacher adalah "data profil akademik guru".
 * Seorang Guru BISA PUNYA akun User untuk login, tapi tidak harus.
 *
 * Menggunakan SoftDelete sesuai ketentuan PRD untuk tabel entitas manusia.
 */
class Teacher extends Model
{
    use SoftDeletes, \App\Traits\BelongsToEducationLevel;

    /**
     * Kolom yang boleh diisi massal.
     */
    protected $fillable = [
        'user_id',
        'education_level_id',
        'nip',
        'nama_lengkap',
        'email',
        'no_hp',
        'alamat',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // =========================================================================
    // RELASI ELOQUENT
    // =========================================================================

    /**
     * Relasi: Guru ini login dengan akun siapa?
     * Tipe: BelongsTo — Teacher dimiliki (ber-FK ke) satu User.
     * Nullable: Guru bisa ada meski belum punya akun login.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Di kelas/mapel mana saja guru ini pernah ditugaskan?
     * Tipe: HasMany — Satu Guru bisa punya BANYAK penugasan mengajar
     * di berbagai kelas & tahun ajaran yang berbeda.
     * Ini adalah riwayat mengajar guru dari tahun ke tahun.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(TeacherAssignment::class);
    }
}
