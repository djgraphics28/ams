<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\SubjectController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\API\ScheduleController;
use App\Http\Controllers\API\InstructorController;
use App\Http\Controllers\InstructorAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/student/login', [StudentAuthController::class, 'login']);


Route::post('/instructor/login', [InstructorAuthController::class, 'login']);

Route::get('/academic-filter', [StudentController::class, 'getYearSemeter']);

Route::group(['prefix' => 'student'], function () {
    Route::get('/{id}/schedules', [StudentController::class, 'getSchedules'])->name('student.schedules');
    Route::post('/{id}/logout', [StudentAuthController::class, 'logout']);
    Route::get('/schoolYearSemester', [StudentController::class, 'getSchoolYearSemester']);
    Route::get('/{id}/profile', [StudentController::class, 'profile']);
    Route::get('/{studentId}/schedule/{scheduleId}/attendance', [StudentController::class, 'getAttendancesInSchedule']);
})->middleware('auth:sanctum');

Route::group(['prefix' => 'instructor'], function () {
    Route::get('/{id}/schedules', [InstructorController::class, 'getSchedules'])->name('instructor.schedules');
    Route::post('/{id}/scan-qrcode', [InstructorController::class, 'markAttendance'])->name('scan-qrcode');
    Route::post('/logout', [InstructorAuthController::class, 'logout']);
    Route::get('/schoolYearSemester', [InstructorController::class, 'getSchoolYearSemester']);
    Route::get('/{id}/profile', [InstructorController::class, 'profile']);
    Route::get('/schedule/{id}', [InstructorController::class, 'getScheduleInfo']);
    Route::get('/schedule/{id}/attendances', [InstructorController::class, 'getScheduleStudentAttendances']);
    Route::post('/schedule/{id}/register', [InstructorController::class, 'registerStudentToSchedule']);
    Route::post('/schedule/{id}/remove', [InstructorController::class, 'removeStudentFromSchedule']);
    Route::get('/schedule/{id}/enrolled', [InstructorController::class, 'getEnrolledStudents']);
    Route::get('/schedule/{id}/available-enrolled', [InstructorController::class, 'getAvailableEnrolledStudents']);

    Route::get('/schedule/{schedId}/all-students', [InstructorController::class, 'getAllStudents']);

    Route::get('/subjects', [SubjectController::class, 'index']);

    Route::resource('schedules', ScheduleController::class);
})->middleware('auth:sanctum');
