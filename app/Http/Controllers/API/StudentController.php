<?php

namespace App\Http\Controllers\API;

use App\Models\AcademicYearSemester;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\Instructor\ScheduleResource;

class StudentController extends Controller
{
    public function getSchedules(Request $request, $id)
    {
        // Retrieve the authenticated instructor
        // $instructor = Auth::guard('instructor')->user();

        // Eager load schedules and attendances
        $data = Student::with(['schedules','attendances'])
            ->find($id);

        // Check if data is found
        if (!$data) {
            return response()->json([
                'message' => 'Invalid Record!',
            ], 404);
        }

        // Return the data as JSON response
        return response()->json([
            'data' => $data
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
}