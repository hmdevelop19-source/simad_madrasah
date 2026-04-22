<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Curriculum (Pemetaan Kurikulum — Pivot Utama)
 *
 * Ini adalah jantung dari "Dynamic Curriculum" yang disebutkan di PRD.
 * Curriculum merupakan tabel pivot yang menghubungkan:
 *   - AcademicYear (Tahun Ajaran mana?)
 *   - GradeLevel   (Untuk tingkat kelas berapa?)
 *   - Subject       (Mata pelajaran apa?)
 *   - kkm           (Berapa nilai KKM-nya di tahun ini?)
 *
 * Contoh satu baris data: "Di Tahun 2025/2026, Kelas 7 MTs, mapel Fiqih, KKM = 70"
 * Tahun berikutnya, baris baru dibuat lagi (tidak ditimpa), sehingga KKM
 * bisa berubah tanpa merusak data historis nilai.
 */
class Curriculum extends Model
{
    use \App\Traits\BelongsToEducationLevel;
    /**
     * Override nama tabel — Laravel default pluralize "Curriculum" → "curricula"
     * padahal migration menggunakan nama "curriculums".
     */
    protected $table = 'curriculums';

    /**
     * Kolom yang boleh diisi massal.
     * 'kkm' adalah Kriteria Ketuntasan Minimal — ambang batas nilai lulus.
     */
    protected $fillable = [
        'academic_year_id',
        'grade_level_id',
        'subject_id',
        'kkm',
    ];

    // =========================================================================
    // RELASI ELOQUENT
    // =========================================================================

    /**
     * Relasi: Entri kurikulum ini berlaku di tahun ajaran mana?
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Relasi: Entri kurikulum ini untuk tingkat kelas mana?
     */
    public function gradeLevel(): BelongsTo
    {
        return $this->belongsTo(GradeLevel::class);
    }

    /**
     * Relasi: Entri kurikulum ini untuk mata pelajaran apa?
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Relasi: Nilai-nilai apa saja yang diinput berdasarkan kurikulum ini?
     * Tipe: HasMany — Satu entri Curriculum bisa memiliki BANYAK entri nilai (Grade)
     * dari berbagai santri di kelas tersebut.
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Relasi: Guru mana yang diamanahkan mengajar mapel di kurikulum ini?
     * Tipe: HasMany — Satu Curriculum bisa diampu oleh satu atau lebih guru
     * (melalui tabel pivot teacher_assignments).
     */
    public function teacherAssignments(): HasMany
    {
        return $this->hasMany(TeacherAssignment::class);
    }
}
