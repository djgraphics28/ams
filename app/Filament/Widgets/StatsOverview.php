<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\Instructor;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Students', Student::count())
            ->description('Increase in students')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->descriptionColor('primary'),
            Stat::make('Total Instructor', Instructor::count()),
            Stat::make('Total Subjects', Subject::count()),
            Stat::make('Total Schedules', Schedule::count()),
        ];
    }
}
