<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model TeacherAssignment (Riwayat Penugasan Mengajar Guru)
 *
 * Merepresentasikan PENUGASAN seorang guru ke kelas dan mata pelajaran tertentu
 * dalam satu tahun ajaran. Ini sekaligus menjadi riwayat mengajar guru.
 *
 * Satu baris menjawab:
 * "Di Tahun Ajaran [Y], Guru [X] mengajar mapel [S] di Kelas [C]"
 *
 * 'is_wali_kelas' menandai apakah guru ini juga menjabat sebagai Wali Kelas
 * untuk kelas tersebut di tahun ajaran itu.
 * Catatan: Seorang Wali Kelas mendapat hak tambahan (validasi raport).
 */
class TeacherAssignment extends Model
{
    /**
     * Kolom yang boleh diisi massal.
     */
    protected $fillable = [
        'teacher_id',
        'academic_year_id',
        'class_id',
        'subject_id',
        'is_wali_kelas',
    ];

    /**
     * Casting tipe data.
     * 'is_wali_kelas' di-cast ke boolean untuk perbandingan langsung di kode.
     */
    protected $casts = [
        'is_wali_kelas' => 'boolean',
    ];

    // =========================================================================
    // RELASI ELOQUENT
    // =========================================================================

    /**
     * Relasi: Guru mana yang mendapat penugasan ini?
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Relasi: Penugasan ini berlaku di tahun ajaran mana?
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Relasi: Penugasan ini untuk kelas mana?
     * FK-nya adalah 'class_id' (bukan 'classroom_id').
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    /**
     * Relasi: Penugasan ini untuk mata pelajaran apa?
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
