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
        // Convert time_in to Asia/Manila timezone
        $time_in = $this->time_in ? $this->time_in->timezone('Asia/Manila')->format('Y-m-d H:i:s') : null;

        return [
            'id' => $this->id,
            'time_in' => $time_in, // Use the converted time_in value
            'scanned_by' => $this->instructor->full_name ?? null, // Use null coalescing for nested properties
            'student' => $this->student->full_name ?? null,
            'course' => $this->student->course->name ?? null,
            'subject' => $this->schedule->subject->name ?? null,
            'description' => $this->schedule->subject->description ?? null, // Assuming 'description' is correct, changed from name
            'schedule' => $this->schedule->sched_code ?? null,
        ];
    }
}
