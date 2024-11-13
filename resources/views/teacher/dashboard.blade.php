@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
<main id="main" class="main">
    <section class="section py-4">
        <div class="container-fluid">
            <!-- Page Header -->
      
            <!-- News Feed Style Layout -->
            <div class="row">
                <!-- Feed Section -->
                <div class="col-lg-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Welcome to Dashboard ! {{ session('employee')->FullName ?? 'Teacher Name' }} </h5>
                        </div>
                    </div>
                       <!-- Analytics Cards -->
            <div class="row mb-5">
                @foreach ([
                    'Enrolled Students' => session('student_count', 0),
                    'Enrolled Subjects' => session('subject_count', 0),
                    'Enrolled Programs' => session('program_count', 0),
                    'Enrolled Sections' => session('section_count', 0),
                ] as $title => $count)
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title">{{ $title }}</h6>
                                <p class="display-4 text-primary">{{ $count }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

                <!-- Grade Analytics -->
                <div class="row mb-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Grade Analytics</h5>
                            <div class="row">
                                @foreach ([
                                    'Average Prelim Grade' => session('avg_prelim', 0),
                                    'Average Midterm Grade' => session('avg_midterm', 0),
                                    'Average Prefinal Grade' => session('avg_prefinal', 0),
                                    'Average Final Grade' => session('avg_final', 0),
                                ] as $title => $average)
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <h6>{{ $title }}</h6>
                                                <p class="display-6 text-success">{{ number_format($average, 2) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- display the next content here -->
       </div>

                <!-- Profile Overview and Schedule Section -->
                <div class="col-lg-4">
                    <!-- Profile Card -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Profile Overview</h5>
                            <p>{{ session('employee')->FullName ?? 'Teacher Name' }}</p>
                            <p class="text-muted">{{ session('employee')->position ?? 'Teacher Position' }}</p>
                        </div>
                    </div>

                <!-- Schedule Card --> 
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Your Schedule</h5>

                        <ul class="list-group list-group-flush mt-3">
                            @forelse($schedules as $schedule)
                                <li class="list-group-item py-3 px-2 border-top border-bottom d-flex align-items-center">
                                    <div class="flex-grow-1 text-truncate">
                                        <strong>{{ $schedule->subject->subject_code }}</strong> - 
                                        {{ $schedule->department->code }} - 
                                        {{ $schedule->section->name }} - 
                                        {{ strtoupper($schedule->days) }}
                                    </div>
                                    <div class="text-muted small text-nowrap ms-2">
                                        <span>{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}</span>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item py-3 text-center text-muted border-top border-bottom">No schedules available.</li>
                            @endforelse
                        </ul>

                        <div class="mt-3 text-center">
                            <a href="{{ route('teacher.schedule') }}" class="btn btn-outline-primary btn-sm">See All</a>
                        </div>
                    </div>
                </div>



                </div>
            </div>
        </div>
    </section>
</main>

@endsection
