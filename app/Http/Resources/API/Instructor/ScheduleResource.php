<?php

namespace App\Http\Resources\API\Instructor;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sched_code' => $this->sched_code,
            'start' => Carbon::parse($this->start)->format('h:i A'),
            'end' => Carbon::parse($this->end)->format('h:i A'),
            'days' => $this->days,
            'school_year' => $this->year->name,
            'semester' => $this->semester->name,
            'subject' => $this->subject->name,
            'subject_description' => $this->subject->description,
            'instructor' => $this->instructor->full_name,
            'instructor_id' => $this->instructor->instructor_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'attendances' => $this->attendances,
            'enrolled_students' => $this->students,
        ];
    }
}
