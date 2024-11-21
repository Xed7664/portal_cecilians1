@extends('layouts.app')

@section('title', 'Program Head Dashboard')

@section('content')
<main id="main" class="main bg-light py-4">
    <div class="container-fluid">
        <!-- Welcome Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                            <div class="text-center text-md-start mb-3 mb-md-0">
                                <h2 class="card-title mb-1">Welcome, {{ $user->name ?? 'Program Head' }}!</h2>
                                <p class="card-text mb-0">Here's an overview of your department's activities.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Overview -->
        <div class="row g-4 mb-4">
            @foreach ([
                'Students' => ['count' => $studentsCount ?? 0, 'icon' => 'bi-people', 'color' => 'primary', 'subtitle' => 'Total Enrolled'],
                'Sections' => ['count' => $sectionsCount ?? 0, 'icon' => 'bi-calendar3', 'color' => 'success', 'subtitle' => 'Active Sections'],
                'Subjects' => ['count' => $subjectsCount ?? 0, 'icon' => 'bi-book', 'color' => 'warning', 'subtitle' => 'Subjects Offered'],
            ] as $title => $data)
                <div class="col-lg-4 col-md-4">
                    <div class="card border-left-{{ $data['color'] }} shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-{{ $data['color'] }} text-uppercase mb-1">{{ $title }}</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($data['count']) }}</div>
                                    <div class="text-muted small">{{ $data['subtitle'] }}</div>
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

        <!-- Quick Actions & Recent Activity -->
        <div class="row">
            <!-- Quick Actions -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-transparent border-0">
                        <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        @foreach ([
                            ['route' => 'phead.students.index', 'icon' => 'bi-person', 'text' => 'Manage Students'],
                            ['route' => 'phead.yearandsection', 'icon' => 'bi-calendar2-week', 'text' => 'Manage Year & Sections'],
                            ['route' => 'phead.schedules.index', 'icon' => 'bi-calendar-event', 'text' => 'Manage Schedules'],
                            ['route' => 'phead.subjects.index', 'icon' => 'bi-book', 'text' => 'Manage Subjects'],
                            ['route' => 'phead.prospectus', 'icon' => 'bi-journal-text', 'text' => 'View Prospectus'],
                        ] as $action)
                            <a href="{{ route($action['route']) }}" class="btn btn-outline-primary w-100 mb-2 text-start">
                                <i class="bi {{ $action['icon'] }} me-2"></i> {{ $action['text'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-transparent border-0">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Activities</h6>
                    </div>
                    <div class="card-body">
                        @forelse($recentActivities ?? [] as $activity)
                            <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                <div class="flex-shrink-0">
                                    <i class="bi {{ $activity->icon ?? 'bi-circle' }} text-{{ $activity->color ?? 'primary' }}"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-0">{{ $activity->description }}</p>
                                    <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-info-circle display-4"></i>
                                <p class="mb-0">No recent activities for today</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.card-title {
    font-size: 1.75rem;
    font-weight: 700;
}
.card-text {
    font-size: 1rem;
}
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
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
.btn-outline-primary {
    color: #4e73df;
    border-color: #4e73df;
}
.btn-outline-primary:hover {
    color: #fff;
    background-color: #4e73df;
    border-color: #4e73df;
}
</style>
@endsection

