<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Student (Santri / Siswa)
 *
 * Mewakili data STATIS seorang santri — informasi yang tidak berubah
 * sepanjang waktu (nama, NIK, tempat lahir, dll).
 * Data DINAMIS (kelas, tahun ajaran) disimpan di StudentHistory.
 *
 * Menggunakan SoftDelete: data tidak benar-benar dihapus dari DB,
 * hanya kolom 'deleted_at' diisi, sesuai prinsip Historical Integrity PRD.
 */
class Student extends Model
{
    use SoftDeletes, \App\Traits\BelongsToEducationLevel;

    protected $educationLevelColumn = 'current_level_id';

    /**
     * Kolom yang boleh diisi massal.
     * 'nisn' sengaja dimasukkan meskipun nullable, agar bisa diisi via form.
     */
    protected $fillable = [
        'nisn',
        'no_kk',
        'nik',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'current_level_id',
        'wali_id',
        'status_aktif',
    ];

    /**
     * Casting tipe data kolom.
     * 'tanggal_lahir' dicast ke object Carbon agar mudah diformat di view.
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // =========================================================================
    // RELASI ELOQUENT
    // =========================================================================

    /**
     * Relasi: Siapa wali/orang tua santri ini?
     * Tipe: BelongsTo — Santri dimiliki (ber-FK ke) satu WaliSantri.
     * FK-nya adalah 'wali_id' di tabel 'students'.
     */
    public function wali(): BelongsTo
    {
        return $this->belongsTo(WaliSantri::class, 'wali_id');
    }

    /**
     * Relasi: Santri ini berada di unit pendidikan mana saat ini?
     * Tipe: BelongsTo — Santri dimiliki (ber-FK ke) satu EducationLevel.
     * FK-nya adalah 'current_level_id'.
     */
    public function educationLevel(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class, 'current_level_id');
    }

    /**
     * Relasi: Apa saja riwayat kelas santri ini dari tahun ke tahun?
     * Tipe: HasMany — Satu Santri punya BANYAK catatan riwayat kelas.
     * Ini adalah inti dari prinsip "Historical Integrity" di PRD.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(StudentHistory::class);
    }

    /**
     * Relasi: Apa saja data presensi santri ini?
     * Tipe: HasMany — Satu Santri punya BANYAK catatan presensi.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Relasi: Apa saja nilai yang dimiliki santri ini?
     * Tipe: HasMany — Satu Santri punya BANYAK catatan nilai.
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }
}