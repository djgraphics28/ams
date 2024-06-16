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
        Schema::table('enrolls', function (Blueprint $table) {
            $table->string('year_level')->after('student_id');
            $table->string('block')->after('year_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrolls', function (Blueprint $table) {
            $table->dropColumn('year_level');
            $table->dropColumn('block');
        });
    }
};
