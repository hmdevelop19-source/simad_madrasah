<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model AcademicYear (Tahun Ajaran)
 *
 * Merepresentasikan satu periode tahun ajaran.
 * Mendukung DUA sistem penamaan periode:
 *   - Semester : 'Ganjil', 'Genap'    → untuk madrasah sistem 2 semester
 *   - Kuartal  : 'Kuartal 1' s/d '4' → untuk madrasah sistem 4 kuartal
 *
 * Hanya SATU tahun ajaran yang boleh is_active = true. Ditegakkan di Controller.
 */
class AcademicYear extends Model
{
    protected $fillable = [
        'nama',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // =========================================================================
    // RELASI ELOQUENT
    // =========================================================================

    /**
     * Relasi ke Kuartal/Term dalam satu tahun ajaran.
     */
    public function terms(): HasMany
    {
        return $this->hasMany(AcademicTerm::class);
    }

    /**
     * Mendapatkan Term/Kuartal yang aktif saat ini di tahun ini.
     */
    public function activeTerm()
    {
        return $this->terms()->where('is_active', true)->first();
    }

    // =========================================================================
    // RELASI ELOQUENT
    // =========================================================================

    public function curriculums(): HasMany
    {
        return $this->hasMany(Curriculum::class);
    }

    public function studentHistories(): HasMany
    {
        return $this->hasMany(StudentHistory::class);
    }

    public function teacherAssignments(): HasMany
    {
        return $this->hasMany(TeacherAssignment::class);
    }
}
