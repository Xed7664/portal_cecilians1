<!DOCTYPE html>
<html>
<head>
    <title>Pre-Enrollment Form</title>
    <style>
        /* General Layout and Font */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            width: 100%;
            box-sizing: border-box;
        }

        /* Header Table Layout */
        .header-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
            text-align: center;
        }

        /* Requirements Section */
        .requirements {
            border: 1px dashed black;
            padding: 10px;
            width: 150px;
            height: 100px;
            vertical-align: top;
            text-align: left;
        }

        /* School Info Section */
        .school-info-container {
            width: 100%;
            text-align: center;
        }

        .school-info-inner-table {
            margin: 0 auto;
        }

        .logo {
            width: 60px;
            height: auto;
        }

        .school-details p {
            margin: 4px 0;
        }

        .school-details p:first-child,
        .school-details p:nth-child(2) {
            font-size: 14px;
            font-weight: bold;
        }

        .school-details p:nth-child(3),
        .school-details p:nth-child(4) {
            font-size: 12px;
            font-weight: bold;
            padding-top: 4px;
        }

        /* Info Table Section */
        .info-table {
            border: 1px solid black;
            width: 220px;
            height: 100px;
            vertical-align: top;
        }

        .info-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            border: 1px solid black;
            height: 20px;
            padding: 2px;
        }

        /* Column Widths */
        .thin-column {
            width: 30%;
        }

        .wide-column {
            width: 70%;
        }
        .courses th,
.courses td {
    vertical-align: middle;
    text-align: center;
}

.courses thead th {
    text-transform: uppercase;
    font-weight: bold;
}

.courses thead th[rowspan="2"] {
    vertical-align: middle;
}

.courses .text-end {
    text-align: right !important;
}

.courses tbody td {
    text-align: center;
    vertical-align: middle;
}

    </style>
</head>
<body>

    <!-- Header Section as a Table for PDF Compatibility -->
    <table class="header-table">
        <tr>
            <!-- Requirements Section -->
            <td class="requirements">
                <p>&#x25EF; Birth Certificate</p>
                <p>&#x25EF; 1 by 1 Picture</p>
            </td>

            <!-- School Info with Logo and Details -->
            <td class="school-info-container">
                <table class="school-info-inner-table">
                    <tr>
                        <td><img src="img/scclogo.png" alt="SCC Logo" class="logo"></td>
                        <td class="school-details">
                            <p><strong>St. Cecilia's College-Cebu, Inc.</strong></p>
                            <p>De La Salle Supervised School</p>
                            <p><strong>HIGHER EDUCATION DEPARTMENT</strong></p>
                            <p><strong>REGISTRATION FORM</strong></p>
                            <p><u>{{ $semester }}</u> Semester/Term</p>
                            <p>AY <u>{{ $schoolYear }}</u></p>
                        </td>
                    </tr>
                </table>
            </td>

            <!-- Info Table Section -->
            <td class="info-table">
                <table>
                    <tr>
                        <td class="thin-column"></td>
                        <td class="wide-column"></td>
                    </tr>
                    <tr>
                        <td class="thin-column"></td>
                        <td class="wide-column"></td>
                    </tr>
                    <tr>
                        <td class="thin-column"></td>
                        <td class="wide-column"></td>
                    </tr>
                    <tr>
                        <td class="thin-column"></td>
                        <td class="wide-column"></td>
                    </tr>
                    <tr>
                        <td class="thin-column"></td>
                        <td class="wide-column"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    
        <!-- start of academic-stat -->
        <div class="academic-stat">
            <div>
                <p><span class="a-stat">Academic Status:</span> (<span> </span>) Regular</p>
                <p>(<span> </span>) Irregular</p>
            </div>
            <div>
              <p>Year<span class="span-year">:<span class="u-year">{{ $yearLevel ??'' }}</span></span></p>
              <p>Program<span class="span-program">:<span class="u-program">{{ $program->code ?? '' }}</span></span></p>


            </div>
        </div>
        <!-- end of academic-stat -->

        <!-- start of peronal-info -->
         <div class="personal-info">
            <table>
                <tbody>
                    <tr>
                        <td><b>Name:</b></td>
                        <td>Aniñon</td>
                        <td>{{ $student->FullName }}</td>
                        <td>Ugsimar</td>
                        <td></td>
                        <td><b>Mobile No:</b> <span>09506832656</span></td>
                    </tr>
                    <tr class="name">
                        <td></td>
                        <td>Surname</td>
                        <td>First Name</td>
                        <td>Middle Name</td>
                    </tr>
                    <tr>
                        <td><b>Date of Birth:</b><span> {{ $student->Birthday }}</span></td>
                        <td><b>Month:</b> <span>December</span></td>
                        <td><b>Day:</b> <span>12</span></td>
                        <td><b>Sex:</b> <span> {{ $student->Gender }}</span></td>
                        <td><b>Religion:</b> <span>{{ $student->Religion ?? '' }}</span></td>
                        <td><b>Status:</b> <span>{{ $student->Status ?? '' }}</span></td>
                    </tr>
                </tbody>
            </table>

            <div class="asd">
              <p><b>Place of Birth: </b><span>{{ $student->BirthPlace ?? '' }}</span></p>
            </div>
            <br>
            <div class="asd">
              <p><b>Home Address: </b><span>{{ $student->Address ?? '' }}</span></p>
              <p><b>Present Address: </b><span>{{ $student->Addres ?? '' }}</span></p>
            </div>
            <br>
            <div class="asd">
              <p><b>Previous School Attended: </b><span>{{ $student->previous_school ?? '' }}</span></p>
              <p><b>Previous School Address: </b><span>{{ $student->previous_school_address?? '' }}</span></p>
            </div>
            <br>
            <div class="asd asd1">
              <p><b>Father's Name: </b><span>{{ $student->father_name ?? '' }}</span></p>
              <p class="occupation"><b>Occupation: </b><span>{{ $student->father_occupation?? '' }}</span></p>
              <p class="employer"><b>Employer: </b><span></span></p>
            </div>
            <br>
            <div class="asd asd1">
              <p><b>Mother's Name: </b><span>{{ $student->mother_name ?? '' }}n</span></p>
              <p class="occupation"><b>Occupation: </b><span>{{ $student->mother_occupation ?? '' }}</span></p>
              <p class="employer"><b>Employer: </b><span></span></p>
            </div>
            <br>
            <div class="asd asd1">
              <p><b>Guardian: </b><span>Gina U. Aniñon</span></p>
              <p class="occupation"><b>Occupation: </b><span>Housewife</span></p>
              <p class="employer"><b>Employer: </b><span></span></p>
            </div>
            <br><br>
         <!-- end of personal-info -->

         <table class="table table-bordered courses" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="border: 1px solid black;">
            <th rowspan="2" style="border: 1px solid black;">COURSE CODE NO. </th>
            <th rowspan="2" style="border: 1px solid black;">COURSE DESCRIPTION </th>
            <th colspan="2" class="text-center" style="border: 1px solid black;">UNITS </th>
            <th rowspan="2" style="border: 1px solid black;">DAYS </th>
            <th rowspan="2" style="border: 1px solid black;">TIME </th>
            <th rowspan="2" style="border: 1px solid black;">ROOM </th>
            <th rowspan="2" style="border: 1px solid black;">INSTRUCTOR </th>
        </tr>
        <tr style="border: 1px solid black;">
            <th style="border: 1px solid black;">Lab</th>
            <th style="border: 1px solid black;">Lec</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalLabUnits = 0;
            $totalLecUnits = 0;
        @endphp

        @foreach ($schedules as $sectionId => $section)
            @foreach ($section['schedules'] as $schedule)
                @php
                    $labUnits = $schedule['subject_lab'] ?? 0;
                    $lecUnits = $schedule['subject_lecture'] ?? 0;
                    $totalLabUnits += $labUnits;
                    $totalLecUnits += $lecUnits;
                @endphp
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black;">{{ $schedule['subject_code'] }}</td>
                    <td style="border: 1px solid black;">{{ $schedule['subject_description'] }}</td>
                    <td class="text-center" style="border: 1px solid black;">{{ $labUnits }}</td>
                    <td class="text-center" style="border: 1px solid black;">{{ $lecUnits }}</td>
                    <td style="border: 1px solid black;">{{ $schedule['days'] }}</td>
                    <td style="border: 1px solid black;">{{ $schedule['time'] }}</td>
                    <td style="border: 1px solid black;">{{ $schedule['room'] }}</td>
                    <td style="border: 1px solid black;">{{ $schedule['teacher_name'] }}</td>
                </tr>
            @endforeach
        @endforeach

        <!-- Total Units Row -->
        <tr style="border: 1px solid black;">
            <td colspan="2" class="text-end" style="border: 1px solid black;"><b>Total Number of Units</b></td>
            <td class="text-center" style="border: 1px solid black;"><b>{{ $totalLabUnits }}</b></td>
            <td class="text-center" style="border: 1px solid black;"><b>{{ $totalLecUnits }}</b></td>
            <td colspan="4" style="border: 1px solid black;"></td>
        </tr>
    </tbody>
</table>


          <!-- end of courses -->

       </div>

</body>
</html>
