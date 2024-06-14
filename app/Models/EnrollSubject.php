<?php

namespace App\Models;

use App\Models\Enroll;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EnrollSubject extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the enroll that owns the EnrollSubject
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enroll(): BelongsTo
    {
        return $this->belongsTo(Enroll::class, 'enroll_id', 'id');
    }

    /**
     * Get the subject that owns the EnrollSubject
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }
}
