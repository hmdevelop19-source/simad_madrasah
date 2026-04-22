<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Classroom (Ruang Kelas Fisik)
 *
 * Merepresentasikan ruang/rombongan belajar yang nyata.
 * Contoh: "7-A", "8-B", "MA-Al Farabi".
 * Satu Classroom dipegang oleh satu Wali Kelas (yang juga seorang Guru).
 *
 * Hierarki: EducationLevel → GradeLevel → Classroom
 */
class Classroom extends Model
{
    use \App\Traits\BelongsToEducationLevel;
    /**
     * Kolom yang boleh diisi massal.
     * 'wali_kelas_id' adalah FK ke tabel teachers — menunjuk Guru yang jadi Wali Kelas.
     */
    protected $fillable = [
        'grade_level_id',
        'nama_kelas',
        'wali_kelas_id',
    ];

    // =========================================================================
    // RELASI ELOQUENT
    // =========================================================================

    /**
     * Relasi: Kelas ini berada di tingkat kelas mana?
     * Tipe: BelongsTo — Classroom dimiliki (ber-FK ke) satu GradeLevel.
     * Contoh: Kelas "7-A" berada di GradeLevel "Kelas 7".
     */
    public function gradeLevel(): BelongsTo
    {
        return $this->belongsTo(GradeLevel::class);
    }

    /**
     * Relasi: Siapa Wali Kelas dari kelas ini?
     * Tipe: BelongsTo — Classroom dimiliki (ber-FK ke) satu Teacher.
     * FK-nya adalah 'wali_kelas_id', bukan 'teacher_id' (nama FK berbeda!).
     */
    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'wali_kelas_id');
    }

    /**
     * Relasi: Riwayat santri mana saja yang pernah berada di kelas ini?
     * Tipe: HasMany — Satu Classroom memiliki BANYAK riwayat kelas santri.
     */
    public function studentHistories(): HasMany
    {
        return $this->hasMany(StudentHistory::class);
    }

    /**
     * Relasi: Guru apa saja yang pernah mengajar di kelas ini?
     * Tipe: HasMany — Satu Classroom memiliki BANYAK penugasan guru.
     */
    public function teacherAssignments(): HasMany
    {
        return $this->hasMany(TeacherAssignment::class);
    }

    /**
     * Relasi: Data presensi dari kelas ini.
     * Tipe: HasMany — Satu Classroom memiliki BANYAK catatan presensi.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
