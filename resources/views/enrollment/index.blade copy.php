<!-- resources/views/enrollment/index.blade.php -->

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

    <!-- Multi-step Enrollment Form -->
    <section class="section enrollment">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <h5 class="card-title">Complete Your Enrollment</h5>

                        <!-- Step Indicators (Progress bar) -->
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar" role="progressbar" style="width: 33%;" id="progress-bar">Step 1 of 3</div>
                        </div>

                        <!-- Multi-step Form -->
                        <form id="enrollmentForm" class="mt-4">

                            <!-- Step 1: Personal Information -->
                            <div class="step step-1">
                                <h6>Step 1: Personal Information</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="studentId">ID No.</label>
                                        <input type="text" id="studentId" class="form-control" placeholder="Enter your ID">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="semester">Semester/Term</label>
                                        <input type="text" id="semester" class="form-control" placeholder="Enter semester/term">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label for="name">Name (Surname, First Name, Middle Name)</label>
                                        <input type="text" id="name" class="form-control" placeholder="Enter full name">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="birthdate">Birthdate</label>
                                        <input type="date" id="birthdate" class="form-control">
                                    </div>
                                </div>

                                <!-- Additional personal information fields -->
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label for="homeAddress">Home Address</label>
                                        <input type="text" id="homeAddress" class="form-control" placeholder="Enter home address">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="previousSchool">Previous School Attended</label>
                                        <input type="text" id="previousSchool" class="form-control" placeholder="Enter previous school">
                                    </div>
                                </div>

                                <!-- Navigation buttons -->
                                <div class="mt-4 d-flex justify-content-end">
                                    <button type="button" class="btn btn-primary" id="nextToStep2">Next</button>
                                </div>
                            </div>

                            <!-- Step 2: Course Enrollment -->
                            <div class="step step-2 d-none">
                                <h6>Step 2: Course Enrollment</h6>
                                
                                <!-- Table for subject schedule input -->
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Course Code</th>
                                                <th>Course Description</th>
                                                <th>Units</th>
                                                <th>Days</th>
                                                <th>Time</th>
                                                <th>Room</th>
                                                <th>Instructor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Example input fields for subjects -->
                                            <tr>
                                                <td><input type="text" class="form-control" placeholder="Enter course code"></td>
                                                <td><input type="text" class="form-control" placeholder="Enter course description"></td>
                                                <td><input type="number" class="form-control" placeholder="Units"></td>
                                                <td><input type="text" class="form-control" placeholder="Days"></td>
                                                <td><input type="text" class="form-control" placeholder="Time"></td>
                                                <td><input type="text" class="form-control" placeholder="Room"></td>
                                                <td><input type="text" class="form-control" placeholder="Instructor"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Navigation buttons -->
                                <div class="mt-4 d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" id="backToStep1">Back</button>
                                    <button type="button" class="btn btn-primary" id="nextToStep3">Next</button>
                                </div>
                            </div>

                            <!-- Step 3: Confirmation & Signature -->
                            <div class="step step-3 d-none">
                                <h6>Step 3: Confirmation</h6>

                                <p>Please review your information and confirm your enrollment.</p>

                                <!-- Signature fields -->
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <label for="applicantSignature">Applicant's Signature Over Printed Name</label>
                                        <input type="text" id="applicantSignature" class="form-control" placeholder="Enter signature">
                                    </div>
                                </div>

                                <!-- Approval signatures -->
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <label for="deanSignature">Dean's Approval</label>
                                        <input type="text" id="deanSignature" class="form-control" placeholder="Dean's signature">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="registrarSignature">Registrar's Approval</label>
                                        <input type="text" id="registrarSignature" class="form-control" placeholder="Registrar's signature">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="cashierSignature">Cashier's Approval</label>
                                        <input type="text" id="cashierSignature" class="form-control" placeholder="Cashier's signature">
                                    </div>
                                </div>

                                <!-- Submit button -->
                                <div class="mt-4 d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" id="backToStep2">Back</button>
                                    <button type="submit" class="btn btn-success" id="submitEnrollment">Submit Enrollment</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        
    </section>
</main>

