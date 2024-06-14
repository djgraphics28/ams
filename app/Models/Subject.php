<?php

namespace App\Models;

use App\Models\Course;
use App\Models\EnrollSubject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'units', 'course_id'];

    /**
     * Get the course that owns the Subject
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    /**
     * Get all of the enrolled_subjects for the Subject
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enrolled_subjects(): HasMany
    {
        return $this->hasMany(EnrollSubject::class, 'subject_id', 'id');
    }

}
