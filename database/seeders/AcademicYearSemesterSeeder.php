<?php

namespace Database\Seeders;

use App\Models\Year;
use App\Models\Semester;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\AcademicYearSemester;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AcademicYearSemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startYear = 2024;
        $endYear = 2034;

        for ($year = $startYear; $year < $endYear; $year++) {
            $nextYear = $year + 1;
            Year::create([
                "name" => "$year-$nextYear",
            ]);
        }

        $semesters = ['FIRST SEMESTER', 'SECOND SEMESTER'];

        foreach ($semesters as $semester) {
            Semester::create([
                'name' => $semester
            ]);
        }

        $years = Year::all();
        $semesters = Semester::all();

        foreach ($years as $year) {
            foreach ($semesters as $semester) {
                AcademicYearSemester::create([
                    'name' => $year->name . " - " . $semester->name,
                    'year_id' => $year->id,
                    'semester_id' => $semester->id,
                ]);
            }
        }
    }
}
