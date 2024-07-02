<!DOCTYPE html>
<html>

<head>
    <title>Attendance Report</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            size: A4 landscape;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 20px;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody+tbody {
            border-top: 2px solid #dee2e6;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .table-bordered thead th,
        .table-bordered thead td {
            border-bottom-width: 2px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="text-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 100px;">
        </div>
        <h2 class="mb-4">Attendance Report</h2>
        <table class="table table-bordered table-sm table-striped table-condensed">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student Number</th>
                    <th>Student Name</th>
                    <th>Time In</th>
                    <th>Date</th>
                    <th>Schedule</th>
                    <th>Subject</th>
                    <th>Instructor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $index => $record)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $record->student->student_number }}</td>
                        <td>{{ $record->student->full_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($record->time_in)->format('h:i A') }}</td>
                        <td>{{ \Carbon\Carbon::parse($record->time_in)->format('Y-m-d') }}</td>
                        <td>{{ \Carbon\Carbon::parse($record->schedule->start)->format('h:i A') }} - {{ \Carbon\Carbon::parse($record->schedule->end)->format('h:i A') }} , {{ json_encode($record->schedule->days) }}</td>
                        <td>{{ $record->schedule->subject->name }}</td>
                        <td>{{ $record->schedule->instructor->full_name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
