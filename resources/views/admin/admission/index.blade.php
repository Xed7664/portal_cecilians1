@extends('layouts.app')

@section('title', 'Admission Management')

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Admissions</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('newsfeed') }}">Home</a></li>
                <li class="breadcrumb-item active">Admission Management</li>
            </ol>
        </nav>
    </div>

    <div class="container">
        <ul class="nav nav-tabs" id="admissionTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="applicants-tab" data-bs-toggle="tab" data-bs-target="#applicants" type="button" role="tab" aria-controls="applicants" aria-selected="true">Admission Applicants</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">Admission Settings</button>
            </li>
        </ul>
        <div class="tab-content mt-4" id="admissionTabsContent">
            <!-- Admission Applicants Tab -->
            <div class="tab-pane fade show active" id="applicants" role="tabpanel" aria-labelledby="applicants-tab">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admissions as $admission)
                            <tr>
                                <td>{{ $admission->id }}</td>
                                <td>{{ $admission->full_name }}</td>
                                <td>{{ $admission->email }}</td>
                                <td>{{ $admission->status }}</td>
                                <td>
                                    <a href="{{ route('admin.admission.review', $admission->id) }}" class="btn btn-primary">Review</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Admission Settings Tab -->
            <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                <div class="mt-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                
<!-- Form for Adding New Admission Settings -->
<form method="POST" action="{{ route('admin.admissions.store') }}" class="p-4 border rounded bg-light">
                        @csrf
                        <div class="mb-3">
                            <label for="schoolYear" class="form-label">School Year</label>
                            <select name="school_year_id" id="schoolYear" class="form-select" required>
                                @foreach($schoolYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <select name="semester_id" id="semester" class="form-select" required>
                                @foreach($semesters as $semester)
                                    <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="open_date" class="form-label">Open Date</label>
                            <input type="date" name="open_date" id="open_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="close_date" class="form-label">Close Date</label>
                            <input type="date" name="close_date" id="close_date" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Admission Setting</button>
                    </form>
                    <table class="table table-striped mt-4">
                        <thead>
                            <tr>
                                <th>Semester</th>
                                <th>School Year</th>
                                <th>Open Date</th>
                                <th>Close Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admissionSettings as $setting)
                                <tr>
                                    <td>{{ $setting->semester->name }}</td>
                                    <td>{{ $setting->schoolYear->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($setting->open_date)->format('F d, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($setting->close_date)->format('F d, Y') }}</td>
                                    <td>
                                        @if ($setting->is_open)
                                            <span class="badge bg-success">Open</span>
                                        @else
                                            <span class="badge bg-danger">Closed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.admissions.toggle', ['semesterId' => $setting->semester_id, 'schoolYearId' => $setting->school_year_id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn {{ $setting->is_open ? 'btn-danger' : 'btn-success' }}">
                                                {{ $setting->is_open ? 'Close' : 'Open' }} Admission
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
</main>
@endsection
