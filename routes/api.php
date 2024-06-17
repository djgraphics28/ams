<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\StudentAuthController;
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

Route::group(['prefix' => 'student'], function () {
    Route::get('/{id}/schedules', [StudentController::class, 'getSchedules'])->name('student.schedules');
    Route::post('/logout', [StudentAuthController::class, 'logout']);
    Route::get('/schoolYearSemester', [StudentController::class, 'getSchoolYearSemester']);
})->middleware('auth:sanctum');

Route::group(['prefix' => 'instructor'], function () {
    Route::get('/{id}/schedules', [InstructorController::class, 'getSchedules'])->name('instructor.schedules');
    Route::post('/scan-qrcode', [InstructorController::class, 'markAttendance'])->name('scan-qrcode');
    Route::post('/logout', [InstructorAuthController::class, 'logout']);
    Route::get('/schoolYearSemester', [InstructorController::class, 'getSchoolYearSemester']);
})->middleware('auth:sanctum');
