@extends('layouts.app')

@section('title', 'Grade Review Requests')

@section('content')
<main id="main" class="main">
    <div class="container">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show position-fixed bottom-0 end-0 m-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show position-fixed bottom-0 end-0 m-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Main Content -->
        @if($hasGrades)
            @php
                // Get the latest semester and related IDs from the filtered enrollments
                $latestEnrollment = $enrollments->first();
                $semester = $latestEnrollment->semester;
                $schoolYear = $latestEnrollment->schoolYear;
                $section = $latestEnrollment->section;
                $yearLevel = $latestEnrollment->yearLevel;
            @endphp

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">{{ $semester->name ?? 'Unknown Semester' }} ({{ $schoolYear->name ?? 'Unknown School Year' }})</h5>
                </div>
                <div class="card-body">
                    <h6 class="mt-2">Section: {{ $section->name ?? 'Unknown Section' }}</h6>
                    <h6 class="mt-2">Year Level: {{ $yearLevel->name ?? 'Unknown Year Level' }}</h6>

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
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollments as $enrollment)
                                @foreach($enrollment->grades as $grade)
                                    <tr>
                                        <td>{{ $grade->subject->subject_code }}</td>
                                        <td>{{ $grade->subject->description }}</td>
                                        <td>{{ $grade->prelim ?: 'No Grade' }}</td>
                                        <td>{{ $grade->midterm ?: 'No Grade' }}</td>
                                        <td>{{ $grade->prefinal ?: 'No Grade' }}</td>
                                        <td>{{ $grade->final ?: 'No Grade' }}</td>
                                        <td>{{ $grade->remarks ?: 'No Remarks' }}</td>
                                        <td>
                                            <form action="{{ route('student.grades.submitReviewRequest', ['gradeId' => $grade->id]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Request Review</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <p>No grades available for review.</p>
        @endif
    </div>
</main>
@endsection
