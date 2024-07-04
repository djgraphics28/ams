<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $scheduleId;
    protected $date;

    public function __construct($scheduleId, $date)
    {
        $this->scheduleId = $scheduleId;
        $this->date = $date;
    }

    public function collection()
    {
        return Attendance::where('schedule_id', $this->scheduleId)->where('time_in_date', $this->date)->get();
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Time In',
            'Date',
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->student->full_name,
            $attendance->time_in,
            $attendance->created_at->format('Y-m-d'),
        ];
    }
}
