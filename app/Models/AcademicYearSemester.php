<?php

namespace App\Models;

use App\Models\Year;
use App\Models\Semester;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicYearSemester extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get all of the years for the AcademicYearSemester
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function years(): HasMany
    {
        return $this->hasMany(Year::class, 'year_id', 'id');
    }

    /**
     * Get all of the semesters for the AcademicYearSemester
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function semesters(): HasMany
    {
        return $this->hasMany(Semester::class, 'semester_id', 'id');
    }
}
