<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model WaliSantri (Orang Tua / Wali Murid)
 *
 * Mewakili tabel 'wali_santri'. Satu wali bisa memiliki banyak santri/anak
 * lintas tingkatan (MTs, MA, dll). Bisa punya akun User untuk login mobile app.
 */
class WaliSantri extends Model
{
    use SoftDeletes;

    /**
     * Nama tabel di database.
     * Harus didefinisikan secara eksplisit karena Laravel secara default
     * akan menebak nama tabel menjadi 'wali_santris' (bentuk jamak otomatis).
     * Tabel kita namanya 'wali_santri' (tanpa 's').
     */
    protected $table = 'wali_santri';

    /**
     * Kolom-kolom yang boleh diisi secara massal (mass assignment).
     * Semua kolom dari migrasi didaftarkan di sini untuk keamanan.
     */
    protected $fillable = [
        'user_id',
        'nik',
        'nama_lengkap',
        'hubungan_keluarga',
        'pendidikan_terakhir',
        'pekerjaan',
        'penghasilan_bulanan',
        'no_whatsapp',
        'alamat_lengkap',
    ];

    // =========================================================================
    // RELASI ELOQUENT
    // =========================================================================

    /**
     * Relasi: Wali ini punya akun login mana? (Untuk Mobile App)
     * Tipe: BelongsTo — WaliSantri dimiliki oleh satu User (opsional).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Santri mana saja yang menjadi tanggungan wali ini?
     * Tipe: HasMany — Satu Wali bisa punya BANYAK Santri.
     * FK 'wali_id' ada di tabel 'students'.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'wali_id');
    }
}