{{-- resources/views/pre-enrollment/form.blade.php --}}
@extends('layouts.app')

@section('title', 'Pre-Enrollment')

@section('content')
<main id="main" class="main">
    <!-- Page title -->
    <div class="pagetitle mb-4">
        <h1 class="display-5 fw-bold text-primary">Pre-Enrollment Application</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('newsfeed') }}">Home</a></li>
                <li class="breadcrumb-item active">Pre-Enrollment Form</li>
            </ol>
        </nav>
    </div>
    
    <div class="container mb-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Progress Bar -->
        <div class="progress mb-4 shadow-sm" style="height: 1.5rem; border-radius: 10px; overflow: hidden;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-gradient-primary" role="progressbar" style="width: 25%;" id="progressBar">
                Step 1 of 4
            </div>
        </div>
       
        <!-- Displaying Active Academic Period -->
        @if ($activeAcademicPeriod)
            <div class="row mb-3 justify-content-center">
                <div class="col-md-10">
                    <div class="card shadow-sm p-3 d-flex align-items-center justify-content-center">
                        <div class="row text-center w-100">
                             <h4 class="card-title mb-3 text-success">Active Academic Period</h4>
                            <!-- Academic Year -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-calendar-alt fa-2x text-primary me-3"></i> 
                                    <div>
                                        <h5 class="text-primary mb-1">Academic Year</h5>
                                        <p class="fs-5 mb-0">{{ $activeAcademicPeriod->schoolYear->name  }}</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Semester -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-book-open fa-2x text-info me-3"></i>
                                    <div>
                                        <h5 class="text-info mb-1">Semester</h5>
                                        <p class="fs-5 mb-0">{{$activeAcademicPeriod->semester->name }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-5">
                                    <i class="bi bi-calendar-range text-success fs-4"></i>
                                    <p class="mb-1"><strong>Enrollment Period</strong></p>
                                    <p class="text-muted">
                                        {{ \Carbon\Carbon::parse($activeAcademicPeriod->open_date)->format('F d, Y') }} 
                                        to 
                                        {{ \Carbon\Carbon::parse($activeAcademicPeriod->close_date)->format('F d, Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
     
         
        @else
            <div class="alert alert-warning text-center">
                <i class="bi bi-exclamation-circle-fill"></i> No active academic period is currently open.
            </div>
        @endif 
        <!-- Custom CSS for aesthetic enhancement -->

<div class="form-container">
    <form id="preEnrollmentForm" action="{{ route('pre-enrollment.submit') }}" method="POST">
        @csrf
              <div class="step active" id="step1">
                    <h3 class="section-title text-primary">Step 1: Personal Information</h3>

                        <!-- Display student type -->
                        <div class="form-group">
                            <label for="studentType">Student Type: <span class="text-danger">*</span></label>
                            <input type="text" id="studentType" class="form-control" value="{{ $student->student_type ?? 'N/A' }}">
                        </div>

                        <div class="form-group">
                            <label for="previous-year-level" class="form-label">Previous Year Level</label>
                            <input type="text" id="previous-year-level" class="form-control" value="{{ $previousYearLevel }}">
                        </div>


                        <!-- Personal Information Fields -->
                        <div class="row mb-3">
                            <div class="col-md-6 form-group">
                                <label for="studentID">Student ID <span class="text-danger">*</span></label>
                                <input type="text" id="studentID" class="form-control custom-input" value="{{ $student->StudentID }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="fullName">Full Name <span class="text-danger">*</span></label>
                                <input type="text" id="fullName" class="form-control custom-input" value="{{ $student->FullName }}">
                            </div>
                        </div>


                       <div class="row mb-3">
                            <div class="col-md-3 form-group">
                                <label for="birthDate">Birth Date <span class="text-danger">*</span></label>
                                <input type="date" id="birthDate" name="birthDate" 
                                    class="form-control custom-input" 
                                    value="{{ old('birthDate', $student->Birthday) }}" 
                                    required>
                                <div class="invalid-feedback">
                                    Please enter a valid birth date. The minimum age for college is 16 years.
                                </div>
                            </div>


                            <div class="col-md-3 form-group">
                                <label for="sex">Gender <span class="text-danger">*</span></label>
                                <select id="sex" name="sex" class="form-control custom-input" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('sex', $student->Gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('sex', $student->Gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select your sex.
                                </div>
                            </div>
         

                        <div class="col-md-3 form-group">
                            <label for="religion">Religion <span class="text-danger">*</span></label>
                            <input type="text" id="religion" name="religion" 
                                class="form-control custom-input" 
                                value="{{ old('religion', $student->Religion) }}" 
                                required pattern="[a-zA-Z\s]+">
                            <div class="invalid-feedback">
                                Please enter a valid religion.
                            </div>
                        </div>


                        <div class="col-md-3 form-group">
                            <label for="status">Status<span class="text-danger">*</span></label>
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
                            <label for="birthplace">Place of Birth<span class="text-danger">*</span></label>
                            <input type="text" id="birthplace" name="birthplace" class="form-control custom-input" value="{{ old('birthplace', $student->BirthPlace) }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="address">Home Address <span class="text-danger">*</span></label>
                            <input type="text" id="address" name="address" class="form-control custom-input" value="{{ old('address', $student->Address) }}" required>
                        </div>
                    </div>
                 <!-- Contact Information -->
                    <div class="form-group">
                        <label for="contactNumber">Contact Number</label>
                        <input type="text" id="contactNumber" name="contactNumber" 
                            class="form-control custom-input" 
                            value="{{ old('contactNumber', $student->contact_number) }}" 
                            pattern="09\d{9}|\(0\d{2}\)\d{7}|\(02\)\d{7}" 
                            placeholder="e.g., 09123456789 or (02)1234567">
                        <div class="invalid-feedback">
                            Please enter a valid Philippine contact number.
                        </div>
                    </div>


 
                  <button type="button" class="btn btn-secondary prev-step">Previous</button>
                 <button type="button" class="btn btn-primary next-step">Next</button>
              </div>
              <div class="step active" id="step2">
                    <h3 class="section-title text-primary">Step 2: Family Info</h3>
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

                    <button type="button" class="btn btn-secondary prev-step">Previous</button>
                    <button type="button" class="btn btn-primary next-step">Next</button>
                </div>

                <div class="step active" id="step3">
    <h3 class="section-title text-primary">Step 3: Programs Offered & Year Level</h3>

    <div class="mb-3">
        <label for="year-level" class="form-label text-dark">
            Year Level
            <i class="fas fa-info-circle" data-bs-toggle="tooltip" title="The current year level is calculated based on your completed subjects and progress."></i>
        </label>
        <input type="text" id="year-level" class="form-control" value="{{ $student->year_level ?? '1st Year' }}">
    </div>

    <!-- Completed Subjects Display -->
    @if(!empty($completedSubjects) && $completedSubjects->isNotEmpty())
        <h5>Completed Subjects</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Semester</th>
                    <th>Status</th>
                    <th>Final Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($completedSubjects as $subject)
                    <tr>
                        <td>{{ $subject->subject->subject_code }}</td>
                        <td>{{ $subject->semester->name }}</td>
                        <td>Passed</td>
                        <td>{{ $subject->grade->final }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
       
    @else
        <p class="text-dark">No completed subjects available.</p>
    @endif

    <!-- Program Selection -->
    <div class="mb-3">
        <label for="program" class="form-label text-dark" >
            Program
            <i class="fas fa-info-circle" data-bs-toggle="tooltip" title="Choose your current program. This determines the subjects and curriculum for your course."></i>
        </label>

        <!-- Program Select Field -->
              
        <select class="form-control text-dark" id="program" disabled>
            @foreach($programs as $program)
                <option value="{{ $program->id }}" {{ $student->program_id == $program->id ? 'selected' : '' }}>
                    {{ $program->name }}
                </option>
            @endforeach
        </select>

        <!-- Hidden input to submit the program ID -->
        <input type="hidden" name="program_id" value="{{ $student->program_id }}">


        <!-- Toggle Button for Program Change -->
        @if($student->program_id)
            <button type="button" class="btn btn-outline-primary mt-2" id="changeProgramButton">
                Change Program
            </button>
        @endif
    </div>

    <button type="button" class="btn btn-secondary prev-step">Previous</button>
    <button type="button" class="btn btn-primary next-step">Next</button>
</div>

<div class="step active" id="step4"> 
    <h3 class="section-title text-primary">Step 4: Class Schedule</h3>
    <div id="schedule-container">
        <p>Select a program and year level to view the schedule by section.</p>

        <!-- Section Cards Container -->
        <div id="sectionCardsContainer" class="d-flex flex-wrap mb-3"></div>

        <!-- Schedules Table -->
        <div id="schedulesTableWrapper"></div>
    </div>
    <button type="button" class="btn btn-secondary prev-step">Previous</button>
    <button type="button" class="btn btn-primary next-step">Next</button>
</div>

                <div class="step" id="step5"> 
                    <h3 class="section-title text-primary">Step 5: Pre-enrollment Confirmation</h3>
               <div class="text-dark">
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
                </div>
                    <button type="button" class="btn btn-secondary prev-step">Previous</button>
                    <button type="submit" class="btn btn-primary">Submit Now</button>
                </div>
        </div>
    </form>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Select the fields you want to make non-editable
        const restrictedFields = [
            document.getElementById("studentType"),
            document.getElementById("previous-year-level"),
            document.getElementById("studentID"),
            document.getElementById("fullName"),
            document.getElementById("year-level")
        ];

        // Prevent typing or pasting into these fields
        restrictedFields.forEach(field => {
            field.addEventListener("keydown", function(event) {
                event.preventDefault();
            });
            field.addEventListener("paste", function(event) {
                event.preventDefault();
            });
            field.addEventListener("cut", function(event) {
                event.preventDefault();
            });
            field.addEventListener("focus", function() {
                this.blur(); // Remove focus immediately
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle=\"tooltip\"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const changeProgramButton = document.getElementById('changeProgramButton');
        const programSelect = document.getElementById('program');

        if (changeProgramButton) {
            changeProgramButton.addEventListener('click', function () {
                // Enable the program select field if they want to change programs
                programSelect.disabled = !programSelect.disabled;
            });
        }
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    let currentStep = 1;
    const totalSteps = 5;
    const steps = Array.from(document.querySelectorAll('.step'));
    const progressBar = document.getElementById('progressBar');
    const programSelect = document.getElementById('program');
    const yearLevelSelect = document.getElementById('year-level');
    const scheduleSelect = document.getElementById('schedule');

    function showStep(step) {
        steps.forEach((element, index) => {
            element.style.display = (index + 1 === step) ? 'block' : 'none';
            element.classList.toggle('fadeIn', index + 1 === step);
        });
        progressBar.style.width = `${(step / totalSteps) * 100}%`;
        progressBar.textContent = `Step ${step} of ${totalSteps}`;
    }

    function validateStep(step) {
        let isValid = true;
        const fields = steps[step - 1].querySelectorAll('input, select');

        fields.forEach(field => {
            if (field.id === 'contactNumber') {
                const isValidContact = isValidContactNumber(field.value);
                field.classList.toggle('is-invalid', !isValidContact);
                isValid = isValid && isValidContact;
            } else {
                const fieldValid = field.checkValidity();
                field.classList.toggle('is-invalid', !fieldValid);
                isValid = isValid && fieldValid;
            }
        });

        // Additional checks for specific fields
        if (step === 1) {
            const birthDate = document.getElementById('birthDate');
            const religion = document.getElementById('religion');

            if (birthDate && !isValidBirthDate(birthDate.value)) {
                birthDate.classList.add('is-invalid');
                isValid = false;
            }
            if (religion && !isValidTextOnly(religion.value)) {
                religion.classList.add('is-invalid');
                isValid = false;
            }
        }

        return isValid;
    }

    function isValidContactNumber(value) {
        if (value.trim() === "") {
            return true; // Field is optional, so empty input is valid
        }
        const mobilePattern = /^09\d{9}$/;
        const landlinePattern = /^(0\d{2}|\(02\))\d{7}$/;
        return mobilePattern.test(value) || landlinePattern.test(value);
    }

    function isValidTextOnly(value) {
        return /^[A-Za-z\s]+$/.test(value);
    }

    function isValidBirthDate(date) {
        const selectedDate = new Date(date);
        const minAge = 16;
        const currentDate = new Date();
        const age = currentDate.getFullYear() - selectedDate.getFullYear();
        return selectedDate && age >= minAge;
    }

    showStep(currentStep);

    document.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', () => {
            if (currentStep < totalSteps && validateStep(currentStep)) {
                currentStep++;
                showStep(currentStep);
                if (currentStep === 4) updatePreview();
            }
        });
    });

    document.querySelectorAll('.prev-step').forEach(button => {
        button.addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });
    });

    // Debounced input validation to improve performance
    const validateFieldDebounced = debounce((field) => {
        const fieldValid = field.checkValidity();
        field.classList.toggle('is-invalid', !fieldValid);
        field.classList.toggle('is-valid', fieldValid);
    }, 300);

    document.querySelectorAll('input, select').forEach(field => {
        field.addEventListener('input', () => validateFieldDebounced(field));
    });

    function debounce(fn, delay) {
        let timeoutId;
        return (...args) => {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => fn(...args), delay);
        };
    }



    function updatePreview() {
    document.getElementById('previewFullName').innerText = document.getElementById('fullName').value;
    document.getElementById('previewStudentID').innerText = document.getElementById('studentID').value;
    document.getElementById('previewBirthDate').innerText = document.getElementById('birthDate').value;
    document.getElementById('previewSex').innerText = document.getElementById('sex').value;
    document.getElementById('previewReligion').innerText = document.getElementById('religion').value;
    document.getElementById('previewStatus').innerText = document.getElementById('status').value;
    document.getElementById('previewAddress').innerText = document.getElementById('address').value;
    document.getElementById('previewProgram').innerText = document.getElementById('program').options[document.getElementById('program').selectedIndex].text;

    // Update the Year Level preview
    const yearLevelInput = document.getElementById('year-level');
    if (yearLevelInput) {
        document.getElementById('previewYearLevel').innerText = yearLevelInput.value;
    }

    // Update the Schedule preview
    const scheduleSelect = document.getElementById('schedule');
    if (scheduleSelect && scheduleSelect.selectedIndex > -1) {
        document.getElementById('previewSchedule').innerText = scheduleSelect.options[scheduleSelect.selectedIndex].text;
    }
}


document.getElementById('program').addEventListener('change', fetchSchedules);
document.getElementById('year-level').addEventListener('change', fetchSchedules);

function fetchSchedules() {
    const programId = document.getElementById('program').value;
    const yearLevel = document.getElementById('year-level').value || '1st Year';
    
    if (programId && yearLevel) {
        fetch(`/get-schedules?program_id=${programId}&year_level=${yearLevel}`)
            .then(response => response.json())
            .then(data => {
                const sectionCardsContainer = document.getElementById('sectionCardsContainer');
                const schedulesTableWrapper = document.getElementById('schedulesTableWrapper');

                // Clear previous content
                sectionCardsContainer.innerHTML = '';
                schedulesTableWrapper.innerHTML = '';

                // Display section cards with validation
                for (const sectionId in data.schedules) {
                    const section = data.schedules[sectionId];
                    const isFull = section.enrolled_count >= section.max_enrollment;
                    const isLocked = section.is_locked;

                    const card = document.createElement('div');
                    card.className = 'card m-2';
                    card.style.cursor = isFull || isLocked ? 'not-allowed' : 'pointer';
                    card.style.width = '150px';

                    card.innerHTML = `
                        <div class="card-body text-center">
                            <h5 class="card-title">${section.section_name}</h5>
                            <p>${section.enrolled_count}/${section.max_enrollment} Enrolled</p>
                            ${isLocked ? '<span class="badge bg-danger">Locked</span>' : ''}
                        </div>
                    `;

                    if (!isFull && !isLocked) {
                        card.onclick = () => displaySchedulesTable(section.schedules);
                    } else {
                        card.onclick = () => {
                            displayToastMessage(
                                isLocked ? 'This section is currently locked.' : 
                                'This section is fully occupied. Please select another section.'
                            );
                        };
                    }
                    sectionCardsContainer.appendChild(card);
                }
            })
            .catch(error => {
                console.error('Error fetching schedules:', error);
            });
    }
}

function displayToastMessage(message) {
    const toast = document.createElement('div');
    toast.className = 'toast align-items-center text-bg-warning border-0 position-fixed';
    toast.role = 'alert';
    toast.style.bottom = '1rem'; // Position from the bottom
    toast.style.right = '1rem'; // Position from the right
    toast.style.zIndex = '1055'; // Ensure it appears above other elements

    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    const bootstrapToast = new bootstrap.Toast(toast);
    bootstrapToast.show();

    // Automatically remove the toast from the DOM after it hides
    toast.addEventListener('hidden.bs.toast', () => {
        document.body.removeChild(toast);
    });
}


function displaySchedulesTable(schedules) {
    const container = document.getElementById('schedulesTableWrapper');
    container.innerHTML = ''; // Clear previous table

    const table = document.createElement('table');
    table.classList.add('table', 'table-bordered', 'display', 'dataTable');

    table.innerHTML = `
        <thead>
            <tr>
                <th>COURSE CODE</th>   
                <th>DESCRIPTION</th>
                <th>LECTURE</th>
                <th>LAB</th>
                <th>UNITS</th>
                <th>DAYS</th>
                <th>TIME</th>
                <th>ROOM</th>
                <th>INSTRUCTOR</th>
            </tr>
        </thead>
    `;

    const tbody = document.createElement('tbody');
    schedules.forEach(schedule => {
        const row = `<tr>
            <td>${schedule.subject_code || ''}</td>
            <td>${schedule.subject_description || ''}</td>
            <td>${schedule.subject_lecture || ''}</td>
            <td>${schedule.subject_lab || ''}</td>
            <td>${schedule.subject_units || ''}</td>
            <td>${schedule.days || ''}</td>
            <td>${schedule.time || ''}</td>
            <td>${schedule.room || ''}</td>
            <td>${schedule.teacher_name || ''}</td>
        </tr>`;
        tbody.innerHTML += row;
    });
    table.appendChild(tbody);
    container.appendChild(table);

    $(table).DataTable({
        responsive: true,
        paging: false,
        searching: false
    });
}


// Auto-fetch schedules on page load if fields are pre-filled
window.onload = function() {
    if (document.getElementById('program').value || document.getElementById('year-level').value) {
        fetchSchedules();
    }
    };
  });
</script>


    @endsection
