@extends('layouts.auth')

@section('title', 'Admission Closed')

@section('content')
<!-- Preloader -->
<div id="preloader">
    <div class="loader"></div>
</div>

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

<!-- Centered Content -->
<div class="d-flex justify-content-center align-items-center min-vh-100 text-center">
    <div>
        <img src="{{ asset('img/svg/no-record.svg')}}" alt="Closed Icon" style="width:500px;">
        <h1>Admission Closed</h1>
        <p>The Admission period is currently closed. Please check back during the designated enrollment period, or contact the administration for more information.</p>
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

    /* Full Height for Vertical Centering */
    .min-vh-100 {
        min-height: 100vh;
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
    // Page Load Transition Effect
    document.addEventListener("DOMContentLoaded", function() {
        document.body.classList.add('fade-in');
    });
</script>

@endsection
