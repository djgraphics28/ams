<?php

use App\Models\Student;
use App\Models\Schedule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_schedule', function (Blueprint $table) {
             $table->bigIncrements('id');
             $table->foreignIdFor(Student::class);
             $table->foreignIdFor(Schedule::class);
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_schedule');
    }
};
