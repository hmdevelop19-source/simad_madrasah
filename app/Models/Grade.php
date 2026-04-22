<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Grade (Penilaian / Nilai Santri)
 *
 * Mencatat satu nilai yang diterima santri untuk satu mata pelajaran
 * dalam konteks kurikulum tertentu (tahun ajaran + tingkat kelas + mapel).
 *
 * Menggunakan 'curriculum_id' sebagai FK utama (bukan langsung subject_id),
 * karena ini memungkinkan satu nilai terikat ke konteks yang sangat spesifik:
 * "Nilai Fiqih Kelas 7 MTs Tahun 2025/2026 dengan KKM 70".
 *
 * Jenis Nilai ('jenis_nilai') Enum:
 * - 'UH'     : Ulangan Harian
 * - 'UTS'    : Ujian Tengah Semester
 * - 'UAS'    : Ujian Akhir Semester
 * - 'Tugas'  : Nilai Tugas
 * - 'Akhir'  : Nilai Akhir (rata-rata akhir yang masuk raport)
 */
class Grade extends Model
{
    public const JENIS_NILAI = ['UH', 'UTS', 'UAS', 'Tugas', 'Akhir'];

    protected $fillable = [
        'student_id',
        'curriculum_id',
        'academic_term_id',
        'jenis_nilai',
        'nilai',
        'catatan_guru',
    ];

    protected $casts = [
        'nilai' => 'float',
    ];

    // =========================================================================
    // RELASI ELOQUENT
    // =========================================================================

    /**
     * Relasi: Nilai ini milik santri siapa?
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(AcademicTerm::class, 'academic_term_id');
    }

    // Scopes
    public function scopeByCurriculum($q, $curriculumId) { return $q->where('curriculum_id', $curriculumId); }
    public function scopeByStudent($q, $studentId)      { return $q->where('student_id', $studentId); }
    public function scopeByJenis($q, $jenis)            { return $q->where('jenis_nilai', $jenis); }
}
