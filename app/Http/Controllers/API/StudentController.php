<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\API\StudentScheduleResource;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AcademicYearSemester;
use App\Http\Resources\API\StudentProfileResource;
use App\Http\Resources\API\Instructor\ScheduleResource;

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

        // Return the data as JSON response
        return response()->json([
            'schedules' => StudentScheduleResource::collection($data->schedules)
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
}
