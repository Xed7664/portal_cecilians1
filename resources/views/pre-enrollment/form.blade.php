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
           <!-- Hidden Inputs -->
           <input type="hidden" name="student_type" value="{{ $student->student_type ?? 'N/A' }}">
        <input type="hidden" name="previous_year_level" value="{{ $previousYearLevel }}">
        <input type="hidden" name="student_id" value="{{ $student->StudentID }}">
        <input type="hidden" name="full_name" value="{{ $student->FullName }}">
        <input type="hidden" name="program_id" value="{{ $student->program_id }}">
        <input type="hidden" name="year_level" value="{{ $student->year_level ?? '1st Year' }}">

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
              <h3 class="section-title text-primary">Step 2: Educational Info</h3>
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
                    <button type="button" class="btn btn-secondary prev-step">Previous</button>
                    <button type="button" class="btn btn-primary next-step">Next</button>
                </div>
              <div class="step active" id="step3">
                    <h3 class="section-title text-primary">Step 3: Family Info</h3>
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

                <div class="step active" id="step4">
    <h3 class="section-title text-primary">Step 4: Programs Offered & Year Level</h3>

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
    <label for="program" class="form-label text-dark">
        Program
        <i class="fas fa-info-circle" data-bs-toggle="tooltip" title="Choose your current program. This determines the subjects and curriculum for your course."></i>
    </label>

    <!-- Program Select Field -->
    <select 
        class="form-control text-dark" 
        id="program" 
        name="program_id"
        {{ ($student->student_type == 'new' || $student->student_type == 'transferee') ? '' : 'disabled' }}>
        @foreach($programs as $program)
            <option value="{{ $program->id }}" {{ $student->program_id == $program->id ? 'selected' : '' }}>
                {{ $program->name }}
            </option> 
        @endforeach
    </select>

    @if(!in_array($student->student_type, ['new', 'transferee']))
        <!-- Hidden input to submit the program ID for returning students -->
        <input type="hidden" name="program_id" value="{{ $student->program_id }}">
    @endif

    <!-- Toggle Button for Program Change -->
    @if($student->program_id && $student->student_type != 'new' && $student->student_type != 'transferee')
        <button type="button" class="btn btn-outline-primary mt-2" id="changeProgramButton">
            Change Program
        </button>
    @endif
</div>

<button type="button" class="btn btn-secondary prev-step">Previous</button>
<button type="button" class="btn btn-primary next-step">Next</button>

</div>
<div class="step active" id="step5"> 
    <h3 class="section-title text-primary">Step 5: Class Schedule</h3>
    <div id="schedule-container">
        <p>Select a program and year level to view the schedule by section.</p>

        <!-- Section Cards Container -->
        <div id="sectionCardsContainer" class="d-flex flex-wrap mb-3"></div>

        <!-- Schedules Table -->
        <div id="schedulesTableWrapper" class="table-responsive">
            <!-- Dynamic table will be injected here -->
        </div>
        
        <!-- Hidden input to store the selected schedule ID -->
        <input type="hidden" name="schedule" id="selectedScheduleId" value="">
    </div>
    <button type="button" class="btn btn-secondary prev-step">Previous</button>
    <button type="submit" class="btn btn-primary">Submit Pre-Enrollment</button>
</div>

        </div> 
    </form>
    @if(session('pdf_path'))
<div class="modal" id="confirmationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enrollment Completed</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Your pre-enrollment was successfully submitted!</p>
                <a href="{{ route('download.preenrollment.pdf') }}" target="_blank" class="btn btn-primary">Download PDF Summary</a>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
        $('#confirmationModal').modal('show');
    });
</script>
@endif
<div id="toastContainer" class="position-fixed" style="top: 60px; right: 0; z-index: 1055; padding: 1rem;"></div>

</div>


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
            // Enable the program select field for returning students
            if (programSelect.hasAttribute('disabled')) {
                programSelect.removeAttribute('disabled');
            } else {
                programSelect.setAttribute('disabled', 'disabled');
            }
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
    let selectedSchedule = null; // Store the selected schedule

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
                if (currentStep === 5) updatePreview(); // Trigger update for Step 5 preview
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

    document.getElementById('program').addEventListener('change', fetchSchedules);
