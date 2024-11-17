@extends('layouts.app') 
@section('title', 'Pre-Enrollment Settings')

@section('content')
<main id="main" class="main">
<section class="container">
    <div class="pagetitle">
        <h1>Pre-enrollment Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('newsfeed') }}">Home</a></li>
                <li class="breadcrumb-item active">Academic School Year</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="container">
      

        <!-- Error Alert -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

      <!-- Active Academic Year and Semester -->
<div class="card border-light shadow-sm p-4 mb-4 rounded text-center">
    <div class="card-body">
        
        <!-- Department Display with Icon -->
        <div class="d-flex justify-content-center align-items-center mb-4">
            <i class="bi bi-building-fill text-primary me-2" style="font-size: 1.5rem;"></i> <!-- Professional, modern icon for department -->
            <h5 class="text-secondary mb-0">Department: <span class="text-dark fw-bold">{{ auth()->user()->employee->department->name ?? 'N/A' }}</span></h5>
        </div>

        <!-- Academic Year and Semester Display with Icons -->
        <div class="d-flex justify-content-center align-items-center mb-3">
            <i class="bi bi-calendar-event-fill text-primary me-2" style="font-size: 1.5rem;"></i> <!-- Icon for academic year -->
            <h5 class="card-title text-primary mb-0">Active Academic Year and Semester</h5>
        </div>
        <p class="card-text">
            <strong>School Year:</strong> {{ $activeEnrollmentSetting->schoolYear->name }} <br>
            <strong>Semester:</strong> {{ $activeEnrollmentSetting->semester->name }}
        </p>
    </div>
</div>


        <!-- Year Level Sections Table -->
          <!-- Year Level Sections Table -->
          @foreach(['1st Year' => 1, '2nd Year' => 2, '3rd Year' => 3, '4th Year' => 4] as $yearLabel => $yearLevelId)
            <div class="card mb-4 shadow-sm border-light">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ $yearLabel }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Section Name</th>
                                    <th>Max Enrollment</th>
                                    <th>Enrolled Count</th>
                                    <th>Lock Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @php
                                    $sectionsForYear = $sections->filter(function($section) use ($yearLevelId) {
                                        return $section->schedules->where('year_level_id', $yearLevelId)->isNotEmpty();
                                    });
                                @endphp

                                @if($sectionsForYear->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No sections available for {{ $yearLabel }}.</td>
                                    </tr>
                                @else
                                    @foreach($sectionsForYear as $section)
                                        @php
                                            $lockStatus = $section->yearLevelLocks->firstWhere('year_level_id', $yearLevelId);
                                        @endphp
                                        <tr>
                                            <td>{{ $section->name }}</td>
                                            <td>{{ $section->max_enrollment }}</td>
                                            <td>{{ $section->year_level_enrollment_counts[$yearLevelId] ?? 0 }}</td>
                                            <td class="{{ $lockStatus && $lockStatus->is_locked ? 'text-danger' : 'text-success' }}">
                                                {{ $lockStatus && $lockStatus->is_locked ? 'Locked' : 'Unlocked' }}
                                            </td>
                                            <td>
                                                <!-- View Schedules -->
                                                <a href="{{ route('phead.viewSchedules', ['section' => $section->id, 'year_level_id' => $yearLevelId]) }}" 
                                                    class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-calendar-event"></i> View Schedules
                                                </a>

                                                <!-- Lock/Unlock -->
                                                <button 
                                                    class="btn btn-sm btn-{{ $lockStatus && $lockStatus->is_locked ? 'danger' : 'success' }} toggle-lock-btn" 
                                                    data-section-id="{{ $section->id }}" 
                                                    data-year-level-id="{{ $yearLevelId }}">
                                                    {{ $lockStatus && $lockStatus->is_locked ? 'Unlock' : 'Lock' }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach

    </div>


    <!-- Toast Container -->
    <div id="toastContainer" class="position-fixed" style="top: 60px; right: 20px; z-index: 1055;"></div>
</section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toastContainer = document.getElementById('toastContainer');

    document.querySelectorAll('.toggle-lock-btn').forEach(button => {
        button.addEventListener('click', function () {
            const sectionId = this.getAttribute('data-section-id');
            const yearLevelId = this.getAttribute('data-year-level-id');
            const button = this;

            // Optimistic UI update
            const row = button.closest('tr');
            const lockStatusCell = row.querySelector('td:nth-child(4)'); 
            const currentLockState = button.textContent.trim() === 'Lock';

            button.textContent = currentLockState ? 'Unlock' : 'Lock';
            button.className = `btn btn-${currentLockState ? 'danger' : 'success'} toggle-lock-btn`;
            lockStatusCell.textContent = currentLockState ? 'Locked' : 'Unlocked';

            displayToastMessage(`Toggling ${currentLockState ? 'Locked' : 'Unlocked'}...`);

            fetch(`/phead/section/${sectionId}/toggle-lock/${yearLevelId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    button.textContent = currentLockState ? 'Lock' : 'Unlock';
                    button.className = `btn btn-${currentLockState ? 'success' : 'danger'} toggle-lock-btn`;
                    lockStatusCell.textContent = currentLockState ? 'Unlocked' : 'Locked';
                    displayToastMessage('Failed to toggle lock. Please try again.');
                } else {
                    displayToastMessage(data.message);
                }
            })
            .catch(error => {
                button.textContent = currentLockState ? 'Lock' : 'Unlock';
                button.className = `btn btn-${currentLockState ? 'success' : 'danger'} toggle-lock-btn`;
                lockStatusCell.textContent = currentLockState ? 'Unlocked' : 'Locked';
                console.error('Error:', error);
                displayToastMessage('An error occurred. Please try again.');
            });
        });
    });

    function displayToastMessage(message) {
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-primary border-0 show';
        toast.role = 'alert';
        toast.ariaLive = 'assertive';
        toast.ariaAtomic = 'true';

        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;

        toastContainer.appendChild(toast);
        setTimeout(() => { toast.remove(); }, 2000);
    }
});
</script>
@endsection
