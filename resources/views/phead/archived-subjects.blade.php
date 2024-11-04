@extends('layouts.app')

@section('title', 'Archived Subjects')

@section('content')
<main id="main" class="main">
    <section class="archived-subjects-container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-3">Archived Subjects</h5>
                        <a href="{{ route('phead.subjects.index') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left"></i> Back to Subjects
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" id="subjectSearchInput" class="form-control" placeholder="Search Archived Subjects...">
                            </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="archivedSubjects" class="table datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">Subject Code</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Lecture Units</th>
                                        <th scope="col">Lab Units</th>
                                        <th scope="col">Total Units</th>
                                        <th scope="col">Total Hours</th>
                                        <th scope="col">Pre-requisite</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($archivedSubjects as $subject)
                                    <tr>
                                        <td>{{ $subject->subject_code }}</td>
                                        <td>{{ $subject->description }}</td>
                                        <td>{{ $subject->lec_units }}</td>
                                        <td>{{ $subject->lab_units }}</td>
                                        <td>{{ $subject->total_units }}</td>
                                        <td>{{ $subject->total_hours }}</td>
                                        <td>{{ $subject->pre_requisite ?? 'None' }}</td>
                                        <td>
                                            <form action="{{ route('phead.restore', $subject->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bx bx-archive-out me-1"></i> Restore
                                                </button>
                                            </form>
                                            <form action="{{ route('phead.delete', $subject->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this subject? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger ms-2">
                                                    <i class="bx bx-trash me-1"></i> Delete
                                                </button>
                                            </form>
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

<script>
    $(document).ready(function () {
        $('#archivedSubjectSearchInput').on('keyup', function () {
            var searchValue = $(this).val().toLowerCase();
            $('#archivedSubjects tbody tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(searchValue) > -1);
            });
        });
    });
</script>
@endsection