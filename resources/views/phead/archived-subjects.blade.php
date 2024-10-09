@extends('layouts.app')

@section('title', 'Archived Subjects')

@section('styles')
<style>
    .pagination-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
    }
    .pagination {
        display: flex;
        list-style-type: none;
        padding: 0;
        margin: 0;
    }
    .page-item {
        margin: 0 5px;
    }
    .page-link {
        display: block;
        padding: 5px 10px;
        text-decoration: none;
        border: 1px solid #dee2e6;
        color: #007bff;
        background-color: #fff;
    }
    .page-link:hover {
        background-color: #e9ecef;
    }
    .page-item.active .page-link {
        background-color: #007bff;
        color: #fff;
        border-color: #007bff;
    }
    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        cursor: auto;
        background-color: #fff;
        border-color: #dee2e6;
    }
    @media (max-width: 768px) {
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }
        .page-item {
            margin: 2px;
        }
    }
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
        }
        .btn-group .btn {
            border-radius: 0.25rem;
            margin-bottom: 0.25rem;
        }
    }
</style>
@endsection

@section('content')
<main id="main" class="main">
    <div class="container">
        <h1 class="mb-4">Archived Subjects</h1>
        
        <div class="row mb-4">
            <div class="col-md-12 d-flex justify-content-between align-items-center">
                <a href="{{ route('phead.subjects.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Back to Subjects
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Subject Code</th>
                        <th>Description</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Instructor</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($archivedSubjects as $subject)
                    <tr>
                        <td>{{ $subject->subject_code }}</td>
                        <td>{{ $subject->description }}</td>
                        <td>{{ $subject->room_name }}</td>
                        <td>{{ $subject->day }}</td>
                        <td>{{ $subject->time }}</td>
                        <td>{{ $subject->instructor_name }}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Subject Actions">
                                <form action="{{ route('phead.subjects.restore', $subject->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Are you sure you want to restore this subject?')">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                    </button>
                                </form>
                                <form action="{{ route('phead.subjects.destroy', $subject->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to permanently delete this subject?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            {{ $archivedSubjects->links() }}
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize Bootstrap tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
@endsection