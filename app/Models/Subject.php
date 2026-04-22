<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Subject (Mata Pelajaran Master)
 *
 * Merepresentasikan daftar GLOBAL mata pelajaran yang tersedia.
 * Ini adalah "katalog" mapel — data referensi yang stabil.
 * Contoh: "Fiqih", "Bahasa Arab", "Matematika".
 *
 * Subject TIDAK secara langsung terikat ke tahun ajaran.
 * Keterkaitan ke tahun ajaran & KKM dikelola melalui model Curriculum.
 */
class Subject extends Model
{
    /**
     * Kolom yang boleh diisi massal.
     * 'kode_mapel' adalah singkatan unik tiap mapel (ex: 'BHS-AR', 'MTK').
     */
    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
    ];

    // =========================================================================
    // RELASI ELOQUENT
    // =========================================================================

    /**
     * Relasi: Di kurikulum mana saja mata pelajaran ini dipakai?
     * Tipe: HasMany — Satu Subject bisa muncul di BANYAK entri Curriculum
     * (di berbagai tahun ajaran & tingkat kelas yang berbeda).
     */
    public function curriculums(): HasMany
    {
        return $this->hasMany(Curriculum::class);
    }
}
