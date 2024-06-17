<?php

namespace App\Http\Resources\API\Instructor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
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
            'time_in' => $this->time_in,
            'scanned_by' => $this->instructor->full_name,
            'student' => $this->student->full_name,
            'course' => $this->student->course->name,
            'subject' => $this->schedule->subject->name,
            'description' => $this->schedule->subject->name,
            'schedule' => $this->schedule->sched_code,
        ];
    }
}
