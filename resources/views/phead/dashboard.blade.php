@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<main id="main" class="main">
    <section class="section">
        <div class="container-fluid py-4">
            <!-- Welcome Card -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-lg bg-primary text-white hover-shadow">
                        <div class="card-body p-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                                <div class="text-center text-md-start mb-3 mb-md-0">
                                    <h2 class="display-6 fw-bold mb-1">Welcome, {{ $user->name ?? 'Program Head' }}!</h2>
                                    <p class="lead mb-0">
                                        Here's an overview of your department's activities.
                                    </p>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Overview -->
            <div class="row g-4 mb-4">
                <div class="col-lg-4 col-md-4">
                    <div class="card h-100 text-white bg-white shadow-sm border-0 hover-shadow transition-all">
                        <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                            <i class="bx bx-user display-3 mb-3 text-primary"></i>
                            <h3 class="fw-bold mb-2 text-primary">Students</h3>
                            <p class="h1 mb-0 text-primary">{{ number_format($studentsCount ?? 0) }}</p>
                            <small class="text-muted">Total Enrolled</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4">
                    <div class="card h-100 text-white bg-white shadow-sm border-0 hover-shadow transition-all">
                        <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                            <i class="bx bx-calendar display-3 mb-3 text-success"></i>
                            <h3 class="fw-bold mb-2 text-success">Sections</h3>
                            <p class="h1 mb-0 text-success">{{ number_format($sectionsCount ?? 0) }}</p>
                            <small class="text-muted">Active Sections</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4">
                    <div class="card h-100 text-white bg-white shadow-sm border-0 hover-shadow transition-all">
                        <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                            <i class="bx bx-book display-3 mb-3 text-warning"></i>
                            <h3 class="fw-bold mb-2 text-warning">Subjects</h3>
                            <p class="h1 mb-0 text-warning">{{ number_format($subjectsCount ?? 0) }}</p>
                            <small class="text-muted">Subjects Offered</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Recent Activity -->
            <div class="row">
                <!-- Quick Actions -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm border-0 rounded-lg h-100">
                        <div class="card-header bg-transparent border-0 py-3">
                            <h5 class="mb-0 d-flex align-items-center">
                                <i class="bx bx-lightning text-primary me-2"></i>
                                Quick Actions
                            </h5>
                        </div>
                        <div class="card-body d-grid gap-3">
                           
                        
                            <a href="{{ route('phead.students.index') }}" class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2">
                                <i class="bx bx-user"></i>
                                Manage Students
                            </a>
                            <a href="{{ route('phead.yearandsection') }}" class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2">
                                <i class="bx bx-calendar"></i>
                                Manage Year & Sections
                            </a>
                            <a href="{{ route('phead.schedules.index') }}" class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2">
                                <i class="bx bx-calendar-event"></i>
                                Manage Schedules
                            </a>
                            <a href="{{ route('phead.subjects.index') }}" class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2">
                                <i class="bx bx-book"></i>
                                Manage Subjects
                            </a>
                            <a href="{{ route('phead.prospectus') }}" class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2">
                                <i class="bx bx-book-open"></i>
                                View Prospectus
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm border-0 rounded-lg h-100">
                        <div class="card-header bg-transparent border-0 py-3">
                            <h5 class="mb-0 d-flex align-items-center">
                                <i class="bx bx-history text-primary me-2"></i>
                                Recent Activities
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                @forelse($recentActivities ?? [] as $activity)
                                    <div class="timeline-item pb-3 mb-3 border-bottom">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bx {{ $activity->icon ?? 'bx-circle' }} text-{{ $activity->color ?? 'primary' }} me-2"></i>
                                            <span class="text-muted small">{{ $activity->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="mb-0">{{ $activity->description }}</p>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-4">
                                        <i class="bx bx-info-circle display-4"></i>
                                        <p class="mb-0">No recent activities for today</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
    .card {
        transition: all 0.3s ease;
    }

    .hover-shadow:hover {
        transform: translateY(-5px);
    }

    .transition-all {
        transition: all 0.3s ease;
    }

    .timeline-item:last-child {
        border-bottom: none !important;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-outline-primary {
        position: relative;
        z-index: 1;
        overflow: hidden;
    }
    .btn-outline-primary:hover {
        color: #fff;
    }
    .btn-outline-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
        z-index: -1;
    }
    .btn-outline-primary:hover::before {
        left: 100%;
    }

    .card-header {
        font-weight: 600;
    }

    .display-4 {
        font-size: 2.5rem;
    }
    .card.bg-primary {
        background: linear-gradient(135deg, #871616 0%, #224abe 100%);
        transition: all 0.3s ease;
    }
    .card.bg-primary:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }
    .display-6 {
        font-size: 2.5rem;
    }
    .lead {
        font-size: 1.1rem;
    }
</style>
@endsection