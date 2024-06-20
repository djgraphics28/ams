<?php

namespace App\Http\Resources\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentScheduleResource extends JsonResource
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
            'academic_year_semester' => $this->academic_year_semester->name,
            'subject' => $this->subject->name,
            'subject_description' => $this->subject->description,
            'instructor' => $this->instructor->full_name,
        ];
    }
}
