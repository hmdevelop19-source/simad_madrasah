<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model StudentHistory (Riwayat Kelas Santri)
 *
 * Ini adalah implementasi langsung dari prinsip "Historical Integrity" PRD.
 * Setiap kali santri naik kelas atau ganti tahun ajaran, sebuah baris BARU
 * ditambahkan ke tabel ini — tidak ada UPDATE pada baris lama.
 *
 * Satu baris StudentHistory menjawab pertanyaan:
 * "Santri [X] di Tahun Ajaran [Y] berada di Kelas [Z], dengan status kenaikan [W]"
 *
 * 'status_kenaikan' mencatat hasil akhir santri di periode tersebut:
 * - 'Naik'    : Naik ke kelas berikutnya
 * - 'Tinggal' : Tinggal kelas / tidak naik
 * - 'Lulus'   : Lulus dari tingkat pendidikan ini
 * - 'Mutasi'  : Pindah sekolah/tingkat
 */
class StudentHistory extends Model
{
    /**
     * Kolom yang boleh diisi massal.
     */
    protected $fillable = [
        'student_id',
        'academic_year_id',
        'class_id',
        'status_kenaikan',
    ];

    // =========================================================================
    // RELASI ELOQUENT
    // =========================================================================

    /**
     * Relasi: Riwayat ini milik santri siapa?
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relasi: Riwayat ini terjadi di tahun ajaran mana?
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Relasi: Di riwayat ini, santri berada di kelas mana?
     * 'class_id' adalah FK ke tabel 'classrooms'.
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }
}
