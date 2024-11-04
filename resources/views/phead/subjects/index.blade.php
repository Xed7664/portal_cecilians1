@extends('layouts.app')

@section('title', 'Subjects')

@section('content')
<main id="main" class="main">
    <section class="subjects-container">
        <div class="row">
            <h1>Subjects</h1>
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                        
                        <div>
                            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                                <i class="bi bi-plus-circle me-1"></i> Add Subject
                            </button>
                            <a href="{{ route('phead.archived-subjects') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-archive me-1"></i> Archived Subjects
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" id="subjectSearchInput" class="form-control" placeholder="Search Subjects...">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="subjects" class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Subject Code</th>
                                        <th scope="col">Description</th>
                                        <th scope="col" class="text-center">Lecture Units</th>
                                        <th scope="col" class="text-center">Lab Units</th>
                                        <th scope="col" class="text-center">Total Units</th>
                                        <th scope="col" class="text-center">Total Hours</th>
                                        <th scope="col">Pre-requisite</th>
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subjects as $subject)
                                    <tr>
                                        <td>{{ $subject->subject_code }}</td>
                                        <td>{{ $subject->description }}</td>
                                        <td class="text-center">{{ $subject->lec_units }}</td>
                                        <td class="text-center">{{ $subject->lab_units }}</td>
                                        <td class="text-center">{{ $subject->total_units }}</td>
                                        <td class="text-center">{{ $subject->total_hours }}</td>
                                        <td>{{ $subject->pre_requisite ?? 'None' }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <button type="button" class="btn btn-sm btn-warning me-2" data-bs-toggle="modal" data-bs-target="#editSubjectModal{{ $subject->id }}">
                                                    <i class="bi bi-pencil-square"></i> Edit
                                                </button>
                                                <form action="{{ route('phead.subjects.archive', $subject->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-archive"></i> Archive
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('phead.subjects.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addSubjectModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>Add New Subject
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="subject_code" class="form-label">Subject Code</label>
                            <input type="text" name="subject_code" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" name="description" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="is_major" class="form-label">Subject Type</label>
                            <select name="is_major" class="form-select">
                                <option value="1">Major</option>
                                <option value="0">Minor</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="pre_requisite" class="form-label">Pre-requisite</label>
                            <input type="text" name="pre_requisite" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="lec_units" class="form-label">Lecture Units</label>
                            <input type="number" name="lec_units" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label for="lab_units" class="form-label">Lab Units</label>
                            <input type="number" name="lab_units" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label for="total_units" class="form-label">Total Units</label>
                            <input type="number" name="total_units" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label for="total_hours" class="form-label">Total Hours</label>
                            <input type="number" name="total_hours" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Add Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Subject Modal -->
@foreach($subjects as $subject)
<div class="modal fade" id="editSubjectModal{{ $subject->id }}" tabindex="-1" aria-labelledby="editSubjectModalLabel{{ $subject->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('phead.subjects.update', $subject->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="editSubjectModalLabel{{ $subject->id }}">
                        <i class="bi bi-pencil-square me-2"></i>Edit Subject
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="subject_code" class="form-label">Subject Code</label>
                            <input type="text" name="subject_code" class="form-control" value="{{ $subject->subject_code }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" name="description" class="form-control" value="{{ $subject->description }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="is_major" class="form-label">Subject Type</label>
                            <select name="is_major" class="form-select">
                                <option value="1" {{ $subject->is_major == 1 ? 'selected' : '' }}>Major</option>
                                <option value="0" {{ $subject->is_major == 0 ? 'selected' : '' }}>Minor</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="pre_requisite" class="form-label">Pre-requisite</label>
                            <input type="text" name="pre_requisite" class="form-control" value="{{ $subject->pre_requisite }}">
                        </div>
                        <div class="col-md-3">
                            <label for="lec_units" class="form-label">Lecture Units</label>
                            <input type="number" name="lec_units" class="form-control" value="{{ $subject->lec_units }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="lab_units" class="form-label">Lab Units</label>
                            <input type="number" name="lab_units" class="form-control" value="{{ $subject->lab_units }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="total_units" class="form-label">Total Units</label>
                            <input type="number" name="total_units" class="form-control" value="{{ $subject->total_units }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="total_hours" class="form-label">Total Hours</label>
                            <input type="number" name="total_hours" class="form-control" value="{{ $subject->total_hours }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Close
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-circle me-2"></i>Update Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@push('styles')
<style>
    .modal-header {
        border-radius: 0.3rem 0.3rem 0 0;
    }

    .modal-content {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .form-label {
        font-weight: 500;
    }

    .btn {
        border-radius: 0.25rem;
    }
</style>
@endpush

<script>
    $(document).ready(function () {
        $('#subjectSearchInput').on('keyup', function () {
            var searchValue = $(this).val().toLowerCase();
            $('#subjects tbody tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(searchValue) > -1);
            });
        });
    });
</script>
@endsection