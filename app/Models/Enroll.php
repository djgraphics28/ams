<?php

namespace App\Models;

use App\Models\Student;
use App\Models\EnrollSubject;
use App\Models\AcademicYearSemester;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enroll extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'year_level',
        'course_id',
        'block',
        'student_id',
    ];

    /**
     * Get all of the enrolled_subjects for the Enroll
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enrolled_subjects(): HasMany
    {
        return $this->hasMany(EnrollSubject::class, 'enroll_id', 'id');
    }

    /**
     * Get the student that owns the Enroll
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    /**
     * Get the academic_year_semester that owns the Enroll
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function academic_year_semester(): BelongsTo
    {
        return $this->belongsTo(AcademicYearSemester::class, 'academic_year_semester_id', 'id');
    }

    /**
     * Get the course that owns the Enroll
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    /**
     * Get the semester that owns the Enroll
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'id');
    }

    /**
     * Get the year that owns the Enroll
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function year(): BelongsTo
    {
        return $this->belongsTo(Year::class, 'year_id', 'id');
    }

    public function getLevelAttribute()
    {
        $levels = [
            1 => 'FIRST YEAR',
            2 => 'SECOND YEAR',
            3 => 'THIRD YEAR',
            4 => 'FOURTH YEAR',
        ];

        return $levels[$this->year_level] ?? 'UNKNOWN YEAR LEVEL';
    }

    public function getSectionAttribute()
    {
        $blocks = [
            'A' => 'Block A',
            'B' => 'Block B',
            'C' => 'Block C',
            'D' => 'Block D',
            'E' => 'Block E',
            'F' => 'Block F',
            'G' => 'Block G',
            'H' => 'Block H',
            'I' => 'Block I',
            'J' => 'Block J',
        ];

        return $blocks[$this->block] ?? 'UNKNOWN BLOCK';
    }

}
