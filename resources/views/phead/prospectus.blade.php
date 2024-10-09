@extends('layouts.app')

@section('title', 'Department Prospectus')

@section('content')
<main id="main" class="main">
    <div class="container">
        <h1 class="mb-4">{{ $department->code }} Prospectus</h1>

        <div class="row mb-4">
            <div class="col-md-8">
                <!-- Add Subject to Prospectus Form -->
                <form action="{{ route('phead.prospectus.store') }}" method="POST" class="d-flex align-items-end">
                    @csrf
                    <div class="form-group flex-grow-1 me-2">
                        
                        <label for="subject_id" class="form-label">Add Subject to Prospectus:</label>
                        <select name="subject_id" id="subject_id" class="form-select" required>
                            @if($subjects->isEmpty())
                                <option value="">No subjects available to add</option>
                            @else
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject_code }} - {{ $subject->description }}</option>
                                @endforeach
                            @endif
                        </select>
                      
                    </div>
                    <button type="submit" class="btn btn-outline-primary" {{ $subjects->isEmpty() ? 'disabled' : '' }}>
                        <i class="bi bi-plus-circle"></i> Add
                    </button>
                   
                </form>
                
            </div>
           
            <div class="col-md-4 d-flex align-items-end justify-content-md-end mt-3 mt-md-0">
                <!-- View Archived Subjects Button -->
                <a href="{{ route('phead.prospectus.archived') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-archive"></i> Archived
                </a>
            </div>
        </div>

        <!-- Prospectus Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Subject Code</th>
                        <th>Description</th>
                        <th>Lec Units</th>
                        <th>Lab Units</th>
                        <th>Year Level</th>
                        <th>Semester</th>
                        <th>Pre-requisites</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prospectus as $item)
                        @if($item->subject)
                            <tr>
                                <td>{{ $item->subject->subject_code }}</td>
                                <td>{{ $item->subject->description }}</td>
                                <td>{{ $item->subject->lec_units }}</td>
                                <td>{{ $item->subject->lab_units }}</td>
                                <td>{{ $item->subject->yearLevel->name ?? 'N/A' }}</td>
                                <td>{{ $item->subject->semester }}</td>
                                <td>{{ $item->subject->pre_requisite }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Subject Actions">
                                        <button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>
                                        <form action="{{ route('phead.prospectus.archive', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-archive"></i> Archive
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Edit Prospectus Item</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('phead.prospectus.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="subject_code_{{ $item->id }}" class="form-label">Subject Code:</label>
                                                    <input type="text" class="form-control" id="subject_code_{{ $item->id }}" name="subject_code" value="{{ $item->subject->subject_code }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="subject_description_{{ $item->id }}" class="form-label">Description:</label>
                                                    <input type="text" class="form-control" id="subject_description_{{ $item->id }}" name="description" value="{{ $item->subject->description }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="subject_lec_units_{{ $item->id }}" class="form-label">Lec Units:</label>
                                                    <input type="number" class="form-control" id="subject_lec_units_{{ $item->id }}" name="lec_units" value="{{ $item->subject->lec_units }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="subject_lab_units_{{ $item->id }}" class="form-label">Lab Units:</label>
                                                    <input type="number" class="form-control" id="subject_lab_units_{{ $item->id }}" name="lab_units" value="{{ $item->subject->lab_units }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="subject_year_level_{{ $item->id }}" class="form-label">Year Level:</label>
                                                    <select class="form-select" id="subject_year_level_{{ $item->id }}" name="year_level_id">
                                                        @foreach($yearLevels as $yearLevel)
                                                            <option value="{{ $yearLevel->id }}" {{ $item->subject->year_level_id == $yearLevel->id ? 'selected' : '' }}>
                                                                {{ $yearLevel->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="subject_semester_{{ $item->id }}" class="form-label">Semester:</label>
                                                    <input type="text" class="form-control" id="subject_semester_{{ $item->id }}" name="semester" value="{{ $item->subject->semester }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="subject_pre_requisite_{{ $item->id }}" class="form-label">Pre-requisites:</label>
                                                    <input type="text" class="form-control" id="subject_pre_requisite_{{ $item->id }}" name="pre_requisite" value="{{ $item->subject->pre_requisite }}">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection

@section('styles')
<style>
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

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize Bootstrap modals
        $('.modal').modal({
            keyboard: false,
            backdrop: 'static'
        });

        // Handle edit button click
        $('.edit-btn').on('click', function() {
            var targetModal = $(this).data('bs-target');
            $(targetModal).modal('show');
        });
    });
</script>
@endsection