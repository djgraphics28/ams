<?php

namespace App\Models;

use App\Models\AcademicYearSemester;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Year extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the academic_year_semester that owns the Year
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function academic_year_semester(): BelongsTo
    {
        return $this->belongsTo(AcademicYearSemester::class, 'year_id', 'id');
    }

    /**
     * Get all of the schedules for the Year
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'year_id', 'id');
    }

    /**
     * Get all of the students for the Year
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'year_id', 'id');
    }
}
