<?php

namespace App\Http\Controllers\API;

use App\Models\Student;
use App\Models\Attendance;
use App\Models\Instructor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\API\Instructor\ScheduleResource;

class InstructorController extends Controller
{
    public function getSchedules(Request $request, $id)
    {
        // Retrieve the authenticated instructor
        // $instructor = Auth::guard('instructor')->user();

        // Eager load schedules and attendances
        $data = Instructor::with(['schedules'])
            ->find($id);

        // Check if data is found
        if (!$data) {
            return response()->json([
                'message' => 'Invalid Record!',
            ], 404);
        }

        // Return the data as JSON response
        return response()->json([
            'data' => ScheduleResource::collection($data->schedules)
        ]);
    }
    public function markAttendance(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required',
            'qr_code' => 'required|exists:students,qr_code',
        ]);

        $student = Student::where('qr_code', $request->qr_code)->first();

        if (!$student) {
            return response()->json([
                'message' => 'Invalid Student!',
            ], 404);
        }

        $instructor = Auth::guard('instructor')->user();

        $attendance = Attendance::create([
            'student_id' => $student->id,
            'schedule_id' => $request->id,
            'scanned_by' => $instructor->id,
            'time_in' => now(),
        ]);

        return response()->json([
            'message' => 'Attendance marked successfully',
            'attendance' => $attendance,
            'student' => [
                'student_number' => $student->student_number,
                'image' => $student->image,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
            ],
        ]);
    }
}
