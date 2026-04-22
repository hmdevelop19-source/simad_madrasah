<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    public const STATUSES = ['Hadir', 'Sakit', 'Izin', 'Alpha'];

    protected $fillable = [
        'student_id',
        'class_id',
        'tanggal',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // =========================================================================
    // RELASI
    // =========================================================================

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    public function scopeByClass($q, $classId)   { return $q->where('class_id', $classId); }
    public function scopeByDate($q, $date)        { return $q->where('tanggal', $date); }
    public function scopeByMonth($q, $year, $m)   { return $q->whereYear('tanggal', $year)->whereMonth('tanggal',$m); }
    public function scopeByStudent($q, $studentId){ return $q->where('student_id', $studentId); }
}
