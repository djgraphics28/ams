<?php

namespace App\Http\Controllers\API;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InstructorController extends Controller
{
    public function markAttendance(Request $request)
    {
        $request->validate([
            'student_number' => 'required|exists:students,student_number',
            'qr_code' => 'required|exists:students,qr_code',
        ]);

        $student = Student::where('student_number', $request->student_number)->where('qr_code', $request->qr_code)->first();

        if(!$student) {
            return response()->json([
                'message' => 'Invalid Student!',
            ], 404);
        }

        $instructor = Auth::guard('instructor')->user();

        $attendance = Attendance::create([
            'student_id' => $student->id,
            'instructor_id' => $instructor->id,
            'attended_at' => now(),
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
