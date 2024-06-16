<?php

namespace App\Models;

use App\Models\Schedule;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Instructor extends Authenticatable
{
    use HasFactory;
    use HasApiTokens, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get all of the schedules for the Instructor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'instructor_id', 'id');
    }

    public function setPasswordAttribute($value)
    {
        $password = '123456';
        if ($this->password == '') {
            $password = Str::slug($this->last_name, '');
        } else {
            $password = $this->password;
        }

        $this->attributes['password'] = Hash::make($password);
    }
}
