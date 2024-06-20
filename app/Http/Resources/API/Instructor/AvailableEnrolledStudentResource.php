<?php

namespace App\Http\Resources\API\Instructor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvailableEnrolledStudentResource extends JsonResource
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
            'student_number' => $this->student->student_number,
            'full_name' => $this->student->full_name, // Use the converted time_
            'email' => $this->student->email ?? null,
            'course' => $this->student->course->name ?? null,
            'image' => config('app.url').'/storage/'.$this->student->image,
        ];
    }
}
