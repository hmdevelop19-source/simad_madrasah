<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicTerm extends Model
{
    public const PERIODE_KUARTAL = ['Kuartal 1', 'Kuartal 2', 'Kuartal 3', 'Kuartal 4'];

    protected $fillable = [
        'academic_year_id',
        'nama',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
