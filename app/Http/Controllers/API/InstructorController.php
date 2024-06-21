<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Enroll;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\Instructor;
use Illuminate\Http\Request;
use App\Models\EnrollSubject;
use App\Mail\EmailNotification;
use App\Models\MessageTemplate;
use Vonage\Laravel\Facade\Vonage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\AcademicYearSemester;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\API\ScheduleInfoResource;
use App\Http\Resources\API\StudentProfileResource;
use App\Http\Resources\API\InstructorProfileResource;
use App\Http\Resources\API\Instructor\ScheduleResource;
use App\Http\Resources\API\Instructor\AttendanceResource;
use App\Http\Resources\API\Instructor\EnrolledStudentResource;
use App\Http\Resources\API\Instructor\AvailableEnrolledStudentResource;

class InstructorController extends Controller
{
    public function getSchedules(Request $request, $id)
    {
        // Retrieve the authenticated instructor
        // $instructor = Auth::guard('instructor')->user();

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
                        $fail('The ' . $attribute . ' must not start with http://.');
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
        if ($student->schedules()->where('schedule_id', $request->schedule_id)->doesntExist()) {
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
            ], 409);
        }


        $late = false;

        // get start time
        $schedule = Schedule::find($request->schedule_id);

        // Set initial late flag to false
        $late = false;

        // Retrieve the schedule based on the provided schedule_id
        $schedule = Schedule::find($request->schedule_id);

        if ($schedule) {
            // Parse the start time and current time into Carbon instances
            $start_time = Carbon::parse($schedule->start_time, 'Asia/Manila');
            $time_in = Carbon::now('Asia/Manila');

            // Check if time_in is later than start_time
            if ($time_in->greaterThan($start_time)) {
                $late = true;
            }

            // Check if there are guardian details
            if (!is_null($student->parent_name) && !is_null($student->parent_number)) {
                // Fetch the message template
                // $messageTemplate = MessageTemplate::where('type', 'sms')->where('level_of_offense', '1st offense')->first();

                // if ($messageTemplate) {
                //     // Replace placeholders with actual values
                //     $message = str_replace(
                //         ['[parentName]', '[studentName]', '[instructorName]'],
                //         [$student->parent_name, $student->full_name, $schedule->instructor->full_name],
                //         $messageTemplate->template
                //     );

                //     $basic  = new \Vonage\Client\Credentials\Basic("9af65d3f", "4JRcdZ9H1gN9GcFg");
                //     $client = new \Vonage\Client($basic);

                //     $response = $client->sms()->send(
                //         new SMS("+".$student->parent_number, 'AMS', $message)
                //     );

                //     $message = $response->current();

                // }

                $basic = new \Vonage\Client\Credentials\Basic("9af65d3f", "4JRcdZ9H1gN9GcFg");
                $client = new \Vonage\Client($basic);

                $client->sms()->send(
                    new \Vonage\SMS\Message\SMS("+" . $student->parent_number, 'AMS', 'Hi Parent, Your child,' . $student->full_name . ',  has been late for their class today. Please remind them to log in earlier. Thank you!')
                );
            }
        }

        // Create a new Attendance record
        $attendance = Attendance::create([
            'student_id' => $student->id,
            'schedule_id' => $request->schedule_id,
            'scanned_by' => $id,
            'is_late' => $late,
            'time_in' => $time_in->format('Y-m-d H:i:s'),
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

    public function getScheduleInfo($schedId)
    {
        $schedule = Schedule::find($schedId);

        if (!$schedule) {
            return response()->json([
                'message' => 'Schedule not found',
            ], 404);
        }

        return response()->json([
            'data' => new ScheduleInfoResource($schedule)
        ]);
    }

    public function registerStudentToSchedule(Request $request, $schedId)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $student = Student::find($request->student_id);

        if (!$student) {
            return response()->json([
                'message' => 'Student not found',
            ], 404);
        }

        $schedule = Schedule::find($schedId);

        if (!$schedule) {
            return response()->json([
                'message' => 'Schedule not found',
            ], 404);
        }

        // Check if the student is already enrolled in the schedule
        if ($student->schedules()->where('schedule_id', $schedId)->exists()) {
            return response()->json([
                'message' => 'Student is already enrolled in this schedule',
            ], 409);
        }

        // Enroll the student to the schedule
        $student->schedules()->attach($schedId);

        // Get the current date
        $today = now()->toDateString();

        $students = $schedule->students()
            ->with([
                'attendances' => function ($query) use ($today) {
                    $query->whereDate('created_at', $today);
                }
            ])
            ->get();

        // Prepare response data
        $enrolledStudents = [];

        foreach ($students as $student) {
            $attendanceStatus = false; // Default to absent

            // Check if the student has attendance for today
            if ($student->attendances->isNotEmpty()) {
                $attendanceStatus = true;
            }

            // Prepare student data for response
            $enrolledStudents[] = [
                'id' => $student->id,
                'full_name' => $student->full_name,
                'course' => $student->course->name,
                'email' => $student->email,
                'image' => config('app.url') . '/storage/' . $student->image,
                'student_number' => $student->student_number,
                'attendance_status' => $attendanceStatus,
            ];
        }

        return response()->json([
            'message' => 'Student enrolled to schedule successfully',
            'data' => $enrolledStudents
        ]);
    }

    public function getEnrolledStudents(Request $request, $schedId)
    {
        $schedule = Schedule::find($schedId);

        if (!$schedule) {
            return response()->json([
                'message' => 'Schedule not found',
            ], 404);
        }

        // Get the current date
        $today = now()->toDateString();

        // Retrieve enrolled students and check their attendance for today
        $students = $schedule->students()
            ->with([
                'attendances' => function ($query) use ($today) {
                    $query->whereDate('created_at', $today);
                }
            ])
            ->get();

        // Prepare response data
        $enrolledStudents = [];

        foreach ($students as $student) {
            $attendanceStatus = false; // Default to absent

            // Check if the student has attendance for today
            if ($student->attendances->isNotEmpty()) {
                $attendanceStatus = true;
            }

            // Prepare student data for response
            $enrolledStudents[] = [
                'id' => $student->id,
                'full_name' => $student->full_name,
                'course' => $student->course->name,
                'email' => $student->email,
                'image' => config('app.url') . '/storage/' . $student->image,
                'student_number' => $student->student_number,
                'attendance_status' => $attendanceStatus,
            ];
        }

        return response()->json([
            'data' => $enrolledStudents,
        ]);
    }

    public function removeStudentFromSchedule($schedId, $studentId)
    {
        $student = Student::find($studentId);

        if (!$student) {
            return response()->json([
                'message' => 'Student not found',
            ], 404);
        }

        $schedule = Schedule::find($schedId);

        if (!$schedule) {
            return response()->json([
                'message' => 'Schedule not found',
            ], 404);
        }

        // Check if the student is enrolled in the schedule
        if (!$student->schedules()->where('schedule_id', $schedId)->exists()) {
            return response()->json([
                'message' => 'Student is not enrolled in this schedule',
            ], 404);
        }

        // Detach the student from the schedule
        $student->schedules()->detach($schedId);

        // Get the updated list of enrolled students
        $students = $schedule->students()
            ->with([
                'attendances' => function ($query) {
                    $query->whereDate('created_at', now()->toDateString());
                }
            ])
            ->get();

        // Prepare response data
        $enrolledStudents = [];

        foreach ($students as $student) {
            $attendanceStatus = false; // Default to absent

            // Check if the student has attendance for today
            if ($student->attendances->isNotEmpty()) {
                $attendanceStatus = true;
            }

            // Prepare student data for response
            $enrolledStudents[] = [
                'id' => $student->id,
                'full_name' => $student->full_name,
                'course' => $student->course->name,
                'email' => $student->email,
                'image' => config('app.url') . '/storage/' . $student->image,
                'student_number' => $student->student_number,
                'attendance_status' => $attendanceStatus,
            ];
        }

        return response()->json([
            'message' => 'Student removed from schedule successfully',
            'data' => $enrolledStudents
        ]);
    }


    public function getStudentProfile(Request $request, $id)
    {
        $student = Student::find($id)->first();

        if (!$student) {
            return response()->json([
                'message' => 'Student not found',
            ], 404);
        }

        return response()->json([
            'data' => StudentProfileResource::collection($student)
        ]);
    }

    public function getAvailableEnrolledStudents($scheduleId)
    {
        // Find the schedule by its ID
        $schedule = Schedule::find($scheduleId);

        // Check if schedule exists
        if (!$schedule) {
            return response()->json([
                'message' => 'Schedule not found',
            ], 404);
        }

        // Get the subject ID from the schedule
        $subjectId = $schedule->subject_id;

        // Get the list of student IDs who are enrolled in the subject
        $enrolledStudentIds = Enroll::whereHas('enrolled_subjects', function ($query) use ($subjectId) {
            $query->where('subject_id', $subjectId);
        })->pluck('student_id');

        // Get the list of students who are not enrolled in the schedule but are enrolled in the subject
        $availableStudents = Student::whereDoesntHave('schedules', function ($query) use ($scheduleId) {
            $query->where('schedule_id', $scheduleId);
        })->whereIn('id', $enrolledStudentIds)->get();

        // Transform the collection using the resource
        $formattedStudents = AvailableEnrolledStudentResource::collection($availableStudents);

        // Return the formatted response
        return response()->json([
            'data' => $formattedStudents,
        ], 200);
    }


}
