<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enrolls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('academic_year_semester_id');
            $table
                ->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table
                ->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table
                ->foreign('academic_year_semester_id')
                ->references('id')
                ->on('academic_year_semesters')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrolls');
    }
};
