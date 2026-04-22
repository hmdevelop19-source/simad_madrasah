<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait BelongsToEducationLevel
 *
 * Menambahkan Global Scope secara otomatis untuk memfilter data berdasarkan unit pendidikan (Education Level).
 * Filter ini dinamis: dicek setiap kali query dijalankan.
 */
trait BelongsToEducationLevel
{
    protected static function bootBelongsToEducationLevel()
    {
        static::addGlobalScope('education_level', function (Builder $builder) {
            // Hanya tambahkan filter jika ada user login (Context Web/API)
            if (auth()->check()) {
                $user = auth()->user();
                $model = $builder->getModel();
                $column = $model->getEducationLevelColumnName();

                // Reset scope: Jika Super Admin, biarkan tanpa filter.
                // Jika unit manager, tambahkan filter kolom unit + data global (NULL).
                if (!$user->isSuperAdmin() && !empty($user->education_level_id)) {
                    $builder->where(function ($query) use ($user, $column) {
                        $table = $query->getModel()->getTable();
                        $query->where($table . '.' . $column, $user->education_level_id)
                              ->orWhereNull($table . '.' . $column);
                    });
                }
            }
        });

        // AUTO-SET education_level_id saat membuat data baru
        static::creating(function ($model) {
            if (auth()->check()) {
                $user = auth()->user();
                $column = $model->getEducationLevelColumnName();
                
                if (!$user->isSuperAdmin() && !empty($user->education_level_id)) {
                    if (empty($model->{$column})) {
                        $model->{$column} = $user->education_level_id;
                    }
                }
            }
        });
    }

    /**
     * Mendapatkan nama kolom yang digunakan untuk filter unit.
     * Default: education_level_id. 
     * Bisa di-override di model dengan properti: protected $educationLevelColumn = '...';
     */
    public function getEducationLevelColumnName(): string
    {
        return property_exists($this, 'educationLevelColumn') ? $this->educationLevelColumn : 'education_level_id';
    }
}
