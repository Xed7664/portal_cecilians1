@extends('layouts.app')

@section('title', 'Student Grades')

@section('content')
<main id="main" class="main">
    <section class="section">
        <div class="row mb-4">
            <div class="mb-3 col-md-12 d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.users.student') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Back to Subjects
                </a>
            </div>

            <!-- Filters -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="school_year_id">Select School Year:</label>
                    <select id="school_year_id" class="form-control">
                        <option value="">All School Years</option>
                        @foreach($schoolYears as $schoolYear)
                            <option value="{{ $schoolYear->id }}">
                                {{ $schoolYear->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="semester_name">Select Semester:</label>
                    <select id="semester_name" class="form-control">
                        <option value="">All Semesters</option>
                        @foreach($semesters->take(2) as $semester)  
                            <option value="{{ $semester }}">
                                {{ $semester}}  
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Grades for {{ $student->FullName }}</h5>

                        <div id="grades-table-container">
                            @if($groupedGrades->isEmpty())
                                <p>No grades available for this student.</p>
                            @else
                                @foreach($groupedGrades as $schoolYearId => $semesters)
                                    @php $schoolYear = \App\Models\SchoolYear::find($schoolYearId); @endphp
                                    <h5 class="mt-4">School Year: {{ $schoolYear->name ?? 'Unknown School Year' }}</h5>
                                    @foreach($semesters as $semesterId => $sections)
                                        @php $semester = \App\Models\Semester::find($semesterId); @endphp

                                        

                                        <!-- Semester grades display -->
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h5 class="card-title">{{ $semester->name ?? 'Unknown Semester' }}</h5>
                                                <!-- Search bar and buttons for each semester -->
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <input type="text" class="form-control" id="search-grades-{{ $semesterId }}" placeholder="Search subjects...">
                                            </div>
                                            <div>
                                                <button class="btn me-2" style="background-color: grey; color: white;">
                                                    <i class="bi bi-file-earmark-arrow-up"></i> Export
                                                </button>
                                                <button class="btn" style="background-color: rgb(197, 5, 5); color: white;">
                                                    <i class="bi bi-file-earmark-arrow-down"></i> Import
                                                </button>
                                            </div>
                                            
                                        </div>
                                            </div>
                                            <div class="card-body">
                                                @foreach($sections as $sectionId => $yearLevels)
                                                    @php $section = \App\Models\Section::find($sectionId); @endphp
                                                    <h6 class="mt-2">Section: {{ $section->name ?? 'Unknown Section' }}</h6>
                                                    @foreach($yearLevels as $yearLevelId => $grades)
                                                        @php $yearLevel = \App\Models\YearLevel::find($yearLevelId); @endphp
                                                        <h6 class="mt-2">Year Level: {{ $yearLevel->name ?? 'Unknown Year Level' }}</h6>
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Subject Code</th>
                                                                        <th>Description</th>
                                                                        <th>Prelim</th>
                                                                        <th>Midterm</th>
                                                                        <th>Prefinal</th>
                                                                        <th>Final</th>
                                                                        <th>Remarks</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($grades as $grade)
                                                                        <tr>
                                                                            <td>{{ $grade->subject->subject_code ?? 'N/A' }}</td>
                                                                            <td>{{ $grade->subject->description ?? 'N/A' }}</td>
                                                                            <td>{{ $grade->prelim ?: 'No Grade' }}</td>
                                                                            <td>{{ $grade->midterm ?: 'No Grade' }}</td>
                                                                            <td>{{ $grade->prefinal ?: 'No Grade' }}</td>
                                                                            <td>{{ $grade->final ?: 'No Grade' }}</td>
                                                                            <td>{{ $grade->remarks ?: 'No Remarks' }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@endsection
