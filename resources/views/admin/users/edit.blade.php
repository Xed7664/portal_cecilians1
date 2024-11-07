@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')
<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="mb-3 col-md-12 d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.users.student') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Back to Subjects
                </a>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Student Information</h5>
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form id="editForm" action="{{ route('admin.users.student.update', $student->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label for="StudentID" class="col-sm-2 col-form-label">School ID</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="StudentID" name="StudentID" value="{{ $student->StudentID }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="FullName" class="col-sm-2 col-form-label">Full Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="FullName" name="FullName" value="{{ $student->FullName }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="Birthday" class="col-sm-2 col-form-label">Birth Date</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" id="Birthday" name="Birthday" value="{{ $student->Birthday }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="Course" class="col-sm-2 col-form-label">Program</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="Course" name="Course" value="{{ $student->Course }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="Gender" class="col-sm-2 col-form-label">Gender</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="Gender" name="Gender" required>
                                        <option value="male" {{ $student->Gender == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ $student->Gender == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="Status" class="col-sm-2 col-form-label">Status</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="Status" name="Status" required>
                                        <option value="1" {{ $student->isRegistered() ? 'selected' : '' }}>Registered</option>
                                        <option value="0" {{ !$student->isRegistered() ? 'selected' : '' }}>Not Registered</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary">Update Student</button>
                                    
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

   
</main>
@endsection