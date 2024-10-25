    {{-- resources/views/pre-enrollment/form.blade.php --}}
    @extends('layouts.app')

    @section('title', 'Pre-Enrollment')

    @section('content')
    <main id="main" class="main">
        <!-- Page title -->
        <div class="pagetitle">
            <h1>Pre-enrollment Application</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('newsfeed') }}">Home</a></li>
                    <li class="breadcrumb-item active">Pre-Enrollment Form</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <div class="container">
            <!-- Progress Bar -->
            <div class="progress mb-4">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 12%" id="progressBar">
                    Step 1 of 3
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
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <!-- Enrollment Form -->
            <form id="preEnrollmentForm" action="{{ route('pre-enrollment.submit') }}" method="POST">
                @csrf
                <div class="step active" id="step1">
                    <h3 class="section-title text-primary">Step 1: Personal Information</h3>
                    <p><strong>Student Type:</strong> {{ $student->student_type }}</p>
                    <p><strong>Year Level:</strong> {{ $student->YearLevel }}</p>

                    <!-- Personal Information Fields -->
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

                    <div class="row mb-3">
                        <div class="col-md-3 form-group">
                            <label for="birthDate">Birth Date</label>
                            <input type="date" id="birthDate" name="birthDate" class="form-control custom-input" value="{{ old('birthDate', $student->Birthday) }}" required>
                        </div>

                        <div class="col-md-3 form-group">
                            <label for="sex">Sex</label>
                            <select id="sex" name="sex" class="form-control custom-input" required>
                                <option value="">Select Sex</option>
                                <option value="Male" {{ old('sex', $student->Gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('sex', $student->Gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <div class="col-md-3 form-group">
                            <label for="religion">Religion</label>
                            <input type="text" id="religion" name="religion" class="form-control custom-input" value="{{ old('religion', $student->Religion) }}" required>
                        </div>

                        <div class="col-md-3 form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control custom-input" required>
                                <option value="">Select Status</option>
                                <option value="Single" {{ old('status', $student->Status) == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ old('status', $student->Status) == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Divorced" {{ old('status', $student->Status) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                            </select>
                        </div>
                    </div>

                    <!-- Place of Birth Information -->
                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label for="birthplace">Place of Birth</label>
                            <input type="text" id="birthplace" name="birthplace" class="form-control custom-input" value="{{ old('birthplace', $student->BirthPlace) }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="address">Home Address</label>
                            <input type="text" id="address" name="address" class="form-control custom-input" value="{{ old('address', $student->Address) }}" required>
                        </div>
                    </div>

                    <!-- Father's Information -->
                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label for="father">Father's Name</label>
                            <input type="text" id="father" name="father" class="form-control custom-input" value="{{ old('father', $student->father_name) }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="fatheroccupation">Father's Occupation</label>
                            <input type="text" id="fatheroccupation" name="fatheroccupation" class="form-control custom-input" value="{{ old('fatheroccupation', $student->father_occupation) }}" required>
                        </div>
                    </div>

                    <!-- Mother's Information -->
                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label for="mother">Mother's Name</label>
                            <input type="text" id="mother" name="mother" class="form-control custom-input" value="{{ old('mother', $student->mother_name) }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="motheroccupation">Mother's Occupation</label>
                            <input type="text" id="motheroccupation" name="motheroccupation" class="form-control custom-input" value="{{ old('motheroccupation', $student->mother_occupation) }}" required>
                        </div>
                    </div>

                    <!-- Previous School Information -->
                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label for="prevschool">Previous School Attended</label>
                            <input type="text" id="prevschool" name="prevschool" class="form-control custom-input" value="{{ old('prevschool', $student->previous_school) }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="prevschooladdress">Previous School Address</label>
                            <input type="text" id="prevschooladdress" name="prevschooladdress" class="form-control custom-input" value="{{ old('prevschooladdress', $student->previous_school_adress) }}" required>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label for="contact">Contact Number</label>
                            <input type="text" id="contact" name="contact" class="form-control custom-input" value="{{ old('contact', $student->contact) }}" required>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary prev-step">Previous</button>
                    <button type="button" class="btn btn-primary next-step">Next</button>
                </div>
                <div class="step active" id="step2">
                            <h3 class="section-title text-primary">Step 2: Programs Offered & Year Level</h3>

                        <div class="mb-3">
                            <label for="program" class="form-label">Program</label>
                            <select class="form-control" id="program" name="program" required>
                                <option value="">Select a Program</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}" {{ $student->program_id == $program->id ? 'selected' : '' }}>
                                        {{ $program->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="year-level" class="form-label">Year Level</label>
                            <select class="form-control" id="year-level" name="year_level" {{ $student->student_type == 'new' ? 'disabled' : '' }}>
                                @foreach($yearLevels as $yearLevel)
                                    <option value="{{ $yearLevel }}" {{ $student->year_level == $yearLevel ? 'selected' : '' }}>
                                        {{ $yearLevel }}
                                    </option>
                                @endforeach
                            </select>

                            @if($student->student_type == 'new')
                                <input type="hidden" name="year_level" value="{{ $student->year_level }}">
                            @endif
                        </div>
                        <button type="button" class="btn btn-secondary prev-step">Previous</button>
                            <button type="button" class="btn btn-primary next-step">Next</button>
                </div>


                <div class="step active" id="step3">
                        <h3 class="section-title text-primary">Step 3: Class Schedule</h3>
                        <div class="mb-3">
                            <label for="schedule" class="form-label">Preferred Class Schedule</label>
                            <select class="form-control" id="schedule" name="schedule" required>
                                <!-- Schedule options populated dynamically with JavaScript -->
                            </select>
                        </div>
                        <button type="button" class="btn btn-secondary prev-step">Previous</button>
                        <button type="button" class="btn btn-primary next-step">Next</button>
                </div>
                <div class="step" id="step4"> 
                    <h3 class="section-title text-primary">Step 4: Pre-enrollment Confirmation</h3>
                    
                    <h5>Review your information:</h5>
                    <p><strong>Full Name:</strong> <span id="previewFullName"></span></p>
                    <p><strong>Student ID:</strong> <span id="previewStudentID"></span></p>
                    <p><strong>Birth Date:</strong> <span id="previewBirthDate"></span></p>
                    <p><strong>Sex:</strong> <span id="previewSex"></span></p>
                    <p><strong>Religion:</strong> <span id="previewReligion"></span></p>
                    <p><strong>Status:</strong> <span id="previewStatus"></span></p>
                    <p><strong>Address:</strong> <span id="previewAddress"></span></p>
                    <p><strong>Program:</strong> <span id="previewProgram"></span></p>
                    <p><strong>Year Level:</strong> <span id="previewYearLevel"></span></p>
                    <p><strong>Preferred Schedule:</strong> <span id="previewSchedule"></span></p>

                    <button type="button" class="btn btn-secondary prev-step">Previous</button>
                    <button type="submit" class="btn btn-primary">Submit Now</button>
                </div>


            </form>
        </div>
    </main>
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
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentStep = 1;
        const totalSteps = 4;
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
                    if (currentStep === 3) {
                        // Call updatePreview() right before transitioning to step 4
                        updatePreview();
                    }
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

        // Function to update the preview fields in Step 4
        function updatePreview() {
            document.getElementById('previewFullName').innerText = document.getElementById('fullName').value;
            document.getElementById('previewStudentID').innerText = document.getElementById('studentID').value;
            document.getElementById('previewBirthDate').innerText = document.getElementById('birthDate').value;
            document.getElementById('previewSex').innerText = document.getElementById('sex').value;
            document.getElementById('previewReligion').innerText = document.getElementById('religion').value;
            document.getElementById('previewStatus').innerText = document.getElementById('status').value;
            document.getElementById('previewAddress').innerText = document.getElementById('address').value;
            document.getElementById('previewProgram').innerText = document.getElementById('program').options[document.getElementById('program').selectedIndex].text;
            document.getElementById('previewYearLevel').innerText = document.getElementById('year-level').options[document.getElementById('year-level').selectedIndex].text;
            document.getElementById('previewSchedule').innerText = document.getElementById('schedule').options[document.getElementById('schedule').selectedIndex].text;
        }

        // Fetch schedules when program or year level is changed
        document.getElementById('program').addEventListener('change', fetchSchedules);
        document.getElementById('year-level').addEventListener('change', fetchSchedules);

        function fetchSchedules() {
            let programId = document.getElementById('program').value;
            let yearLevelId = document.getElementById('year-level').value;

            // Handle new students
            if (document.getElementById('year-level').disabled) {
                yearLevelId = '1st Year'; // Default for new students
            }

            if (programId && yearLevelId) {
                fetch(`/get-schedules?program_id=${programId}&year_level=${yearLevelId}`)
                    .then(response => response.json())
                    .then(data => {
                        let scheduleSelect = document.getElementById('schedule');
                        scheduleSelect.innerHTML = ''; // Clear existing options

                        if (data.schedules.length) {
                            data.schedules.forEach(schedule => {
                                let option = document.createElement('option');
                                option.value = schedule.id;
                                option.textContent = `${schedule.section_name} - ${schedule.subject_code} (${schedule.start_time} - ${schedule.end_time}, ${schedule.days})`;
                                scheduleSelect.appendChild(option);
                            });
                        } else {
                            let option = document.createElement('option');
                            option.value = '';
                            option.textContent = 'No schedules available for this program';
                            scheduleSelect.appendChild(option);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching schedules:', error);
                    });
            } else {
                let scheduleSelect = document.getElementById('schedule');
                scheduleSelect.innerHTML = ''; 
                let option = document.createElement('option');
                option.value = '';
                option.textContent = 'Please select a program and year level';
                scheduleSelect.appendChild(option);
            }
        }

        // Automatically fetch schedules for new students (if they are 1st year)
        window.onload = function() {
            if (document.getElementById('year-level').disabled) {
                fetchSchedules(); // Preload schedules for new students
            }
        };
    });

    </script>


    @endsection
