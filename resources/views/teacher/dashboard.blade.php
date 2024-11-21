@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
<main id="main" class="main bg-light py-4">
    <div class="container-fluid">
        <!-- Modern Header Design -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="card-title mb-1" >Welcome, Teacher!</h2>
                        </div>
                        <h2 class="text-muted mt-1 mb-0">Here’s an overview of your teaching data.</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Analytics Cards -->
                <div class="row mb-4">
                    @foreach ([
                        'Enrolled Students' => ['count' => session('student_count', 0), 'icon' => 'bi-people', 'color' => 'primary'],
                        'Enrolled Subjects' => ['count' => session('subject_count', 0), 'icon' => 'bi-book', 'color' => 'success'],
                        'Enrolled Programs' => ['count' => session('program_count', 0), 'icon' => 'bi-collection', 'color' => 'info'],
                        'Enrolled Sections' => ['count' => session('section_count', 0), 'icon' => 'bi-grid-3x3-gap', 'color' => 'warning'],
                    ] as $title => $data)
                        <div class="col-sm-6 col-md-3 mb-4">
                            <div class="card border-left-{{ $data['color'] }} shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-{{ $data['color'] }} text-uppercase mb-1">{{ $title }}</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['count'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="bi {{ $data['icon'] }} fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Grade Analytics -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Grade Analytics</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ([
                                'Average Prelim Grade' => session('avg_prelim', 0),
                                'Average Midterm Grade' => session('avg_midterm', 0),
                                'Average Prefinal Grade' => session('avg_prefinal', 0),
                                'Average Final Grade' => session('avg_final', 0),
                            ] as $title => $average)
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card border-left-success shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{ $title }}</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($average, 2) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right side column -->
            <div class="col-lg-4">
                <!-- Profile Overview -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Profile</h6>
                    </div>
                    <div class="card-body">
                        <h5>{{ session('employee')->FullName ?? 'Teacher Name' }}</h5>
                        <p class="text-muted">{{ session('employee')->position ?? 'Teacher Position' }}</p>
                    </div>
                </div>

                <!-- Schedule Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Your Schedule</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @forelse($schedules as $schedule)
                                <li class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $schedule->subject->subject_code }}</h6>
                                        <small>{{ strtoupper($schedule->days) }}</small>
                                    </div>
                                    <p class="mb-1">{{ $schedule->department->code }} - {{ $schedule->section->name }}</p>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}</small>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">No schedules available.</li>
                            @endforelse
                        </ul>
                        <div class="text-center mt-3">
                            <a href="{{ route('teacher.schedule') }}" class="btn btn-primary btn-sm">See All Schedules</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
$(document).ready(function() {
    // Update last updated time
    function updateLastUpdated() {
        var now = new Date();
        var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
        $('#lastUpdated').text(now.toLocaleDateString('en-US', options));
    }

    updateLastUpdated();
    setInterval(updateLastUpdated, 60000); // Update every minute
});
</script>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.text-gray-300 {
    color: #dddfeb !important;
}
.text-gray-800 {
    color: #5a5c69 !important;
}
.card-title {
    font-size: 1.5rem;
    font-weight: 600;
}
.btn-group .btn {
    font-size: 0.8rem;
    padding: 0.375rem 0.75rem;
}
.text-muted {
    font-size: 0.9rem;
}
</style>
@endsection