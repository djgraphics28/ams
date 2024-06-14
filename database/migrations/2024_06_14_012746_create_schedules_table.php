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
        Schema::create('schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sched_code');
            $table->time('start');
            $table->time('end');
            $table->unsignedBigInteger('academic_year_semester_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('instructor_id')->nullable();
            $table->timestamps();

            $table
                ->foreign('academic_year_semester_id')
                ->references('id')
                ->on('academic_year_semesters')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('subject_id')
                ->references('id')
                ->on('subjects')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('instructor_id')
                ->references('id')
                ->on('instructors')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