<!-- Preview Modal -->
<div class="modal fade" id="enrollmentPreviewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="previewModalLabel"><strong>Enrollment Preview</strong></h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <!-- Printable Form Preview -->
                <div id="printableEnrollmentForm">
                    <!-- Form Title -->
                    <div class="text-center mb-4">
                        <img src="{{ asset('img/scclogo.png') }}" alt="School Logo" style="width: 80px; height: 80px;">
                        <h3><strong>St. Cecilia's College-Cebu, Inc.</strong></h3>
                        <h4><i>De La Salle Supervised School</i></h4>
                        <h5>HIGHER EDUCATION DEPARTMENT</h5>
                    </div>
                    
                    <hr class="mb-4">
 <!-- Enrollment Details for Student and School -->
 <div class="enrollment-details">
                        <!-- For Student -->
                        <div class="text-center mb-4">
                            <h6 class="text-primary"><strong>Enrollment Details (Student Copy)</strong></h6>
                        </div>

                        <!-- Student Information Section -->
                        <h6 class="text-primary"><strong>Personal Information</strong></h6>
                        <div class="row">
                            <div class="col-6">
                                <p><strong>ID No.:</strong> <span id="previewStudentId">123456</span></p>
                                <p><strong>Semester/Term:</strong> <span id="previewSemester">Fall 2024</span></p>
                                <p><strong>Academic Year:</strong> <span id="previewAcademicYear">2024-2025</span></p>
                                <p><strong>Year Level:</strong> <span id="previewYearLevel">2nd Year</span></p>
                                <p><strong>Grade/Program:</strong> <span id="previewGradeProgram">BS Computer Science</span></p>
                                <p><strong>Mobile Number:</strong> <span id="previewMobileNumber">09123456789</span></p>
                            </div>
                            <div class="col-6">
                                <p><strong>Name:</strong> <span id="previewName">John Doe</span></p>
                                <p><strong>Birthdate:</strong> <span id="previewBirthdate">01/01/2000</span></p>
                                <p><strong>Sex:</strong> <span id="previewSex">Male</span></p>
                                <p><strong>Religion:</strong> <span id="previewReligion">Catholic</span></p>
                                <p><strong>Status:</strong> <span id="previewStatus">Single</span></p>
                                <p><strong>Place of Birth:</strong> <span id="previewPlaceOfBirth">Cebu City</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p><strong>Home Address:</strong> <span id="previewHomeAddress">1234 Elm St</span></p>
                                <p><strong>Previous School Attended:</strong> <span id="previewPreviousSchool">ABC High School</span></p>
                            </div>
                            <div class="col-6">
                                <p><strong>Previous School Address:</strong> <span id="previewPreviousSchoolAddress">XYZ Street</span></p>
                                <p><strong>Father's Name:</strong> <span id="previewFathersName">John Doe Sr.</span></p>
                                <p><strong>Father's Occupation:</strong> <span id="previewFathersOccupation">Engineer</span></p>
                                <p><strong>Mother's Name/Guardian:</strong> <span id="previewMothersName">Jane Doe</span></p>
                                <p><strong>Mother's Occupation:</strong> <span id="previewMothersOccupation">Teacher</span></p>
                            </div>
                        </div>

                        <!-- Course Enrollment Section -->
                        <h6 class="text-primary"><strong>Course Enrollment</strong></h6>
                        <table class="table table-bordered table-striped">
                            <thead class="table-primary">
                                <tr>
                                    <th>Course Code</th>
                                    <th>Description</th>
                                    <th>Units</th>
                                    <th>Days</th>
                                    <th>Time</th>
                                    <th>Room</th>
                                    <th>Instructor</th>
                                </tr>
                            </thead>
                            <tbody id="previewCourses">
                                <tr>
                                    <td>CS101</td>
                                    <td>Introduction to Programming</td>
                                    <td>3</td>
                                    <td>MWF</td>
                                    <td>9:00 AM - 10:30 AM</td>
                                    <td>Room 201</td>
                                    <td>Dr. Smith</td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Signature Section -->
                        <h6 class="text-primary"><strong>Signatures</strong></h6>
                        <div class="row">
                            <div class="col text-center">
                                <p><strong>Applicant's Signature:</strong></p>
                                <p>______________________________</p>
                                <p id="previewApplicantSignature">John Doe</p>
                            </div>
                            <div class="col text-center">
                                <p><strong>Dean's Approval:</strong></p>
                                <p>______________________________</p>
                                <p id="previewDeanSignature">Dean Jane</p>
                            </div>
                            <div class="col text-center">
                                <p><strong>Registrar's Approval:</strong></p>
                                <p>______________________________</p>
                                <p id="previewRegistrarSignature">Registrar Tom</p>
                            </div>
                            <div class="col text-center">
                                <p><strong>Cashier's Approval:</strong></p>
                                <p>______________________________</p>
                                <p id="previewCashierSignature">Cashier Anne</p>
                            </div>
                        </div>
                   

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="printEnrollmentForm">Print</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Add CSS for improved design -->
<style>
    .modal-header {
        background-color: #004085;
    }
    .modal-title {
        font-weight: bold;
    }
    .table-bordered {
        border: 1px solid #dee2e6;
    }
    .table-bordered th, .table-bordered td {
        text-align: center;
        padding: 12px;
    }
    .table-primary {
        background-color: #007bff;
        color: white;
    }
    .text-primary {
        color: #004085;
    }
    h6 {
        font-size: 18px;
        font-weight: bold;
    }
    h3, h4, h5 {
        font-weight: bold;
        margin-bottom: 10px;
    }
    hr {
        border-top: 2px solid #dee2e6;
    }
