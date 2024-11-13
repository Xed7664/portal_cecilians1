@extends('layouts.auth')

@section('title', 'Admission')

@section('content')
<!-- Preloader -->
<div id="preloader">
    <div class="loader"></div>
</div>
<div class="container-fluid p-0" id="main-content">
    <!-- Full Header with Logo and Portal Name -->
    <header class="bg-gradient-primary text-white shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand d-flex align-items-center" href="{{ route('welcome') }}">
                    <img src="{{ asset('img/SCC.png') }}" alt="Portal Logo" class="logo me-2">
                    <span class="portal-name">Cecilian College Portal</span>
                </a>
                <!-- Hamburger Menu (Responsive) -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Navigation Links -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admission.tracker') }}">Track Application Status</a>
                        </li>
                        <!-- Add more navigation links if needed -->
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="container py-5">
    <div class="row justify-content-center">
        
    @if ($activeAdmissionPeriod)
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body text-center">
            <h4 class="card-title mb-3 text-success">Active Admission Period</h4>
            <p><strong>School Year:</strong> {{ $activeAdmissionPeriod->schoolYear->name }}</p>
            <p><strong>Semester:</strong> {{ $activeAdmissionPeriod->semester->name }}</p>
            <p><strong>Enrollment Period:</strong> 
                {{ \Carbon\Carbon::parse($activeAdmissionPeriod->open_date)->format('F d, Y') }} 
                to 
                {{ \Carbon\Carbon::parse($activeAdmissionPeriod->close_date)->format('F d, Y') }}
            </p>
        </div>
    </div>
@else
    <div class="alert alert-warning text-center">
        <i class="bi bi-exclamation-circle-fill"></i> No active admission period is currently open.
    </div>
@endif
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg registration-form animate__animated animate__fadeInDown">
                <div class="card-header header-background text-white text-center py-4">
                    <h2 class="animate__animated animate__fadeIn">Admission Application</h2>
                    <p class="animate__animated animate__fadeIn animate__delay-1s">Please fill out the form below to submit your admission application.</p>
                </div>
                <!-- Rest of the form goes here -->
         


                <div class="card-body p-4 bg-light">
             

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInUp" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeInUp" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admission.submit') }}" method="POST" enctype="multipart/form-data" class="needs-validation animate__animated animate__fadeInUp animate__delay-2s" novalidate>
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                                <div class="invalid-feedback">
                                    Please provide your first name.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                                <div class="invalid-feedback">
                                    Please provide your last name.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">
                                    Please provide a valid email address.
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="birthday" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="birthday" name="birthday" required>
                                <div class="invalid-feedback">
                                    Please provide your date of birth.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="" selected disabled>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select your gender.
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="address" name="address" required>
                            <div class="invalid-feedback">
                                Please provide your address.
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="student_type" class="form-label">Student Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="student_type" name="student_type" required>
                                    <option value="" selected disabled>Select Student Type</option>
                                    <option value="new">New Student</option>
                                    <option value="transferee">Transferee</option>
                                    <option value="returnee">Returnee</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select your student type.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="picture" class="form-label">Profile Photo (2x2) <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="picture" name="picture" accept=".jpg,.jpeg,.png" required>
                                <div class="invalid-feedback">
                                    Please upload a valid profile photo.
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="formcard" class="form-label">Form 138 (PDF, JPG, JPEG, PNG)</label>
                            <input type="file" class="form-control" id="formcard" name="formcard" accept=".pdf,.jpg,.jpeg,.png">
                        </div>

                        <div class="mb-3">
                            <label for="certifications" class="form-label">Birth Certificate Authenticated by PSA (PDF, JPG, JPEG, PNG)</label>
                            <input type="file" class="form-control" id="certifications" name="certifications" accept=".pdf,.jpg,.jpeg,.png">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-secondary btn-block mt-4 btn-lg rounded-pill animate__animated animate__pulse">Submit Application</button>
                        </div>
                    </form>

                    <div class="mt-4 text-center animate__animated animate__fadeInUp animate__delay-3s">
                        <p>Already submitted your application? <a href="{{ route('admission.tracker') }}" class="text-primary fw-bold">Track your application status</a> here.</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
  /* Preloader styles */
  #preloader {
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background-color: #ffffff; /* Change to match your theme */
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: opacity 0.5s ease;
}

.loader {
    border: 8px solid #f3f3f3; /* Light gray */
    border-top: 8px solid #800000; /* Maroon */
    border-radius: 50%;
    width: 60px;
    height: 60px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

 #main-content {
    opacity: 0;
    transition: opacity 0.5s ease;
}

.header-background {
    background-image: url('/assets/images/finalhomebg11.png'); /* Path to your image */
    background-size: cover; /* Ensure the image covers the header area */
    background-position: center; /* Center the image */
    color: white; /* Set text color for better contrast */
}

/* Optional: Style for better contrast on the header text */
.card-header h2, .card-header p {
    z-index: 1; /* Ensure text is above the background */
    position: relative; /* Position text relative to the header */
}


 /* Header Styles */
 .bg-gradient-primary {
    background: linear-gradient(90deg, #a12c2f, #7f2022); /* Primary gradient color */
}

    .navbar {
        padding: 0.5rem 0;
    }
    .navbar-brand {
        font-size: 1.5rem;
        font-weight: bold;
    }
    .logo {
        width: 50px;
        height: auto;
    }
    .portal-name {
        color: #fff;
        font-weight: bold;
    }
    .nav-link {
        color: #fff !important;
        font-size: 1rem;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: color 0.3s ease;
    }
    .nav-link:hover {
        color: #f8f9fc !important; /* Light hover effect */
    }
    .navbar-toggler {
        border-color: rgba(255, 255, 255, 0.5);
    }
    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23fff' viewBox='0 0 30 30'%3E%3Cpath stroke='rgba%28255, 255, 255, 0.5%29' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .portal-name {
            font-size: 1.25rem;
        }
    }

    /* Smooth page transition on load */
    body {
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
    }
    body.fade-in {
        opacity: 1;
    }

    /* Button animations */
    .btn {
        transition: transform 0.3s ease-in-out;
    }

    .btn:hover {
        transform: scale(1.05);
    }
</style>
<script>
    // Preloader functionality
    document.addEventListener("DOMContentLoaded", function () {
        window.addEventListener("load", function () {
            var preloader = document.getElementById('preloader');
            preloader.style.opacity = 0;
            setTimeout(function() {
                preloader.style.display = 'none';
                document.getElementById('main-content').style.opacity = 1;
            }, 500); 
        });
    });
</script>
<script>
    // Bootstrap validation example (front-end validation)
    (() => {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()

    // Page Load Transition Effect
    document.addEventListener("DOMContentLoaded", function() {
        document.body.classList.add('fade-in');
    });
</script>

<style>
    /* Smooth page transition on load */
    body {
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
    }
    body.fade-in {
        opacity: 1;
    }

    /* Animations using Animate.css */
    .animate__animated {
        animation-duration: 1.5s;
        animation-fill-mode: both;
    }

    /* Smooth form field transitions */
    .form-control {
        transition: box-shadow 0.3s, border-color 0.3s;
    }

    .form-control:focus {
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
        border-color: rgba(0, 123, 255, 0.8);
    }

    /* Button animations */
    .btn {
        transition: transform 0.3s ease-in-out;
    }

    .btn:hover {
        transform: scale(1.05);
    }
</style>
@endsection
