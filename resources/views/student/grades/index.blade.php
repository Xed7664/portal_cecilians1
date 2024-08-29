@extends('layouts.app')

@section('title', 'My Grades')

@section('content')
<main id="main" class="main">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2>My Grades</h2>
            </div>
        </div>

        <div class="card">
            @if($hasGrades)
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">
                            Year & Section: {{ $enrollments->first()->yearLevel->name ?? 'Unknown Year Level' }} - {{ $enrollments->first()->section->name ?? 'Unknown Section' }}
                        </h5>
                        <div>
                            <a href="{{ route('student.grades.requestReview', ['studentId' => $enrollments->first()->student_id]) }}" class="btn btn-danger me-2">
                                Request Review
                            </a>
                            <a href="{{ route('student.grades.all', ['studentId' => $enrollments->first()->student_id]) }}" class="btn btn-primary">
                                View Grade Records
                            </a>
                        </div>
                        
                    </div>
                </div>
            @endif
            <div class="card-body">
                @if($enrollments->isEmpty())
                    <h5 style="color:red; margin-top:10px;">You are not enrolled for this Semester.</h5>
                @else
                    @php
                        // Get the latest semester and related IDs
                        $latestSemester = $enrollments->sortByDesc('semester.id')->first();
                        $latestSemesterId = $latestSemester->semester->id ?? 0;
                        $latestSchoolYearId = $latestSemester->schoolYear->id ?? 0;
                        $latestSectionId = $latestSemester->section->id ?? 0;
                        $latestYearLevelId = $latestSemester->year_level_id ?? 0;
                    @endphp

                    @foreach($enrollments->groupBy('semester_id') as $semesterId => $semesterEnrollments)
                        @php
                            $firstEnrollment = $semesterEnrollments->first();
                            $semester = $firstEnrollment->semester;
                            $schoolYear = $firstEnrollment->schoolYear;
                            $section = $firstEnrollment->section;
                            $yearLevel = $firstEnrollment->yearLevel;

                            // Check if the enrollment matches the latest semester and IDs
                            $isLatest = $semesterId == $latestSemesterId &&
                                        $firstEnrollment->school_year_id == $latestSchoolYearId &&
                                        $firstEnrollment->section->id == $latestSectionId &&
                                        $firstEnrollment->year_level_id == $latestYearLevelId;
                        @endphp

                        @if($isLatest)
                            <h5 class="mt-4">{{ $semester->name ?? 'Unknown Semester' }} ({{ $schoolYear->name ?? 'Unknown School Year' }})</h5>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Subject Code</th>
                                        <th>Description</th>
                                        <th>Prelim</th>
                                        <th>Midterm</th>
                                        <th>Prefinal</th>
                                        <th>Final</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($semesterEnrollments as $enrollment)
                                        @php
                                            $grade = $enrollment->grades->first(); // Access the first grade record for this enrollment
                                        @endphp
                                        <tr>
                                            <td>{{ $enrollment->subject->subject_code }}</td>
                                            <td>{{ $enrollment->subject->description }}</td>
                                            
                                            @if($grade)
                                                <td>{{ $grade->prelim ?: 'No Grade' }}</td>
                                                <td>{{ $grade->midterm ?: 'No Grade' }}</td>
                                                <td>{{ $grade->prefinal ?: 'No Grade' }}</td>
                                                <td>{{ $grade->final ?: 'No Grade' }}</td>
                                                <td>{{ $grade->remarks ?: 'No Remarks' }}</td>
                                            @else
                                                <td colspan="7" style="text-align: center;">No grades available</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
