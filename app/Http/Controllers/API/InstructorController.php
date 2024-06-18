<?php

namespace App\Http\Controllers\API;

use App\Models\Student;
use App\Models\Attendance;
use App\Models\Instructor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AcademicYearSemester;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\API\InstructorProfileResource;
use App\Http\Resources\API\Instructor\ScheduleResource;
use App\Http\Resources\API\Instructor\AttendanceResource;

class InstructorController extends Controller
{
    public function getSchedules(Request $request, $id)
    {
        // Retrieve the authenticated instructor
        // $instructor = Auth::guard('instructor')->user();

        $date = $request->date;

        // Eager load schedules and attendances
        $data = Instructor::with([
            'schedules',
            'schedules.students'
        ])
            ->find($id);

        // Check if data is found
        if (!$data) {
            return response()->json([
                'message' => 'Invalid Record!',
            ], 404);
        }

        // Transform schedules data using ScheduleResource
        $schedules = ScheduleResource::collection($data->schedules);

        // Return the data as JSON response
        return response()->json([
            'data' => $schedules
        ]);
    }
    public function markAttendance(Request $request, $id)
    {
        $request->validate([
            'schedule_id' => 'required',
            'qr_code' => [
                'required',
                'exists:students,qr_code',
                function ($attribute, $value, $fail) {
                    if (strpos($value, 'http://') === 0) {
                        $fail('The '.$attribute.' must not start with http://.');
                    }
                },
            ],
        ]);

        $student = Student::where('qr_code', $request->qr_code)->first();

        if (!$student) {
            return response()->json([
                'message' => 'Invalid Student!',
            ], 404);
        }

        //check if the students is enrolled to this schedule
        if($student->schedules()->where('schedule_id', $request->schedule_id)->doesntExist()){
            return response()->json([
                'message' => 'Student is not enrolled to this schedule!',
            ], 404);
        }

        $currentDate = now()->toDateString();

        $check = Attendance::where('schedule_id', $request->schedule_id)
            ->where('student_id', $student->id)
            ->whereDate('time_in', $currentDate)
            ->first();

        if ($check) {
            return response()->json([
                'message' => 'Already Logged in!',
                'attendance' => $check,
                'student' => [
                    'student_number' => $student->student_number,
                    'image' => $student->image,
                    'first_name' => $student->first_name,
                    'last_name' => $student->last_name,
                ],
            ], 401);
        }

        $attendance = Attendance::create([
            'student_id' => $student->id,
            'schedule_id' => $request->schedule_id,
            'scanned_by' => $id,
            'time_in' => now()->timezone('Asia/Manila')->format('Y-m-d H:i:s'),
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

    public function getSchoolYear(Request $request)
    {

        $data = AcademicYearSemester::all();

        if (!$data) {
            return response()->json([
                'message' => 'Invalid Record!',
            ], 404);
        }

        return response()->json([
            'school_year' => $data
        ]);
    }

    public function profile(Request $request, $id)
    {
        $student = Instructor::find($id);

        if (!$student) {
            return response()->json([
                'message' => 'Instructor not found',
            ], 404);
        }

        return response()->json([
            'data' => new InstructorProfileResource($student)
        ]);
    }

    public function getScheduleStudentAttendances(Request $request, $schedId)
    {
        // Get the start and end date from the request, assuming they are provided as 'start_date' and 'end_date'
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Query attendances based on schedule_id and optional date range
        $query = Attendance::where('schedule_id', $schedId)
            ->with('student');

        // Apply date range filter if both start date and end date are provided
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Fetch the attendances
        $attendances = $query->get();

        // Return JSON response
        return response()->json([
            'data' => AttendanceResource::collection($attendances)
        ]);
    }
}
