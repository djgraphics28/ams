<?php

namespace App\Models;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    /**
     * Get all of the subjects for the Course
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'course_id', 'id');
    }
}
