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
        Schema::create('attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('time_in');
            $table->unsignedBigInteger('scanned_by');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('schedule_id');
            $table
                ->foreign('schedule_id')
                ->references('id')
                ->on('schedules')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table
                ->foreign('student_id')
                ->references('id')
                ->on('students')
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
        Schema::dropIfExists('attendances');
    }
};
