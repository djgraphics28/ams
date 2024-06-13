<?php

namespace App\Models;

use App\Models\AcademicYearSemester;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * Get the academic_year_semester that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function academic_year_semester(): BelongsTo
    {
        return $this->belongsTo(AcademicYearSemester::class, 'academic_year_semester_id', 'id');
    }
}
