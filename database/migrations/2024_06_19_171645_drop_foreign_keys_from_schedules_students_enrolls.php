<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropForeignKeysFromSchedulesStudentsEnrolls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['academic_year_semester_id']); // or use dropForeign('schedules_academic_year_semester_id_foreign') if you know the exact name
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['academic_year_semester_id']); // or use dropForeign('students_academic_year_semester_id_foreign') if you know the exact name
        });

        Schema::table('enrolls', function (Blueprint $table) {
            $table->dropForeign(['academic_year_semester_id']); // or use dropForeign('enrolls_academic_year_semester_id_foreign') if you know the exact name
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->foreign('academic_year_semester_id')->references('id')->on('academic_year_semesters')->onDelete('cascade');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->foreign('academic_year_semester_id')->references('id')->on('academic_year_semesters')->onDelete('cascade');
        });

        Schema::table('enrolls', function (Blueprint $table) {
            $table->foreign('academic_year_semester_id')->references('id')->on('academic_year_semesters')->onDelete('cascade');
        });
    }
}
