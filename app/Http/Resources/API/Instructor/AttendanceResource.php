<?php

namespace App\Http\Resources\API\Instructor;

use Carbon\Carbon;
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
        $time_in = $this->time_in ? Carbon::parse($this->time_in)->timezone('Asia/Manila') : null;
        $start = Carbon::parse($this->schedule->start)->timezone('Asia/Manila');

        // Adjusting for the same day comparison
        $startToday = Carbon::parse($start)->setDateFrom($time_in);

        return [
            'id' => $this->id,
            'time_in' => $time_in ? $time_in->format('Y-m-d H:i:s') : null,
            'scanned_by' => $this->schedule->instructor->full_name ?? null,
            'schedule' => $this->schedule->sched_code ?? null,
            'student_name' => $this->student->full_name ?? null,
            'student_number' => $this->student->student_number ?? null,
            'image' => $this->student->image ? config('app.url').'/storage/'.$this->student->image : '',
            'course' => $this->student->course->name ?? null,
            'email' => $this->student->email ?? null,
            'start' => $start->format('h:i A'),
            'end' => Carbon::parse($this->schedule->end)->timezone('Asia/Manila')->format('h:i A') ?? null,
            'is_late' => $time_in ? $time_in->greaterThan($startToday) : false,
            'created_at' => $this->created_at ? Carbon::parse($this->created_at)->timezone('Asia/Manila')->format('Y-m-d') : null,
            'updated_at' => $this->updated_at ? Carbon::parse($this->updated_at)->timezone('Asia/Manila')->format('Y-m-d') : null,
        ];
    }


}
