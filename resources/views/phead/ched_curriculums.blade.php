@extends('layouts.app')

@section('title', 'Prospectus')

@section('content')
<main id="main" class="main">
    <section class="section">
<div class="container">
    <h1 class="mb-4">CHED Curriculums</h1>
    <div class="table-responsive">
        <table id="chedCurriculumsTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Title</th>
                    <th>Effectivity Year</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($chedCurriculums as $index => $curriculum)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $curriculum->title }}</td>
                    <td>{{ $curriculum->effectivity_year }}</td>
                    <td>{{ $curriculum->archive_status ? 'Archived' : 'Active' }}</td>
                    <td>
                        <a href="{{ route('phead.prospectus', $curriculum->id) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-book"></i> View Prospectus
                        </a>
                    </td>



                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#chedCurriculumsTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            columnDefs: [{ orderable: false, targets: [4] }]
        });
    });
</script>
@endpush
