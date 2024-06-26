<?php

namespace App\Http\Controllers\API;

use App\Models\Year;
use App\Models\Student;
use App\Models\Semester;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AcademicYearSemester;
use App\Http\Resources\API\StudentProfileResource;
use App\Http\Resources\API\StudentScheduleResource;
use App\Http\Resources\API\Instructor\ScheduleResource;
use App\Http\Resources\API\Instructor\AttendanceResource;

class StudentController extends Controller
{
    public function getSchedules(Request $request, $id)
    {
        // Eager load schedules and attendances
        $data = Student::with(['schedules'])
            ->find($id);

        // Check if data is found
        if (!$data) {
            return response()->json([
                'message' => 'Invalid Record!',
            ], 404);
        }

        // Retrieve year and semester from request query parameters
        $year_id = $request->query('year');
        $semester_id = $request->query('semester');

        // Filter schedules based on year_id and semester_id if provided
        $schedules = $data->schedules();

        if ($year_id) {
            $schedules->where('year_id', $year_id);
        }

        if ($semester_id) {
            $schedules->where('semester_id', $semester_id);
        }

        // Get the filtered schedules
        $filteredSchedules = $schedules->get();

        // Return the data as JSON response
        return response()->json([
            'schedules' => StudentScheduleResource::collection($filteredSchedules)
        ]);
    }


    public function getSchoolYearSemester(Request $request)
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
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'message' => 'Student not found',
            ], 404);
        }

        return response()->json([
            'data' => new StudentProfileResource($student)
        ]);
    }

    public function getAttendancesInSchedule(Request $request, $student_id, $schedule_id)
    {
        $student = Student::find($student_id);

        if (!$student) {
            return response()->json([
                'message' => 'Student not found',
            ], 404);
        }

        $query = $student->attendances()->where('schedule_id', $schedule_id);

        // Check if start_date parameter is provided
        if ($request->has('start_date')) {
            $start_date = $request->input('start_date');
            $query->whereDate('created_at', '>=', $start_date);
        }

        // Check if end_date parameter is provided
        if ($request->has('end_date')) {
            $end_date = $request->input('end_date');
            $query->whereDate('created_at', '<=', $end_date);
        }

        $schedule = $query->get();

        if ($schedule->isEmpty()) {
            return response()->json([
                'message' => 'Attendance not found',
            ], 404);
        }

        return response()->json([
            'data' => AttendanceResource::collection($schedule)
        ]);
    }

    public function getYearSemeter(Request $request)
    {
        $data = [];

        $year = Year::all();
        $semester = Semester::all();

        $data['year'] = $year;
        $data['semester'] = $semester;

        if (!$year && !$semester) {
            return response()->json([
                'message' => 'No Year and Semster!',
            ], 404);
        }

        return response()->json([
            'data' => $data
        ]);
    }

}
