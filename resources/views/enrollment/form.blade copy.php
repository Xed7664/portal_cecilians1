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
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 33%" id="progressBar">
                Step 1 of 3
            </div>
        </div>

        <!-- Multi-Step Form -->
        <form action="{{ route('enrollment.store') }}" method="POST" class="enrollment-form shadow-lg p-4">

            @csrf
              <!-- School Information Header -->
        <div class="text-center mb-4">
            <img src="{{ asset('img/scclogo.png') }}" alt="School Logo" style="width: 100px;">
            <h2>{{ config('app.school_name', 'St.Cecilias College-Cebu, Inc.') }}</h2>
            <p>{{ config('app.school_address', 'Poblacion Ward II, Minglanilla, Cebu') }}</p>
            <h3>Enrollment Form</h3>
        </div>

          <!-- Step 1: Personal Information -->
<div class="step" id="step1">
    <h3 class="section-title text-primary">Step 1: Personal Information</h3>
    
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
     <!-- Home Address information -->
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
</div>


        <!-- Step 2: Course Enrollment -->
<div class="step" id="step2" style="display:none;">
    <h3 class="section-title text-primary">Step 2: Course Enrollment</h3>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="department">Select Department</label>
            <select id="department" name="department_id" class="form-control" required>
                <option value="">Select Department</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 form-group">
            <label for="yearLevel">Select Year Level</label>
            <select id="yearLevel" name="year_level_id" class="form-control" required>
                <option value="">Select Year Level</option>
                @foreach ($yearLevels as $yearLevel)
                    <option value="{{ $yearLevel->id }}">{{ $yearLevel->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 form-group">
            <label for="section">Select Section</label>
            <select id="section" name="section_id" class="form-control" required onchange="loadBlockSchedule()">
                <option value="">Select Section</option>
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Block Schedule Calendar -->
    <h4 class="mt-4">Block Schedule</h4>
    <div id="blockScheduleCalendar"></div>

    <!-- Subject Table -->
    <h4 class="mt-4">Subject List</h4>
    <table id="subjectTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Course Description</th>
                <th>UNITS</th>
                <th>Days</th>
                <th>Time</th>
                <th>Room</th>
                <th>Instructor</th>
            </tr>
        </thead>
        <tbody>
            <!-- Dynamic content from script -->
        </tbody>
    </table>
</div>


            <!-- Step 3: Condition of Payment -->
            <div class="step" id="step3" style="display:none;">
                <h3 class="section-title text-primary">Step 3: Condition of Payment</h3>
                <p class="mb-4">
                    The student must adhere to the following payment conditions set by the school. Please review these terms carefully...
                    <!-- Long terms and conditions text here -->
                </p>
            </div>

            <!-- Navigation Buttons -->
            <div class="form-group text-center mt-4">
                <button type="button" class="btn btn-secondary" id="prevBtn" onclick="prevStep()" style="display:none;">Back</button>
                <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextStep()">Next</button>
                <button type="submit" class="btn btn-success btn-lg" id="submitBtn" style="display:none;">Submit Enrollment</button>
            </div>
        </form>
    </div>
</main>


<style>
    .custom-input {
    background-color: #f9f9f9;
    border: 1px solid #ced4da;
    padding: 10px;
    border-radius: 5px;
    transition: border-color 0.3s ease;
}

.custom-input:focus {
    border-color: #80bdff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

/* Add space between labels and inputs */
label {
    margin-bottom: 5px;
}

/* Make cards and fields contrast more visible */
.form-group {
    margin-bottom: 15px;
}

</style>
<script>
    // Initialize FullCalendar
    function initializeCalendar(events) {
        let calendarEl = document.getElementById('blockScheduleCalendar');
        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            events: events, // Dynamic events passed from loadBlockSchedule()
        });
        calendar.render();
    }

    // Function to load block schedule and subjects based on section selection
    function loadBlockSchedule() {
        let sectionId = document.getElementById('section').value;

        if (!sectionId) {
            return; // Do nothing if no section is selected
        }

        // Fetch schedule and subjects from the backend
        $.ajax({
            url: '/api/get-section-schedule/' + sectionId, // Backend API route to fetch data
            method: 'GET',
            success: function(response) {
                // Load calendar events (block schedule)
                initializeCalendar(response.schedule); // Assumes response.schedule is an array of events

                // Load subject table
                let subjectTable = $('#subjectTable').DataTable();
                subjectTable.clear(); // Clear previous data
                response.subjects.forEach(function(subject) {
                    subjectTable.row.add([
                        subject.code,
                        subject.description,
                        subject.units,
                        subject.days,
                        subject.time,
                        subject.room,
                        subject.instructor
                    ]).draw();
                });
            },
            error: function(xhr) {
                console.error(xhr);
                alert('Failed to load the block schedule. Please try again later.');
            }
        });
    }

    // Initialize DataTables for subjects
    $(document).ready(function() {
        $('#subjectTable').DataTable();
    });
</script>

<script>
    let currentStep = 1;

    function updateProgressBar() {
        const progressBar = document.getElementById('progressBar');
        const progressText = `Step ${currentStep} of 3`;
        const progressWidth = (currentStep / 3) * 100 + '%';

        progressBar.style.width = progressWidth;
        progressBar.textContent = progressText;
    }

    function showStep(step) {
        document.getElementById(`step${currentStep}`).style.display = 'none';
        document.getElementById(`step${step}`).style.display = 'block';
        currentStep = step;
        updateProgressBar();

        // Toggle Next and Back buttons visibility
        if (currentStep === 1) {
            document.getElementById('prevBtn').style.display = 'none';
            document.getElementById('nextBtn').style.display = 'inline-block';
        } else {
            document.getElementById('prevBtn').style.display = 'inline-block';
        }

        if (currentStep === 3) {
            document.getElementById('nextBtn').style.display = 'none';
            document.getElementById('submitBtn').style.display = 'inline-block';
        } else {
            document.getElementById('nextBtn').style.display = 'inline-block';
            document.getElementById('submitBtn').style.display = 'none';
        }
    }

    function nextStep() {
        if (currentStep < 3) {
            showStep(currentStep + 1);
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            showStep(currentStep - 1);
        }
    }
</script>
@endsection
