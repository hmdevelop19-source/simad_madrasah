<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EducationLevel extends Model
{
    protected $guarded = ['id'];

    // Relasi: MTs ini punya santri siapa saja?
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'current_level_id');
    }

    // Relasi: MTs ini punya tingkat kelas apa saja? (Kelas 7, 8, 9)
    public function gradeLevels(): HasMany
    {
        return $this->hasMany(GradeLevel::class);
    }
}
