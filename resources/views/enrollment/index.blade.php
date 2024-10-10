@extends('layouts.app') <!-- Assuming you have a layout file -->

@section('content')
<div class="container">
    <h2>Enrollment Form</h2>

    <!-- Step 1: Personal Information (Autofilled for existing students) -->
    <form id="enrollmentForm" action="{{ route('enrollment.submit') }}" method="POST">
        @csrf
        <div class="card mb-3">
            <div class="card-header">
                <h3>Personal Information</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- ID Number (Autofilled and disabled) -->
                    <div class="col-md-4">
                        <label for="student_id">ID No:</label>
                        <input type="text" class="form-control" id="student_id" value="{{ $student->StudentID }}" disabled>
                    </div>

                    <!-- Academic Year (Autofilled and disabled) -->
                    <div class="col-md-4">
                        <label for="school_year">Academic Year:</label>
                        <input type="text" class="form-control" id="school_year" value="{{ $currentSchoolYear->name }}" disabled>
                    </div>

                    <!-- Semester (Autofilled and disabled) -->
                    <div class="col-md-4">
                        <label for="semester">Semester:</label>
                        <input type="text" class="form-control" id="semester" value="{{ $currentSemester->name }}" disabled>
                    </div>
                </div>

                <div class="row mt-3">
                    <!-- Name (Autofilled and disabled) -->
                    <div class="col-md-4">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" value="{{ $student->FullName }}" disabled>
                    </div>

                    <!-- Birthday (Autofilled and disabled) -->
                    <div class="col-md-4">
                        <label for="birthday">Birthday:</label>
                        <input type="text" class="form-control" id="birthday" value="{{ $student->Birthday }}" disabled>
                    </div>

                    <!-- Address (Autofilled and disabled) -->
                    <div class="col-md-4">
                        <label for="address">Address:</label>
                        <input type="text" class="form-control" id="address" value="{{ $student->Address }}" disabled>
                    </div>
                </div>

                <div class="row mt-3">
                    <!-- Status -->
                    <div class="col-md-4">
                        <label for="status">Status:</label>
                        <input type="text" class="form-control" id="status" value="{{ $student->Status }}" disabled>
                    </div>

                    <!-- Religion -->
                    <div class="col-md-4">
                        <label for="religion">Religion:</label>
                        <input type="text" class="form-control" id="religion" value="{{ $student->Religion }}" disabled>
                    </div>

                    <!-- Mobile Number -->
                    <div class="col-md-4">
                        <label for="mobile_number">Mobile Number:</label>
                        <input type="text" class="form-control" id="mobile_number" value="{{ $student->mobile_number }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: Course Enrollment -->
        <div class="card mb-3">
            <div class="card-header">
                <h3>Block Subject Schedule</h3>
            </div>
            <div class="card-body">
                <!-- Use AJAX to fetch the block schedule dynamically based on year level, section, and department -->
                <div class="row">
                    <div class="col-md-4">
                        <label for="department">Department:</label>
                        <input type="text" class="form-control" id="department" value="{{ $student->Course }}" disabled>
                    </div>

                    <div class="col-md-4">
                        <label for="year_level">Year Level:</label>
                        <input type="text" class="form-control" id="year_level" value="{{ $student->YearLevel }}" disabled>
                    </div>

                    <div class="col-md-4">
                        <label for="section">Section:</label>
                        <input type="text" class="form-control" id="section" value="{{ $student->Section }}" disabled>
                    </div>
                </div>

                <div class="row mt-4">
                    <!-- Interactive subject schedule display here -->
                    <div class="col-md-12">
                        <div id="block-schedule-container">
                            <!-- Subject cards will be displayed here dynamically using JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- A button to switch to calendar view (optional) -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary" id="viewCalendarButton">View in Calendar</button>
                        <button type="submit" class="btn btn-success">Submit Enrollment</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- JavaScript for fetching and displaying block schedule -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fetch and display the block schedule when the page loads
        fetchBlockSchedule();

        // Fetch the block schedule
        function fetchBlockSchedule() {
            const semesterId = '{{ $currentSemester->id }}';
            const schoolYearId = '{{ $currentSchoolYear->id }}';

            fetch('{{ route('enrollment.getBlockSchedule') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    semester_id: semesterId,
                    school_year_id: schoolYearId
                })
            })
            .then(response => response.json())
            .then(subjects => {
                let scheduleContainer = document.getElementById('block-schedule-container');

                if (subjects.error) {
                    scheduleContainer.innerHTML = '<p>' + subjects.error + '</p>';
                } else {
                    scheduleContainer.innerHTML = '';

                    subjects.forEach(subject => {
                        let subjectCard = `
                            <div class="card mt-3">
                                <div class="card-body">
                                    <h5 class="card-title">${subject.subject_code} - ${subject.description}</h5>
                                    <p class="card-text">
                                        <strong>Days:</strong> ${subject.day} <br>
                                        <strong>Time:</strong> ${subject.time} <br>
                                        <strong>Room:</strong> ${subject.room_name} <br>
                                        <strong>Instructor:</strong> ${subject.instructor_name}
                                    </p>
                                </div>
                            </div>
                        `;
                        scheduleContainer.innerHTML += subjectCard;
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching block schedule:', error);
            });
        }

        // Event listener for the calendar button (optional feature)
        document.getElementById('viewCalendarButton').addEventListener('click', function() {
            alert('Calendar view is not implemented yet.');
        });
    });
</script>
@endsection
