@extends('layouts.app')
@section('title', 'Online Enrollment')

@section('content')
<main id="main" class="main">
    <!-- Page title -->
    <div class="pagetitle">
        <h1>Online Enrollment</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('newsfeed') }}">Home</a></li>
                <li class="breadcrumb-item active">Online Enrollment</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="container">
        <!-- Progress Bar -->
        <div class="progress mb-4">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 12%" id="progressBar">
                Step 1 of 5
            </div>
        </div>
<!-- Academic Year and Semester Display -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card bg-light shadow-sm p-3 d-flex align-items-center justify-content-center">
            <div class="row text-center w-100">
                <!-- Academic Year -->
                <div class="col-md-6">
                    <div class="d-flex align-items-center justify-content-center">
                        <!-- Icon for Academic Year (with color) -->
                        <i class="fas fa-calendar-alt fa-2x text-primary me-3"></i> 
                        <div>
                            <h5 class="text-primary mb-1">Academic Year</h5>
                            <p class="fs-5 mb-0">{{ $currentSchoolYear->name }}</p>
                        </div>
                    </div>
                </div>

                <!-- Semester -->
                <div class="col-md-6">
                    <div class="d-flex align-items-center justify-content-center">
                        <!-- Icon for Semester (with color) -->
                        <i class="fas fa-book-open fa-2x text-info me-3"></i>
                        <div>
                            <h5 class="text-info mb-1">Semester</h5>
                            <p class="fs-5 mb-0">{{ $currentSemester->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


        <!-- Multi-Step Form -->
        <form id="enrollmentForm" action="{{ route('enrollment.store') }}" method="POST" class="enrollment-form shadow-lg p-4">
            @csrf

 <!-- Step 1: Student Category Selection -->
<div class="step active" id="step1">
    <h3 class="section-title text-primary">Step 1: Select Student Category</h3>
    <div class="row mb-3">
        <div class="col-md-12 form-group">
            <label for="studentCategory">Select Category</label>
            <select id="studentCategory" name="student_category" class="form-control custom-input" required>
                <option value="">-- Select Student Category --</option>
                <option value="shiftee">Shiftee</option>
                <option value="transferee">Transferee</option>
                <option value="returnee">Returnee</option>
                <option value="new_student">New Student</option>
                <option value="old_student">Old Student</option> <!-- Handles old student selection -->
            </select>
        </div>
    </div>
    <button type="button" class="btn btn-primary next-step">Next</button>
</div>

<!-- Step 2: Regular or Irregular Selection -->
<div class="step" id="step2">
    <h3 class="section-title text-primary">Step 2: Are you a Regular or Irregular Student?</h3>
    <div class="form-group mb-3">
        <label>Student Type</label>
        <div class="form-check">
            <input type="radio" class="form-check-input" name="student_type" id="regular" value="regular" required>
            <label class="form-check-label" for="regular">Regular</label>
        </div>
        <div class="form-check">
            <input type="radio" class="form-check-input" name="student_type" id="irregular" value="irregular" required>
            <label class="form-check-label" for="irregular">Irregular</label>
        </div>
    </div>
    <button type="button" class="btn btn-secondary prev-step">Previous</button>
    <button type="button" class="btn btn-primary next-step">Next</button>
</div>

<!-- Step 3: Program and Year Level Selection -->
<div class="step" id="step3">
    <h3 class="section-title text-primary">Step 3: Select Program and Year Level</h3>
    <div class="row mb-3">
        <div class="col-md-6 form-group">
            <label for="program">Program</label>
            <select id="program" name="program" class="form-control custom-input" required>
                <option value="">-- Select Program --</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 form-group">
            <label for="yearLevel">Year Level</label>
            <select id="yearLevel" name="year_level" class="form-control custom-input" required>
                <option value="">-- Select Year Level --</option>
                <option value="1st Year">1st Year</option>
                <option value="2nd Year">2nd Year</option>
                <option value="3rd Year">3rd Year</option>
                <option value="4th Year">4th Year</option>
            </select>
        </div>
    </div>
    <button type="button" class="btn btn-secondary prev-step">Previous</button>
    <button type="button" class="btn btn-primary next-step">Next</button>
</div>



                    <!-- Step 4: Personal Information -->
            <div class="step" id="step4">
                <h3 class="section-title text-primary">Step 4: Personal Information</h3>
                <!-- Personal information fields -->
                <div class="row mb-3">
                    <div class="col-md-6 form-group">
                        <label for="studentID">Student ID</label>
                        <input type="text" id="studentID" class="form-control custom-input" value="{{ $student->StudentID }}" disabled>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="fullName">Full Name</label>
                        <input type="text" id="fullName" class="form-control custom-input" value="{{ $student->FullName }}" disabled>
                    </div>
                </div>

                <!-- Horizontal alignment for birthDate, sex, religion, status -->
                <div class="row mb-3">
                    <div class="col-md-3 form-group">
                        <label for="birthDate">Birth Date</label>
                        <input type="date" id="birthDate" class="form-control custom-input" value="{{ $student->Birthday }}" disabled>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="sex">Sex</label>
                        <select id="sex" name="sex" class="form-control custom-input" required>
                            <option value="">Select Sex</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="religion">Religion</label>
                        <input type="text" id="religion" name="religion" class="form-control custom-input" value="{{ old('religion') }}" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control custom-input" required>
                            <option value="">Select Status</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Divorced">Divorced</option>
                        </select>
                    </div>
                </div>

                <!-- Place of Birth information -->
                <div class="row mb-3">
                    <div class="col-md-6 form-group">
                        <label for="birthplace">Place of Birth</label>
                        <input type="text" id="birthplace" name="birthplace" class="form-control custom-input" value="{{ old('birthplace') }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="address">Home Address</label>
                        <input type="text" id="address" name="address" class="form-control custom-input" value="{{ old('address') }}" required>
                    </div>
                </div>

                <!-- Father's information -->
                <div class="row mb-3">
                    <div class="col-md-6 form-group">
                        <label for="father">Father's Name</label>
                        <input type="text" id="father" name="father" class="form-control custom-input" value="{{ old('father') }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="fatheroccupation">Father's Occupation</label>
                        <input type="text" id="fatheroccupation" name="fatheroccupation" class="form-control custom-input" value="{{ old('fatheroccupation') }}" required>
                    </div>
                </div>

                <!-- Mother's information -->
                <div class="row mb-3">
                    <div class="col-md-6 form-group">
                        <label for="mother">Mother's Name</label>
                        <input type="text" id="mother" name="mother" class="form-control custom-input" value="{{ old('mother') }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="motheroccupation">Mother/Guardian's Occupation</label>
                        <input type="text" id="motheroccupation" name="motheroccupation" class="form-control custom-input" value="{{ old('motheroccupation') }}" required>
                    </div>
                </div>

                <!-- Previous school information -->
                <div class="row mb-3">
                    <div class="col-md-6 form-group">
                        <label for="prevschool">Previous School Attended</label>
                        <input type="text" id="prevschool" name="prevschool" class="form-control custom-input" value="{{ old('prevschool') }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="prevschooladdress">Previous School Address</label>
                        <input type="text" id="prevschooladdress" name="prevschooladdress" class="form-control custom-input" value="{{ old('prevschooladdress') }}" required>
                    </div>
                </div>

                <!-- Contact information -->
                <div class="row mb-3">
                    <div class="col-md-6 form-group">
                        <label for="contact">Contact Number</label>
                        <input type="text" id="contact" name="contact" class="form-control custom-input" value="{{ old('contact') }}" required>
                    </div>
                </div>

                <button type="button" class="btn btn-secondary prev-step">Previous</button>
                <button type="button" class="btn btn-primary next-step">Next</button>
            </div>


              <!-- Step 5: Course and Schedule Selection -->
              <div class="step" id="step5">
                <h3 class="section-title text-primary">Step 5: Select Course and Schedule</h3>

                <!-- Section Cards Layout -->
                <div class="row">
                    @foreach ($sections as $section)
                        <div class="col-md-4 mb-4">
                            <div class="card section-card h-100 shadow-sm {{ $section->available_slots == 0 ? 'bg-light text-muted' : '' }}"
                                style="{{ $section->available_slots == 0 ? 'cursor: not-allowed;' : 'cursor: pointer;' }}">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $section->name }}</h5>
                                    <p class="card-text">Available Slots: {{ $section->available_slots }}/{{ $section->total_slots }}</p>
                                    <p class="card-text">Year Level: {{ $section->subjectsEnrolled->first()->yearLevel->name ?? 'N/A' }}</p>

                                    @if ($section->available_slots == 0)
                                        <button type="button" class="btn btn-success mt-auto select-section-btn"
                                            data-section-id="{{ $section->id }}"
                                            data-year-level-id="{{ optional($section->subjectsEnrolled->first())->year_level_id }}"
                                            data-department-id="{{ $section->department_id }}"
                                            data-school-year-id="{{ $currentSchoolYear->id }}"
                                            data-semester-id="{{ $currentSemester->id }}">
                                            Select Section
                                        </button>
                                    @else
                                        <span class="badge bg-danger mt-auto">Full</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Schedule Modal -->
                <div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="scheduleModalLabel">Section Schedule</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Course Code</th>
                                            <th>Course Description</th>
                                            <th>Room</th>
                                            <th>Days</th>
                                            <th>Time</th>
                                            <th>Instructor</th>
                                        </tr>
                                    </thead>
                                    <tbody id="subject-schedule">
                                        <!-- Schedule data will be injected here -->
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-secondary prev-step">Previous</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
</main>
<!-- JavaScript to handle form transitions and section selection -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentStep = 1;
        const totalSteps = 5;
        const steps = document.querySelectorAll('.step');
        const progressBar = document.getElementById('progressBar');

        // Function to show the current step
        function showStep(step) {
            steps.forEach((element, index) => {
                if (index + 1 === step) {
                    element.style.display = 'block';
                    element.classList.add('fadeIn');
                } else {
                    element.style.display = 'none';
                    element.classList.remove('fadeIn');
                }
            });
            progressBar.style.width = `${(step / totalSteps) * 100}%`;
            progressBar.textContent = `Step ${step} of ${totalSteps}`;
        }

        // Initial display
        showStep(currentStep);

        // Next step button
        document.querySelectorAll('.next-step').forEach(button => {
            button.addEventListener('click', () => {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            });
        });

        // Previous step button
        document.querySelectorAll('.prev-step').forEach(button => {
            button.addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });
        });

        // Handle section selection
        const selectButtons = document.querySelectorAll('.select-section-btn');
        const scheduleModal = new bootstrap.Modal(document.getElementById('scheduleModal'), {
            keyboard: false
        });
        const scheduleTableBody = document.getElementById('subject-schedule');

        selectButtons.forEach(button => {
            button.addEventListener('click', function () {
                const sectionId = this.getAttribute('data-section-id');
                const yearLevelId = this.getAttribute('data-year-level-id');
                const departmentId = this.getAttribute('data-department-id');
                const schoolYearId = this.getAttribute('data-school-year-id');
                const semesterId = this.getAttribute('data-semester-id');

                // Fetch schedule via AJAX
                fetch(`/get-section-schedule?section_id=${sectionId}&year_level_id=${yearLevelId}&department_id=${departmentId}&school_year_id=${schoolYearId}&semester_id=${semesterId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.subjects && data.subjects.length > 0) {
                            let scheduleHtml = '';
                            data.subjects.forEach(subject => {
                                scheduleHtml += `
                                    <tr>
                                        <td>${subject.subject_code}</td>
                                        <td>${subject.subject_description}</td>
                                        <td>${subject.room_name || 'N/A'}</td>
                                        <td>${subject.days || 'N/A'}</td>
                                        <td>${subject.time || 'N/A'}</td>
                                        <td>${subject.instructor_name || 'N/A'}</td>
                                    </tr>`;
                            });
                            scheduleTableBody.innerHTML = scheduleHtml;
                            scheduleModal.show();
                        } else {
                            alert('No subjects found for this section.');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching section schedule:', error);
                        alert('Failed to fetch the schedule. Please try again.');
                    });
            });
        });
    });
</script>
<script>
$(document).ready(function() {
    // Track changes in the 'Student Category' dropdown
    $('#studentCategory').on('change', function() {
        var studentCategory = $(this).val();
        
        if (studentCategory === 'old_student') {
            // Fetch student's previous details for old students
            $.ajax({
                url: '/get-student-details', // Adjust the URL to your route
                method: 'GET',
                success: function(response) {
                    // Check if the response has the necessary data
                    if (response.department) {
                        // Set the department value and disable the dropdown
                        $('#program').val(response.department.id).prop('disabled', true);
                    }

                    if (response.currentYearLevel) {
                        // Populate and set the current year level
                        populateYearLevels(response.currentYearLevel);
                    }
                },
                error: function() {
                    alert('Error fetching student details.');
                }
            });
        } else {
            // Enable the dropdown and reset if not an old student
            $('#program').prop('disabled', false).val('');
            resetYearLevels();
        }
    });
});

// Function to dynamically populate year levels starting from current year
function populateYearLevels(currentYearLevel) {
    $('#yearLevel').empty().append('<option value="">-- Select Year Level --</option>');
    
    var yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
    
    for (let i = currentYearLevel - 1; i < yearLevels.length; i++) {
        $('#yearLevel').append(`<option value="${yearLevels[i]}">${yearLevels[i]}</option>`);
    }
}

// Function to reset the year level dropdown to default values
function resetYearLevels() {
    $('#yearLevel').empty().append(`
        <option value="">-- Select Year Level --</option>
        <option value="1st Year">1st Year</option>
        <option value="2nd Year">2nd Year</option>
        <option value="3rd Year">3rd Year</option>
        <option value="4th Year">4th Year</option>
    `);
}


</script>
<!-- CSS for smooth transitions and enhanced UI -->
<style>
    .step {
        display: none;
    }

    .fadeIn {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        0% { opacity: 0; transform: translateX(20px); }
        100% { opacity: 1; transform: translateX(0); }
    }

    .section-card {
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .section-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }

    .btn-success.select-section-btn {
        width: 100%;
    }

    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .section-card {
            margin-bottom: 20px;
        }
    }
</style>
@endsection
