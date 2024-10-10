    @extends('layouts.app')

    @section('title', 'Subjects')

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
    </style>
    @endsection

    @section('content')
    <main id="main" class="main">
        <div class="container">
            <h1>Subjects</h1>
            <div class="row mb-4">
            <div class="col-md-8">
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal"><i class="bi bi-plus-circle"></i>
                    Add New Subject
                </button>
                
               
            
            </div>

            <div class="col-md-4 d-flex align-items-end justify-content-md-end mt-3 mt-md-0">
                
                <a href="{{ route('phead.subjects.archived') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-archive"></i> Archived Subjects
                </a>
                
            </div>


        </div>

        

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
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
                        @foreach($subjects as $subject)
                        <tr>
                            <td>{{ $subject->subject_code }}</td>
                            <td>{{ $subject->description }}</td>
                            <td>{{ $subject->room_name }}</td>
                            <td>{{ $subject->day }}</td>
                            <td>{{ $subject->time }}</td>
                            <td>{{ $subject->instructor_name }}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Subject Actions">
                                    <a href="{{ route('phead.subjects.show', $subject) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <form action="{{ route('phead.subjects.archive', $subject->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to archive this subject?')">
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

            <div class="pagination-container">
                {{ $subjects->links() }}
            </div>

        

    <!-- Add Subject Modal -->
    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubjectModalLabel">Add New Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('phead.subjects.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="subject_code" class="form-label">Subject Code</label>
                                <input type="text" class="form-control" id="subject_code" name="subject_code" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <input type="text" class="form-control" id="description" name="description" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="room_name" class="form-label">Room Name</label>
                                <input type="text" class="form-control" id="room_name" name="room_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="day" class="form-label">Day</label>
                                <input type="text" class="form-control" id="day" name="day" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="time" class="form-label">Time</label>
                                <input type="text" class="form-control" id="time" name="time" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="teacher_id" class="form-label">Instructor</label>
                                <select class="form-select" id="teacher_id" name="teacher_id" required>
                                    @if($teachers->isEmpty())
                                        <option value="">No employees available</option>
                                    @else
                                        <option value="">Select Instructor</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->FullName }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="year_level_id" class="form-label">Year Level</label>
                                <select class="form-select" id="year_level_id" name="year_level_id" required>
                                    @foreach($yearLevels as $yearLevel)
                                        <option value="{{ $yearLevel->id }}">{{ $yearLevel->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="semester" class="form-label">Semester</label>
                                <select class="form-select" id="semester" name="semester" required>
                                    <option value="1st Semester">1st Semester</option>
                                    <option value="2nd Semester">2nd Semester</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="lec_units" class="form-label">Lecture Units</label>
                                <input type="number" class="form-control" id="lec_units" name="lec_units" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="lab_units" class="form-label">Lab Units</label>
                                <input type="number" class="form-control" id="lab_units" name="lab_units" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="total_units" class="form-label">Total Units</label>
                                <input type="number" class="form-control" id="total_units" name="total_units" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pre_requisite" class="form-label">Pre-requisite</label>
                                <input type="text" class="form-control" id="pre_requisite" name="pre_requisite">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="total_hours" class="form-label">Total Hours</label>
                                <input type="number" class="form-control" id="total_hours" name="total_hours" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Subject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
        </div>
    </main>
    @endsection

