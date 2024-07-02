<?php

namespace App\Http\Controllers\API;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Initialize query builder for Schedule
        $query = Schedule::query();

        // Apply filters if they exist in the request
        if ($request->has('year_id')) {
            $query->where('year_id', $request->input('year_id'));
        }

        if ($request->has('semester_id')) {
            $query->where('semester_id', $request->input('semester_id'));
        }

        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->input('subject_id'));
        }

        // Fetch schedules with applied filters
        $schedules = $query->get();

        // Return a response, typically as JSON or to a view
        return response()->json($schedules);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'sched_code' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'days' => 'required|array',
            'instructor_id' => 'required|integer|exists:instructors,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'year_id' => 'required|integer|exists:years,id',
            'semester_id' => 'required|integer|exists:semesters,id',
        ]);

        // Convert start_time and end_time to Carbon instances
        $start = \Carbon\Carbon::parse($validatedData['start_time']);
        $end = \Carbon\Carbon::parse($validatedData['end_time']);

        // Create a new Schedule instance and assign the validated data
        $schedule = new Schedule();
        $schedule->sched_code = $validatedData['sched_code'];
        $schedule->start = $start;
        $schedule->end = $end;
        $schedule->days = $validatedData['days'];
        $schedule->instructor_id = $validatedData['instructor_id'];
        $schedule->subject_id = $validatedData['subject_id'];
        $schedule->year_id = $validatedData['year_id'];
        $schedule->semester_id = $validatedData['semester_id'];

        // Save the schedule to the database
        $schedule->save();

        // Return a response, e.g., the newly created schedule or a success message
        return response()->json($schedule, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'sched_code' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'days' => 'required|array',
            'instructor_id' => 'required|integer|exists:instructors,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'year_id' => 'required|integer|exists:years,id',
            'semester_id' => 'required|integer|exists:semesters,id',
        ]);

        // Convert start_time and end_time to Carbon instances
        $start = \Carbon\Carbon::parse($validatedData['start_time']);
        $end = \Carbon\Carbon::parse($validatedData['end_time']);

        // Find the Schedule instance by its ID
        $schedule = Schedule::findOrFail($id);

        // Update the schedule instance with validated data
        $schedule->sched_code = $validatedData['sched_code'];
        $schedule->start = $start;
        $schedule->end = $end;
        $schedule->days = $validatedData['days'];
        $schedule->instructor_id = $validatedData['instructor_id'];
        $schedule->subject_id = $validatedData['subject_id'];
        $schedule->year_id = $validatedData['year_id'];
        $schedule->semester_id = $validatedData['semester_id'];

        // Save the updated schedule to the database
        $schedule->save();

        // Return a response, e.g., the updated schedule or a success message
        return response()->json($schedule);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the Schedule instance by its ID
        $schedule = Schedule::findOrFail($id);

        // Delete the Schedule instance
        $schedule->delete();

        // Optionally, return a response indicating success
        return response()->json(['message' => 'Schedule deleted successfully']);
    }
}
