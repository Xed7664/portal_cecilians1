@if($groupedGrades->isEmpty())
    <p>No grades available.</p>
@else
    @foreach($groupedGrades as $schoolYearId => $semesters)
        @php $schoolYear = \App\Models\SchoolYear::find($schoolYearId); @endphp
        <h5 class="mt-4">School Year: {{ $schoolYear->name ?? 'Unknown School Year' }}</h5>
        @foreach($semesters as $semesterId => $sections)
            @php $semester = \App\Models\Semester::find($semesterId); @endphp
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">{{ $semester->name ?? 'Unknown Semester' }}</h5>
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
                                                <td>{{ $grade->subject->subject_code }}</td>
                                                <td>{{ $grade->subject->description }}</td>
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