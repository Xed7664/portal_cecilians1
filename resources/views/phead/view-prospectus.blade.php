@extends('layouts.app')

@section('title', 'View Prospectus')

@section('content')
<main id="main" class="main">
    <section class="section">
        <div class="row mb-3">
            <div class="col-md-6 d-flex justify-content-start">
                <a href="{{ route('phead.students.grades', $student->id) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Back to Grades
                </a>
            </div>
        </div>
        
        <div class="container">
            <h2>Prospectus</h2>

            <!-- Filters for Year Level, Semester, and Search -->
            <div class="row mb-1 mt-4">
                <div class="col-md-4">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search Courses...">
                </div>

                <div class="col-md-4">
                    <select id="yearLevelFilter" class="form-select">
                        <option value="">All Year Levels</option>
                        @foreach ($prospectusData as $year => $semesters)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <select id="semesterFilter" class="form-select">
                        <option value="">All Semesters</option>
                        @php $semesterOptions = []; @endphp
                        @foreach ($prospectusData as $year => $semesters)
                            @foreach ($semesters as $semester => $subjects)
                                @if (!in_array($semester, $semesterOptions))
                                    <option value="{{ $semester }}">{{ $semester }} Semester</option>
                                    @php $semesterOptions[] = $semester; @endphp
                                @endif
                            @endforeach
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Prospectus Data -->
            @foreach ($prospectusData as $year => $semesters)
                <div class="prospectus-year mb-4" data-year="{{ $year }}">
                    <h3>{{ $year }}</h3>
                    
                    @foreach ($semesters as $semester => $subjects)
                        <div class="prospectus-semester" data-semester="{{ $semester }}">
                            <h4 class="text-center">{{ $semester }} Semester</h4>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered prospectus-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Final Grade</th>
                                            <th>Course Code</th>
                                            <th>Course Description</th>
                                            <th>Lec Units</th>
                                            <th>Lab Units</th>
                                            <th>Total Units</th>
                                            <th>Pre-requisite/Co-Requisite</th>
                                            <th>Total Hours/Week</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalLecUnits = $totalLabUnits = $totalUnits = $totalHours = 0;
                                        @endphp

                                        @foreach ($subjects as $subjectProspectus)
                                            @php
                                                $subject = $subjectProspectus->subject;
                                                $enrollment = $enrolledSubjects->get($subject->id);
                                                $finalGrade = $enrollment && $enrollment->grades->first() ? $enrollment->grades->first()->final : '--';

                                                $totalLecUnits += $subject->lec_units;
                                                $totalLabUnits += $subject->lab_units;
                                                $totalUnits += $subject->total_units;
                                                $totalHours += $subject->total_hours;
                                            @endphp
                                            <tr>
                                                <td>{{ $finalGrade }}</td>
                                                <td>{{ $subject->subject_code }}</td>
                                                <td>{{ $subject->description }}</td>
                                                <td>{{ $subject->lec_units }}</td>
                                                <td>{{ $subject->lab_units }}</td>
                                                <td>{{ $subject->total_units }}</td>
                                                <td>{{ $subject->pre_requisite ?? 'None' }}</td>
                                                <td>{{ $subject->total_hours }}</td>
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <th colspan="3">Total</th>
                                            <th>{{ $totalLecUnits }}</th>
                                            <th>{{ $totalLabUnits }}</th>
                                            <th>{{ $totalUnits }}</th>
                                            <th></th>
                                            <th>{{ $totalHours }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>
</main>

<!-- Search and Filter Script -->
<script>
    $(document).ready(function () {
        function filterProspectus() {
            var selectedYear = $('#yearLevelFilter').val();
            var selectedSemester = $('#semesterFilter').val();
            var searchValue = $('#searchInput').val().toLowerCase();
            
            $('.prospectus-year').each(function () {
                var yearMatch = (selectedYear === '' || $(this).data('year') === selectedYear);
                $(this).toggle(yearMatch);

                $(this).find('.prospectus-semester').each(function () {
                    var semesterMatch = (selectedSemester === '' || $(this).data('semester') === selectedSemester);
                    $(this).toggle(yearMatch && semesterMatch);
                });
                
                $(this).find('.prospectus-table tbody tr').each(function () {
                    var textMatch = $(this).text().toLowerCase().indexOf(searchValue) > -1;
                    $(this).toggle(textMatch);
                });
            });
        }

        $('#yearLevelFilter, #semesterFilter').on('change', filterProspectus);
        $('#searchInput').on('keyup', filterProspectus);
    });
</script>
@endsection