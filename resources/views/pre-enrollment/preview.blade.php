@extends('layouts.app')
@section('title', 'Preview-Enrollment')
@section('content')
<main id="main" class="main">
<div class="container">
    <!-- Flash Message Section -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h2>Pre-Enrollment Preview</h2>
    <p>Please review your pre-enrollment details below:</p>

    <h4>Student Information</h4>
    <p><strong>Student ID:</strong> {{ $student->StudentID }}</p>
    <p><strong>Student Type:</strong> {{ $student->student_type }}</p>
    <p><strong>Name:</strong> {{ $student->FullName }}</p>
    <p><strong>Program:</strong> {{ $selectedProgram->code }}</p>
    <p><strong>Year Level:</strong> {{ $selectedYearLevel }}</p>
    <p><strong>Semester:</strong> {{ $selectedSemester->name }}</p>
    <p><strong>School Year:</strong> {{ $selectedSchoolYear->name }}</p>
    <p><strong>Address:</strong> {{ $selectedAddress }}</p>
    <p><strong>Birthday:</strong> {{ \Carbon\Carbon::parse($selectedBirthDate)->format('F j, Y') }}</p>
    <p><strong>Gender:</strong> {{ $selectedGender }}</p>

    <p><strong>Status:</strong> {{ $selectedStatus }}</p>
    <p><strong>Birthplace:</strong> {{ $selectedBirthPlace }}</p>
    <p><strong>Religion:</strong> {{ $selectedReligion }}</p>
    <p><strong>Father's Name:</strong> {{ $fatherName }}</p>
    <p><strong>Father's Occupation:</strong> {{ $fatherOccupation }}</p>
    <p><strong>Mother's Name:</strong> {{ $motherName }}</p>
    <p><strong>Mother's Occupation:</strong> {{ $motherOccupation }}</p>

    <h4>Previous School Information</h4>
    <p><strong>Previous School Attended:</strong> {{ $previousSchool }}</p>
    <p><strong>Previous School Address:</strong> {{ $previousSchoolAddress }}</p>

    <h4>Contact Information</h4>
    <p><strong>Contact Number:</strong> {{ $contactNumber }}</p>

    <h4>Selected Schedules:</h4>
    <p><strong>Schedule:</strong> 
        {{ $scheduleDetails->section->name ?? 'N/A' }} - 
        {{ $scheduleDetails->subject->subject_code ?? 'N/A' }} 
        ({{ $scheduleDetails->start_time ?? 'N/A' }} - 
        {{ $scheduleDetails->end_time ?? 'N/A' }}, 
        {{ $scheduleDetails->days ?? 'N/A' }}, 
        Room: {{ $scheduleDetails->room ?? 'N/A' }})
    </p>
    <p><strong>Teacher:</strong> {{ $scheduleDetails->teacher->FullName ?? 'N/A' }}</p>

    <!-- Confirm & Submit Button -->
    <form action="{{ route('pre-enrollment.submit') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Confirm & Submit</button>
    </form>
</div>
</main>
@endsection
