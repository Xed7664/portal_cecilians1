@extends('layouts.app')
@section('title', 'Schedule Details')

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Pre-enrollment Management for Program Heads</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('newsfeed') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('phead.preenrollment') }}">Academic School Year</a></li>
                <li class="breadcrumb-item active">Schedule Details</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="container">
        <!-- Display Program/Department of the Program Head -->

        <!-- Schedule Section -->
<div class="card border-light shadow-sm mb-4 text-center">
    <div class="card-body">
 <!-- Department Display with Icon -->
<h5 class="text-secondary d-flex justify-content-center align-items-center mb-1" style="padding-bottom: 10px;">
    <i class="bi bi-building text-primary me-2"></i> Department: 
    <span class="text-dark fw-bold ms-2">{{ optional(auth()->user()->employee->department)->name ?? 'N/A' }}</span>
</h5>

<!-- Section Title -->
<h3 class="card-title text-primary mb-2" style="padding-bottom: 10px;">
    <i class="bi bi-calendar-event-fill me-2"></i> Schedules for Section:
    <span class="text-dark fw-bold">{{ $section->name ?? 'N/A' }}</span>
</h3>

<!-- Academic Year and Semester -->
<p class="text-secondary mb-2" style="padding-bottom: 10px;">
    <i class="bi bi-calendar2-range me-2"></i>
    <strong>School Year:</strong> {{ optional($activeEnrollmentSetting->schoolYear)->name ?? 'N/A' }} 
    <span class="mx-2">|</span> 
    <strong>Semester:</strong> {{ optional($activeEnrollmentSetting->semester)->name ?? 'N/A' }}
</p>


                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Subject Code</th>
                                <th>Teacher</th>
                                <th>Room</th>
                                <th>Days</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach($schedules as $schedule)
                                <tr>
                                    <td>{{ optional($schedule->subject)->subject_code ?? '-' }}</td>
                                    <td>{{ optional($schedule->teacher)->FullName ?? 'TBA' }}</td>
                                    <td>{{ $schedule->room ?? 'TBA' }}</td>
                                    <td>{{ $schedule->days ?? '-' }}</td>
                                    <td>{{ $schedule->time ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
