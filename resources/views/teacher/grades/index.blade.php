@extends('layouts.app')

@section('title', 'Grade')

@section('content')
<main id="main" class="main">
    <section class="section profile">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <ul class="nav nav-tabs nav-tabs-bordered border-top pt-3 border-light-subtle border-opacity-10 border-1" role="tablist">
                        <li class="nav-item active" role="presentation">
                            <a class="nav-link" type="button" tabindex="-1">
                                <i class="bx bx-book-bookmark me-1"></i> Manage Grades
                            </a>
                        </li>
                        <li class="nav-item active" role="presentation">
                            <a class="nav-link" type="button" tabindex="-1">
                                <i class="bx bx-building me-1"></i> My Departments
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" type="button" tabindex="-1">
                                <i class="bx bx-group me-1"></i> My Students
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" type="button" tabindex="-1">
                                <i class="bx bx-book-open me-1"></i> My Subjects
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <form method="GET" action="{{ route('teacher.grades.index') }}">
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <label for="school_year_id">School Year</label>
                        <select name="school_year_id" id="school_year_id" class="form-control">
                            <option value="">Select School Year</option>
                            @foreach($schoolYears as $schoolYear)
                                <option value="{{ $schoolYear->id }}" {{ (old('school_year_id', $selectedSchoolYearId) == $schoolYear->id) ? 'selected' : '' }}>
                                    {{ $schoolYear->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_year_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6" id="semester-container" style="display:none;">
                        <label for="semester_id">Semester</label>
                        <select name="semester_id" id="semester_id" class="form-control">
                            <option value="">Select Semester</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}" {{ (old('semester_id', $selectedSemesterId) == $semester->id) ? 'selected' : '' }}>
                                    {{ $semester->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('semester_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label for="department" class="form-label">Filter by Department</label>
                        <select name="department_id" id="department" class="form-select">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ (old('department_id', $selectedDepartmentId) == $department->id) ? 'selected' : '' }}>
                                    {{ $department->code }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label for="section" class="form-label">Filter by Section</label>
                        <select name="section_id" id="section" class="form-select">
                            <option value="">Select Section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ (old('section_id', $selectedSectionId) == $section->id) ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('section_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </form>
        </div>

        <div class="row" id="subjects-container">
            @if($selectedSchoolYearId && $selectedSemesterId)
                @if($subjectsEnrolled->isEmpty())
                    <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
                        <div class="col-12 text-center">
                            <p class="fs-6 text-center"><b>Oops!</b> No subjects enrolled for the selected options.</p>
                            <img src="{{ asset('img/svg/no-record.svg') }}" class="img-fluid py-5" alt="No Records Found">
                        </div>
                    </section>
                @else
                    @foreach($subjectsEnrolled as $subjectEnrolled)
                        <div class="col-md-4">
                            <div class="card mb-3 shadow-sm">
                                <div class="card-body">
                                    @php
                                        // Remove the "(lab)" or "(lec)" suffix from the subject code
                                        $cleanSubjectCode = preg_replace('/\s*\(.*?\)\s*/', '', $subjectEnrolled->subject->subject_code);
                                    @endphp
                                    <h5 class="card-title">{{ $subjectEnrolled->subject->department->code }}-{{ $cleanSubjectCode }} - {{ $subjectEnrolled->section->name }}</h5>
                                    <p class="card-text">{{ $subjectEnrolled->subject->description }}</p>
                                    <a href="{{ route('teacher.subject.grades', ['subjectEnrolledId' => $subjectEnrolled->id]) }}" class="btn btn-primary">View Grades</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            @else
                <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
                    <div class="col-12 text-center">
                        <p class="fs-6 text-center"><b>Oops!</b> Please select a School Year and Semester to view subjects.</p>
                        <img src="{{ asset('img/svg/no-record.svg') }}" class="img-fluid py-5" alt="No Records Found">
                    </div>
                </section>
            @endif
        </div>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const schoolYearId = localStorage.getItem('school_year_id');
        const semesterId = localStorage.getItem('semester_id');
        const departmentId = localStorage.getItem('department_id');
        const sectionId = localStorage.getItem('section_id');

        if (schoolYearId) {
            document.getElementById('school_year_id').value = schoolYearId;
            document.getElementById('semester-container').style.display = 'block';
        }

        if (semesterId) {
            document.getElementById('semester_id').value = semesterId;
        }

        if (departmentId) {
            document.getElementById('department').value = departmentId;
        }

        if (sectionId) {
            document.getElementById('section').value = sectionId;
        }

        if (!schoolYearId) {
            document.getElementById('semester-container').style.display = 'none';
        }
    });

    document.getElementById('school_year_id').addEventListener('change', function() {
        localStorage.setItem('school_year_id', this.value);
        if (this.value) {
            document.getElementById('semester-container').style.display = 'block';
        } else {
            document.getElementById('semester-container').style.display = 'none';
        }
        this.form.submit();
    });

    document.getElementById('semester_id').addEventListener('change', function() {
        localStorage.setItem('semester_id', this.value);
        this.form.submit();
    });

    document.getElementById('department').addEventListener('change', function() {
        localStorage.setItem('department_id', this.value);
        this.form.submit();
    });

    document.getElementById('section').addEventListener('change', function() {
        localStorage.setItem('section_id', this.value);
        this.form.submit();
    });
</script>
@endsection
