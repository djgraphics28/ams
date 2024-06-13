<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('academic_year_semesters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('year_id');
            $table->unsignedBigInteger('semester_id');

            // Adding foreign key constraints
            $table->foreign('year_id')->references('id')->on('years')->onDelete('cascade');
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_year_semesters');
    }
};
