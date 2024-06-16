<?php

namespace App\Models;

use App\Models\Subject;
use App\Models\Instructor;
use App\Models\AcademicYearSemester;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $cast = [
        'days' => 'array'
    ];

    /**
     * Get the academic_year_semester that owns the Schedule
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function academic_year_semester(): BelongsTo
    {
        return $this->belongsTo(AcademicYearSemester::class, 'academic_year_semester_id', 'id');
    }

    /**
     * Get the subject that owns the Schedule
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    /**
     * Get the instructor that owns the Schedule
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class, 'instructor_id', 'id');
    }

    public function getDaysAttribute($value)
    {
        return explode(',', $value);
    }

    public function setDaysAttribute($value)
    {
        $this->attributes['days'] = implode(',', $value);
    }
}