</style>


<!-- Add JavaScript for handling next/back functionality -->
<script>
   document.addEventListener('DOMContentLoaded', function () {
    const steps = document.querySelectorAll('.step');
    const progressBar = document.getElementById('progress-bar');
    let currentStep = 0;

    // Handle next and back buttons
    document.getElementById('nextToStep2').addEventListener('click', function () {
        steps[currentStep].classList.add('d-none');
        currentStep++;
        steps[currentStep].classList.remove('d-none');
        progressBar.style.width = '66%';
        progressBar.textContent = 'Step 2 of 3';
    });

    document.getElementById('nextToStep3').addEventListener('click', function () {
        steps[currentStep].classList.add('d-none');
        currentStep++;
        steps[currentStep].classList.remove('d-none');
        progressBar.style.width = '100%';
        progressBar.textContent = 'Step 3 of 3';
    });

    document.getElementById('backToStep1').addEventListener('click', function () {
        steps[currentStep].classList.add('d-none');
        currentStep--;
        steps[currentStep].classList.remove('d-none');
        progressBar.style.width = '33%';
        progressBar.textContent = 'Step 1 of 3';
    });

    document.getElementById('backToStep2').addEventListener('click', function () {
        steps[currentStep].classList.add('d-none');
        currentStep--;
        steps[currentStep].classList.remove('d-none');
        progressBar.style.width = '66%';
        progressBar.textContent = 'Step 2 of 3';
    });

    // Handle Submit Enrollment (show preview modal)
    document.getElementById('submitEnrollment').addEventListener('click', function (event) {
        event.preventDefault();

        // Collect form data
        document.getElementById('previewStudentId').textContent = document.getElementById('studentId').value;
        document.getElementById('previewName').textContent = document.getElementById('name').value;
        document.getElementById('previewSemester').textContent = document.getElementById('semester').value;
        document.getElementById('previewBirthdate').textContent = document.getElementById('birthdate').value;
        document.getElementById('previewHomeAddress').textContent = document.getElementById('homeAddress').value;
        document.getElementById('previewPreviousSchool').textContent = document.getElementById('previousSchool').value;

        // Signatures
        document.getElementById('previewApplicantSignature').textContent = document.getElementById('applicantSignature').value;
        document.getElementById('previewDeanSignature').textContent = document.getElementById('deanSignature').value;
        document.getElementById('previewRegistrarSignature').textContent = document.getElementById('registrarSignature').value;
        document.getElementById('previewCashierSignature').textContent = document.getElementById('cashierSignature').value;

        // Show the modal
        new bootstrap.Modal(document.getElementById('enrollmentPreviewModal')).show();
    });

    // Print functionality
    document.getElementById('printEnrollmentForm').addEventListener('click', function () {
        const printContents = document.getElementById('printableEnrollmentForm').innerHTML;
        const originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();  // Reload the page after printing to restore the content
    });
});

</script>
@endsection
