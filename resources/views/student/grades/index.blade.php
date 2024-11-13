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
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                        <h5 class="card-title mb-3 mb-md-0">
                            Year & Section: {{ $enrollments->first()->yearLevel->name ?? 'Unknown Year Level' }} - {{ $enrollments->first()->section->name ?? 'Unknown Section' }}
                        </h5>
                        <div class="btn-group" role="group" aria-label="Grade Actions">
                            <a href="{{ route('student.grades.requestReview', ['studentId' => $enrollments->first()->student_id]) }}" class="btn btn-outline-danger">
                                <i class="bi bi-exclamation-circle"></i> Request Review
                            </a>
                            <a href="{{ route('student.grades.all', ['studentId' => $enrollments->first()->student_id]) }}" class="btn btn-outline-primary">
                                <i class="bi bi-journal-text"></i> View Grade Records
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            <div class="card-body">
                @if($enrollments->isEmpty())
                    <h5 class="text-danger mt-3">No record for this Semester.</h5>
                @else
                    <h5 class="mt-4">{{ $enrollments->first()->semester->name ?? 'Unknown Semester' }} ({{ $enrollments->first()->schoolYear->name ?? 'Unknown School Year' }})</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
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
                                @foreach($enrollments as $enrollment)
                                    @php $grade = $enrollment->grade->first(); @endphp
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
                                            <td colspan="5" class="text-center">No grades available</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection

@section('styles')
<style>
    .btn-group .btn {
        border-radius: 0;
    }
    .btn-group .btn:first-child {
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }
    .btn-group .btn:last-child {
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
    }
    @media (max-width: 767.98px) {
        .btn-group {
            display: flex;
            flex-direction: column;
            width: 100%;
        }
        .btn-group .btn {
            border-radius: 0.25rem;
            margin-bottom: 0.25rem;
            width: 100%;
        }
    }
</style>
@endsection