document.getElementById('year-level').addEventListener('change', fetchSchedules);
// Function to fetch schedules and initialize SSE listener
function fetchSchedules() {
    const programId = document.getElementById('program').value;
    const yearLevel = document.getElementById('year-level').value || '1st Year';

    if (programId && yearLevel) {
        // Fetch the schedules as usual
        fetch(`/get-schedules?program_id=${programId}&year_level=${yearLevel}`)
            .then(response => response.json())
            .then(data => {
                const sectionCardsContainer = document.getElementById('sectionCardsContainer');
                const schedulesTableWrapper = document.getElementById('schedulesTableWrapper');

                sectionCardsContainer.innerHTML = '';
                schedulesTableWrapper.innerHTML = '';

                for (const sectionId in data.schedules) {
                    const section = data.schedules[sectionId];
                    const isFull = section.enrolled_count >= section.max_enrollment;
                    const isLocked = section.is_locked; // Directly access lock status for this section-year level

                    // Create the card for each section
                    const card = document.createElement('div');
                    card.className = 'card m-2';
                    card.style.cursor = isFull || isLocked ? 'not-allowed' : 'pointer';
                    card.style.width = '150px';

                    // Add card HTML with lock status badge
                    card.innerHTML = `
                        <div class="card-body text-center">
                            <h5 class="card-title">${section.section_name}</h5>
                            <p>${section.enrolled_count}/${section.max_enrollment} Enrolled</p>
                            ${isLocked ? '<span class="badge bg-danger">Locked</span>' : ''}
                        </div>
                    `;

                    // Set card behavior based on full and lock status
                    if (!isFull && !isLocked) {
                        card.onclick = () => {
                            selectedSchedule = section; // Store selected section
                            document.getElementById('selectedScheduleId').value = section.schedules[0].id; // Set schedule ID
                            displaySchedulesTable(section.schedules);
                        };
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

        // Listen for SSE updates on section lock status
        const sectionLockUpdates = new EventSource(`/phead/section-lock/updates?program_id=${programId}&year_level=${yearLevel}`);

        sectionLockUpdates.onmessage = function(event) {
            const updates = JSON.parse(event.data);

            updates.forEach(update => {
                const sectionCard = document.querySelector(`[data-section-id="${update.section_id}"]`);

                if (sectionCard) {
                    const lockStatusElement = sectionCard.querySelector('.lock-status');
                    lockStatusElement.textContent = update.is_locked ? 'Locked' : 'Unlocked';
                    sectionCard.classList.toggle('locked', update.is_locked);
                    sectionCard.style.cursor = update.is_locked ? 'not-allowed' : 'pointer';  // Update cursor based on lock status
                }
            });
        };

        sectionLockUpdates.onerror = function(event) {
            console.error('Error with SSE connection:', event);
            sectionLockUpdates.close(); // Close the connection if there's an error
        };
    }
}


function displaySchedulesTable(schedules) {
    const container = document.getElementById('schedulesTableWrapper');
    container.innerHTML = ''; // Clear existing content

    // Create the table element
    const table = document.createElement('table');
    table.classList.add('table', 'table-striped', 'table-bordered', 'display', 'w-100');

    // Define the table structure
    table.innerHTML = `
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Description</th>
                <th>Lecture</th>
                <th>Lab</th>
                <th>Units</th>
                <th>Days</th>
                <th>Time</th>
                <th>Room</th>
                <th>Instructor</th>
            </tr>
        </thead>
        <tbody>
            ${schedules.map(schedule => `
                <tr>
                    <td>${schedule.subject_code || ''}</td>
                    <td>${schedule.subject_description || ''}</td>
                    <td>${schedule.subject_lecture || ''}</td>
                    <td>${schedule.subject_lab || ''}</td>
                    <td>${schedule.subject_units || ''}</td>
                    <td>${schedule.days || ''}</td>
                    <td>${schedule.time || ''}</td>
                    <td>${schedule.room || ''}</td>
                    <td>${schedule.teacher_name || ''}</td>
                </tr>
            `).join('')}
        </tbody>
    `;

    // Append the table to the container
    container.appendChild(table);

    // Initialize DataTables
    if ($.fn.DataTable.isDataTable(table)) {
        $(table).DataTable().destroy();
    }
    $(table).DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        language: {
            emptyTable: "No schedules available. Please select a program and year level."
        }
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
<script>
    function displayToastMessage(message) {
    const toastContainer = document.getElementById('toastContainer');
    const toastId = `toast-${Date.now()}`;

    const toastHTML = `
        <div class="toast align-items-center text-white bg-primary border-0 mb-2" id="${toastId}" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

    // Append toast to the container
    toastContainer.insertAdjacentHTML('beforeend', toastHTML);

    // Initialize and show the toast
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement);
    toast.show();

    // Automatically remove the toast from DOM after it's hidden
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}

</script>
    @endsection
