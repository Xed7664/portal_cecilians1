@extends('layouts.app')

@section('title', 'Schedules')

@section('content')
<main id="main" class="main">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Schedules</h1>
            <!-- Back Button -->
            <a href="{{ route('phead.dashboard') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
            </a>
        </div>

        <!-- Search Input for Schedules -->
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="subjectSearchInput" class="form-control" placeholder="Search Schedules...">
                </div>
            </div>
        </div>

        <!-- Schedules Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="schedulesTable">
                <thead class="table-light">
                    <tr>
                        <th>Schedule Code</th>
                        <th>Description</th>
                        <th>Time</th>
                        <th>Day</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example Data -->
                    <tr>
                        <td>SC-001</td>
                        <td>Introduction to Computing</td>
                        <td>10:00 AM - 12:00 PM</td>
                        <td>Monday, Wednesday</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary">View</button>
                        </td>
                    </tr>
                    <!-- Dynamically loaded data here -->
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection

@section('styles')
<style>
    /* Responsive button styling */
    .btn-group .btn {
        border-radius: 0;
    }
    .btn-group .btn:first-child {
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }
    .btn-group .btn:last-child {
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
    }
    @media (max-width: 767.98px) {
        .btn-group {
            display: flex;
            flex-direction: column;
            width: 100%;
        }
        .btn-group .btn {
            border-radius: 0.25rem;
            margin-bottom: 0.25rem;
            width: 100%;
        }
    }
</style>
@endsection
