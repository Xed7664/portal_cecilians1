@extends('layouts.app')

@section('title', 'Subjects Prospectus')

@section('content')
<main id="main" class="main">
    <section class="section">
        <div class="container">
            <h1>Subjects Prospectus</h1>
            <!-- Add Prospectus Button -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#prospectusModal">Add New Prospectus</button>

            <!-- Prospectus Table -->
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>CMO</th>
                        <th>Subject</th>
                        <th>Program</th>
                        <th>Semester</th>
                        <th>Year Level</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prospectuses as $prospectus)
                    <tr>
                        <td>{{ $prospectus->id }}</td>
                        <td>{{ $prospectus->cmo->cmo_number }}</td>
                        <td>{{ $prospectus->subject->name }}</td>
                        <td>{{ $prospectus->program->name }}</td>
                        <td>{{ $prospectus->semester_id }}</td>
                        <td>{{ $prospectus->year_level_id }}</td>
                        <td>
                           
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <!-- Prospectus Modal -->
    <div class="modal fade" id="prospectusModal" tabindex="-1" aria-labelledby="prospectusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="prospectusModalLabel">Add New Prospectus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="cmo_id" class="form-label">CMO</label>
                            <select class="form-select" id="cmo_id" name="cmo_id" required>
                                @foreach($cmos as $cmo)
                                <option value="{{ $cmo->id }}">{{ $cmo->cmo_number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="subject_id" class="form-label">Subject</label>
                            <select class="form-select" id="subject_id" name="subject_id" required>
                                @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="program_id" class="form-label">Program</label>
                            <select class="form-select" id="program_id" name="program_id" required>
                                @foreach($programs as $program)
                                <option value="{{ $program->id }}">{{ $program->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="semester_id" class="form-label">Semester</label>
                            <input type="number" class="form-control" id="semester_id" name="semester_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="year_level_id" class="form-label">Year Level</label>
                            <input type="number" class="form-control" id="year_level_id" name="year_level_id" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
