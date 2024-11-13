@extends('layouts.app')

@section('title', 'Pre-Enrollment Schedule')

@section('content')
<main id="main" class="main">
    <!-- Page title -->
    <div class="pagetitle mb-4">
        <h1 class="display-5 fw-bold text-primary">Pre-Enrollment Application</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('newsfeed') }}">Home</a></li>
                <li class="breadcrumb-item active">Pre-Enrollment Form</li>
            </ol>
        </nav>
    </div>
    
<div class="container">
    <h1>Manage Pre-Enrollment Schedules</h1>
    <table id="sectionsTable" class="table table-striped">
        <thead>
            <tr>
                <th>Section</th>
                <th>Status</th>
                <th>Action</th>
                <th>Schedules</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sections as $section)
                <tr>
                    <td>{{ $section->name }}</td>
                    <td>
                        <span class="badge {{ $section->is_locked ? 'badge-danger' : 'badge-success' }}">
                            {{ $section->is_locked ? 'Locked' : 'Unlocked' }}
                        </span>
                    </td>
                    <td>
                        @if($section->is_locked)
                            <button class="btn btn-success btn-sm unlock-section" data-id="{{ $section->id }}">Unlock</button>
                        @else
                            <button class="btn btn-danger btn-sm lock-section" data-id="{{ $section->id }}">Lock</button>
                        @endif
                    </td>
                    <td>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Teacher</th>
                                    <th>Room</th>
                                    <th>Days</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($section->schedules as $schedule)
                                    <tr>
                                        <td>{{ $schedule->subject->subject_code ?? 'N/A' }}</td>
                                        <td>{{ $schedule->teacher->FullName ?? 'N/A' }}</td>
                                        <td>{{ $schedule->room }}</td>
                                        <td>{{ $schedule->days }}</td>
                                        <td>{{ $schedule->time }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</main>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#sectionsTable').DataTable();

    // Lock Section
    $('.lock-section').on('click', function() {
        let sectionId = $(this).data('id');
        $.ajax({
            url: "{{ route('phead.lockSection', '') }}/" + sectionId,
            type: "POST",
            data: {_token: "{{ csrf_token() }}"},
            success: function(response) {
                alert(response.message);
                location.reload();
            }
        });
    });

    // Unlock Section
    $('.unlock-section').on('click', function() {
        let sectionId = $(this).data('id');
        $.ajax({
            url: "{{ route('phead.unlockSection', '') }}/" + sectionId,
            type: "POST",
            data: {_token: "{{ csrf_token() }}"},
            success: function(response) {
                alert(response.message);
                location.reload();
            }
        });
    });
});
</script>
@endpush
