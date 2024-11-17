<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pre-Enrollment Summary</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; }
        .section { margin-top: 20px; }
        .section-title { font-weight: bold; }
        .details { margin: 10px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Pre-Enrollment Summary</h2>
        <p>Student: {{ $student->name }}</p>
        <p>Program: {{ $enrollmentData->program_name }}</p>
        <p>Year Level: {{ $enrollmentData->year_level }}</p>
    </div>
    
    <div class="section">
        <h3 class="section-title">Selected Schedules</h3>
        <table width="100%" border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Course Code</th>
                <th>Description</th>
                <th>Instructor</th>
                <th>Room</th>
                <th>Days</th>
                <th>Time</th>
            </tr>
            @foreach($enrollmentData->schedules as $schedule)
                <tr>
                    <td>{{ $schedule->subject_code }}</td>
                    <td>{{ $schedule->subject_description }}</td>
                    <td>{{ $schedule->teacher_name }}</td>
                    <td>{{ $schedule->room }}</td>
                    <td>{{ $schedule->days }}</td>
                    <td>{{ $schedule->time }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</body>
</html>
