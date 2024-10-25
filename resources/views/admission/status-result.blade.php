@extends('layouts.auth')

@section('title', 'Admission Status Result')

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
            <div class="col-md-8">
                <div class="card shadow-lg border border-danger border-opacity-25 rounded-lg animate__animated animate__fadeInDown registration-form bg-body-tertiary">
                    <div class="card-header header-background text-white text-center py-4">
                        <h2 class="mb-0 animate__animated animate__fadeIn">Admission Status for {{ $admission->full_name }}</h2>
                    </div>
                    <div class="card-body p-4 bg-light">
                        <div class="mb-3">
                            <p><strong>Email:</strong> {{ $admission->email }}</p>
                        </div>
                        <div class="mb-3">
                            <p><strong>Tracker Code:</strong> {{ $admission->tracker_code }}</p>
                        </div>
                        <div class="mb-3">
                            <p><strong>Status:</strong> 
                                <span class="badge {{ $admission->status == 'approved' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($admission->status) }}
                                </span>
                            </p>
                        </div>
                        <a href="{{ route('admission.form') }}" class="btn btn-secondary btn-block mt-4">
                            Submit Another Application
                        </a>
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
    background-color: #ffffff; 
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: opacity 0.5s ease;
}

.loader {
    border: 8px solid #f3f3f3;
    border-top: 8px solid #800000; 
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
        background: linear-gradient(90deg, #a12c2f, #7f2022); /* Updated primary gradient color */
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

    /* Card Styles */
    .admission-tracker {
        border-radius: 10px;
    }
    .text-maroon {
        color: #800000; /* Maroon color */
    }

    /* Button animations */
    .btn {
        transition: transform 0.3s ease-in-out;
    }

    .btn:hover {
        transform: scale(1.05);
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

.badge {
    font-size: 1rem;
    padding: 0.4rem 0.6rem;
    border-radius: 0.5rem;
}

@media (max-width: 768px) {
    .card-header h2 {
        font-size: 1.5rem;
    }
}
</style>

<script>
    // Preloader and page load effect
    document.addEventListener("DOMContentLoaded", function() {
        document.body.classList.add('fade-in');
    });

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
@endsection
