<?php

namespace App\Models;

use App\Models\AcademicYearSemester;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Semester extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the academic_year_semeter that owns the Semester
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function academic_year_semeter(): BelongsTo
    {
        return $this->belongsTo(AcademicYearSemester::class, 'semester_id', 'id');
    }

    /**
     * Get all of the enrolls for the Semester
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enrolls(): HasMany
    {
        return $this->hasMany(Enroll::class, 'semester_id', 'id');
    }

    /**
     * Get all of the students for the Semester
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'semester_id', 'id');
    }
}
