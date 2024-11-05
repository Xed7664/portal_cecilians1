@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<main id="main" class="main">
    <section class="section">
        <div class="container-fluid py-4">
            <!-- Welcome Card -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-lg">
                        <div class="card-body text-center">
                            <h2 class="mb-3">Welcome, {{ $user->name ?? 'Program Head' }}!</h2>
                            <p class="text-muted">
                                Here's an overview of your department's activities and quick access to essential management tools.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Overview -->
            <div class="row g-4 mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 text-white bg-primary shadow-sm border-0 hover-shadow transition-all">
                        <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                            <i class="bx bx-user display-4 mb-3"></i>
                            <h4 class="fw-bold mb-2">Students</h4>
                            <p class="h2 mb-0">{{ number_format($studentsCount ?? 0) }}</p>
                            <small>Total Enrolled</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 text-white bg-success shadow-sm border-0 hover-shadow transition-all">
                        <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                            <i class="bx bx-calendar display-4 mb-3"></i>
                            <h4 class="fw-bold mb-2">Sections</h4>
                            <p class="h2 mb-0">{{ number_format($sectionsCount ?? 0) }}</p>
                            <small>Active Sections</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 text-white bg-warning shadow-sm border-0 hover-shadow transition-all">
                        <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                            <i class="bx bx-book display-4 mb-3"></i>
                            <h4 class="fw-bold mb-2">Subjects</h4>
                            <p class="h2 mb-0">{{ number_format($subjectsCount ?? 0) }}</p>
                            <small>Subjects Offered</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 text-white bg-info shadow-sm border-0 hover-shadow transition-all">
                        <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                            <i class="bx bx-book-open display-4 mb-3"></i>
                            <h4 class="fw-bold mb-2">Prospectus</h4>
                            <p class="h2 mb-0">{{ number_format($prospectusCount ?? 0) }}</p>
                            <small>Total Programs</small>
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
                            <a href="{{ route('phead.students.index') }}" class="btn btn-primary d-flex align-items-center justify-content-center gap-2">
                                <i class="bx bx-user"></i>
                                Manage Students
                            </a>
                            <a href="{{ route('phead.yearandsection') }}" class="btn btn-success d-flex align-items-center justify-content-center gap-2">
                                <i class="bx bx-calendar"></i>
                                Manage Year & Sections
                            </a>
                            <a href="{{ route('phead.subjects.index') }}" class="btn btn-warning d-flex align-items-center justify-content-center gap-2">
                                <i class="bx bx-book"></i>
                                Manage Subjects
                            </a>
                            <a href="{{ route('phead.prospectus') }}" class="btn btn-info d-flex align-items-center justify-content-center gap-2">
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
                                        <p class="mb-0">No recent activities to display</p>
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
    }

    .btn i {
        font-size: 1.2rem;
    }

    .card-header {
        font-weight: 600;
    }

    .display-4 {
        font-size: 2.5rem;
    }
</style>
@endsection