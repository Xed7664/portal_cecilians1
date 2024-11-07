@extends('layouts.app')

@section('title', 'Archived Subjects')

@section('content')
<main id="main" class="main">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Archived Subjects</h1>
            <!-- Back to Active Prospectus Button -->
            <a href="{{ route('phead.prospectus') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left-circle"></i> Back
            </a>
        </div>

        <!-- Search Input for Archived Prospectus -->
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="subjectSearchInput" class="form-control" placeholder="Search Prospectus Subjects...">
                </div>
            </div>
        </div>

        <!-- Archived Prospectus Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="archivedSubjectsTable">
                <thead class="table-light">
                    <tr>
                        <th>Subject Code</th>
                        <th>Description</th>
                        <th>Lec Units</th>
                        <th>Lab Units</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($archivedProspectus as $item)
                        <tr>
                            <td>{{ $item->subject->subject_code }}</td>
                            <td>{{ $item->subject->description }}</td>
                            <td>{{ $item->subject->lec_units }}</td>
                            <td>{{ $item->subject->lab_units }}</td>
                            <td>
                                <form action="{{ route('phead.prospectus.restore', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection

@section('styles')
<style>
    /* Style adjustments for buttons on smaller screens */
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

