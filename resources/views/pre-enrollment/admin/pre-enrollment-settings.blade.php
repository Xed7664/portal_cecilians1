@extends('layouts.app')
@section('title', 'Pre-Enrollment Settings')

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
    <h1>Pre-Enrollment Settings</h1>

        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('newsfeed') }}">Home</a></li>
                <li class="breadcrumb-item active">Pre-Enrollment Settings</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="container">
      
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.pre-enrollment.storeSettings') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="semester_id" class="form-label">Semester</label>
                    <select class="form-control" id="semester_id" name="semester_id" required>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="school_year_id" class="form-label">School Year</label>
                    <select class="form-control" id="school_year_id" name="school_year_id" required>
                        @foreach($schoolYears as $schoolYear)
                            <option value="{{ $schoolYear->id }}">{{ $schoolYear->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="open_date" class="form-label">Open Date</label>
                    <input type="date" class="form-control" id="open_date" name="open_date" required>
                </div>
                <div class="col-md-6">
                    <label for="close_date" class="form-label">Close Date</label>
                    <input type="date" class="form-control" id="close_date" name="close_date" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>

        <hr>

        <h3>Existing Pre-Enrollment Settings</h3>
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
                @foreach ($preEnrollmentSettings as $setting)
                    <tr>
                        <td>{{ $setting->semester->name }}</td>
                        <td>{{ $setting->schoolYear->name }}</td>
                        <td>{{ $setting->open_date }}</td>
                        <td>{{ $setting->close_date }}</td>
                        <td>
                            @if ($setting->is_open)
                                <span class="badge bg-success">Open</span>
                            @else
                                <span class="badge bg-danger">Closed</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.pre-enrollment.toggle', ['semesterId' => $setting->semester_id, 'schoolYearId' => $setting->school_year_id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn {{ $setting->is_open ? 'btn-danger' : 'btn-success' }}">
                                    {{ $setting->is_open ? 'Close' : 'Open' }} Pre-Enrollment
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</main>
@endsection
