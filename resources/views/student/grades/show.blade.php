@extends('layouts.app')

@section('title', 'My Grades')

@section('content')
<main id="main" class="main">
    <div class="container">
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h2 class="mb-0">Grade Records</h2>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('student.grades.index') }}" class="btn btn-primary">Go Back</a>
            </div>
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
                    @foreach($semesters as $semester)
                        <option value="{{ $semester }}">
                            {{ $semester }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Grades Display -->
        <div id="filtered-grades">
            <!-- The grades will be loaded here via AJAX -->
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        function loadGrades() {
            const schoolYearId = $('#school_year_id').val();
            const semesterName = $('#semester_name').val();

            $.ajax({
                url: '{{ route("student.grades.filter", $studentId) }}',
                method: 'GET',
                data: {
                    school_year_id: schoolYearId,
                    semester_name: semesterName
                },
                success: function(response) {
                    $('#filtered-grades').html(response);
                },
                error: function(xhr) {
                    console.error('Error loading grades:', xhr.responseText);
                }
            });
        }

        // Load grades on page load
        loadGrades();

        // Load grades when filters change
        $('#school_year_id, #semester_name').change(loadGrades);
    });
</script>
@endsection