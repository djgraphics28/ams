<?php

namespace App\Models;

use App\Models\Schedule;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use App\Models\AcademicYearSemester;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    use HasFactory, SoftDeletes;
    use HasApiTokens, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the academic_year_semester that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function academic_year_semester(): BelongsTo
    {
        return $this->belongsTo(AcademicYearSemester::class, 'academic_year_semester_id', 'id')->where('is_active', true);
    }

    /**
     * Get all of the schedules for the Instructor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'student_id', 'id');
    }

    // Define mutator for password attribute
    public function setPasswordAttribute($value)
    {
        if (empty($value)) {
            // If no new password is provided, generate a password from the last name
            $password = empty($this->password) ? Str::slug($this->last_name, '') : $this->password;
        } else {
            // Use the provided value as the new password
            $password = $value;
        }

        $this->attributes['password'] = Hash::make($password);
    }

}
