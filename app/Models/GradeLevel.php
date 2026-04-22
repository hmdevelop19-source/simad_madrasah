<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model GradeLevel (Tingkat Kelas / Jurusan)
 *
 * Merepresentasikan tingkatan kelas dalam satu unit pendidikan.
 * Contoh: Unit MTs punya GradeLevel: "Kelas 7", "Kelas 8", "Kelas 9".
 * GradeLevel berada di antara EducationLevel dan Classroom dalam hierarki.
 *
 * Hierarki: EducationLevel → GradeLevel → Classroom
 */
class GradeLevel extends Model
{
    /**
     * Kolom yang boleh diisi massal.
     * 'education_level_id' adalah FK yang mengaitkan tingkat kelas ke unit sekolah.
     */
    protected $fillable = [
        'education_level_id',
        'nama_tingkat', // Contoh: 'Kelas 7', 'Kelas 8', 'Kelas 9'
    ];

    // =========================================================================
    // RELASI ELOQUENT
    // =========================================================================

    /**
     * Relasi: Tingkat kelas ini milik unit pendidikan mana?
     * Tipe: BelongsTo — GradeLevel dimiliki (ber-FK ke) satu EducationLevel.
     * Contoh: "Kelas 7" milik unit "MTs".
     */
    public function educationLevel(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class);
    }

    /**
     * Relasi: Ruang kelas fisik apa saja yang ada di tingkat kelas ini?
     * Tipe: HasMany — Satu GradeLevel memiliki BANYAK Classroom.
     * Contoh: "Kelas 7" memiliki ruang "7-A", "7-B", "7-C".
     */
    public function classrooms(): HasMany
    {
        return $this->hasMany(Classroom::class);
    }

    /**
     * Relasi: Kurikulum apa yang berlaku untuk tingkat kelas ini?
     * Tipe: HasMany — Satu GradeLevel memiliki BANYAK entri kurikulum
     * (bisa berbeda per tahun ajaran).
     */
    public function curriculums(): HasMany
    {
        return $this->hasMany(Curriculum::class);
    }
}
