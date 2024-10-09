@extends('layouts.app')

@section('title', 'Subject Details')

@section('content')
<main id="main" class="main">
    <div class="container">
        <h1 class="mb-4">Subject Details</h1>
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $subject->subject_code }} - {{ $subject->description }}</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Room:</strong> {{ $subject->room_name ?? 'N/A' }}</p>
                        <p><strong>Day:</strong> {{ $subject->day ?? 'N/A' }}</p>
                        <p><strong>Time:</strong> {{ $subject->time ?? 'N/A' }}</p>
                        <p><strong>Instructor:</strong> {{ $subject->instructor_name ?? 'N/A' }}</p>
                        <p><strong>Year Level:</strong> {{ $subject->yearLevel->name ?? 'N/A' }}</p>
                        <p><strong>Semester:</strong> {{ $subject->semester ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Lecture Units:</strong> {{ $subject->lec_units ?? 'N/A' }}</p>
                        <p><strong>Lab Units:</strong> {{ $subject->lab_units ?? 'N/A' }}</p>
                        <p><strong>Total Units:</strong> {{ $subject->total_units ?? 'N/A' }}</p>
                        <p><strong>Pre-requisite:</strong> {{ $subject->pre_requisite ?: 'None' }}</p>
                        <p><strong>Total Hours:</strong> {{ $subject->total_hours ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <a href="{{ route('phead.subjects.index') }}" class="btn btn-primary">Back to Subjects</a>
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateSubjectModal">
                Update Subject
            </button>
        </div>
    </div>

    <!-- Update Subject Modal -->
    <div class="modal fade" id="updateSubjectModal" tabindex="-1" aria-labelledby="updateSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateSubjectModalLabel">Update Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('phead.subjects.update', $subject->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="subject_code" class="form-label">Subject Code</label>
                                <input type="text" class="form-control" id="subject_code" name="subject_code" value="{{ $subject->subject_code }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <input type="text" class="form-control" id="description" name="description" value="{{ $subject->description }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="room_name" class="form-label">Room Name</label>
                                <input type="text" class="form-control" id="room_name" name="room_name" value="{{ $subject->room_name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="day" class="form-label">Day</label>
                                <input type="text" class="form-control" id="day" name="day" value="{{ $subject->day }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="time" class="form-label">Time</label>
                                <input type="text" class="form-control" id="time" name="time" value="{{ $subject->time }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="teacher_id" class="form-label">Instructor</label>
                                <select class="form-select" id="teacher_id" name="teacher_id" required>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ $subject->teacher_id == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->FullName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="year_level_id" class="form-label">Year Level</label>
                                <select class="form-select" id="year_level_id" name="year_level_id" required>
                                    @foreach($yearLevels as $yearLevel)
                                        <option value="{{ $yearLevel->id }}" {{ $subject->year_level_id == $yearLevel->id ? 'selected' : '' }}>
                                            {{ $yearLevel->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="semester" class="form-label">Semester</label>
                                <select class="form-select" id="semester" name="semester" required>
                                    <option value="1st Semester" {{ $subject->semester == '1st Semester' ? 'selected' : '' }}>1st Semester</option>
                                    <option value="2nd Semester" {{ $subject->semester == '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="lec_units" class="form-label">Lecture Units</label>
                                <input type="number" class="form-control" id="lec_units" name="lec_units" value="{{ $subject->lec_units }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="lab_units" class="form-label">Lab Units</label>
                                <input type="number" class="form-control" id="lab_units" name="lab_units" value="{{ $subject->lab_units }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="total_units" class="form-label">Total Units</label>
                                <input type="number" class="form-control" id="total_units" name="total_units" value="{{ $subject->total_units }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pre_requisite" class="form-label">Pre-requisite</label>
                                <input type="text" class="form-control" id="pre_requisite" name="pre_requisite" value="{{ $subject->pre_requisite }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="total_hours" class="form-label">Total Hours</label>
                                <input type="number" class="form-control" id="total_hours" name="total_hours" value="{{ $subject->total_hours }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Subject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection