@extends('layouts.app')
@section('title', 'Preview-Enrollment')
@section('content')
<div class="container">
    <h1 class="mb-4">Pre-enrollment Preview</h1>

    {{-- Student Details --}}
    <div class="card mb-4">
        <div class="card-header">
            <h4>Your Pre-enrollment Details</h4>
        </div>
        <div class="card-body">
        <p><strong>Full Name:</strong> {{ $student->FullName ?? 'Not provided' }}</p>
<p><strong>Student ID:</strong> {{ $student->StudentID ?? 'Not provided' }}</p>
<p><strong>Program/Course:</strong> {{ $student->course->name ?? 'Not provided' }}</p>
<p><strong>Year Level:</strong> {{ $student->YearLevel ?? 'Not provided' }}</p>
<p><strong>Section:</strong> {{ $student->section ?? 'Not provided' }}</p>
<p><strong>School Year:</strong> {{ $student->SchoolYear ?? 'Not provided' }}</p>
<p><strong>Semester:</strong> {{ $student->Semester ?? 'Not provided' }}</p>

        </div>
    </div>

    {{-- Enrolled Subjects --}}
    <div class="card">
        <div class="card-header">
            <h4>Enrolled Subjects</h4>
        </div>
        <div class="card-body">
            @if($enrolledSubjects->isEmpty())
                <p>No subjects enrolled yet.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Description</th>
                            <th>Room</th>
                            <th>Days</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Section</th>
                            <th>Semester</th>
                            <th>School Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrolledSubjects as $subject)
                            <tr>
                                <td>{{ $subject->subject_code }}</td>
                                <td>{{ $subject->description }}</td>
                                <td>{{ $subject->room }}</td>
                                <td>{{ $subject->days }}</td>
                                <td>{{ $subject->start_time }}</td>
                                <td>{{ $subject->end_time }}</td>
                                <td>{{ $subject->section_name }}</td>
                                <td>{{ $subject->semester }}</td>
                                <td>{{ $subject->school_year }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
