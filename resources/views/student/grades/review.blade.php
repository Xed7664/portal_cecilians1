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
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Grade Review Requests</h2>
            <a href="{{ route('student.grades.index') }}" class="btn btn-primary">Go Back</a>
        </div>

        @if(isset($error))
            <div class="alert alert-warning" role="alert">
                {{ $error }}
            </div>
        @elseif($hasGrades)
            @php
                $groupedEnrollments = $enrollments->groupBy(function ($enrollment) {
                    return $enrollment->yearLevel->name . ' - ' . $enrollment->section->name;
                });
            @endphp

            @foreach($groupedEnrollments as $groupKey => $groupEnrollments)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">{{ $groupKey }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
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
                                    @foreach($groupEnrollments as $enrollment)
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
                                                    <form action="{{ route('student.grades.submitReviewRequest', ['gradeId' => $grade->id]) }}" method="POST" class="d-grid gap-2">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <span class="d-none d-md-inline">Request Review</span>
                                                            <span class="d-inline d-md-none">Review</span>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info" role="alert">
                No grades available for review.
            </div>
        @endif
    </div>
</main>
@endsection