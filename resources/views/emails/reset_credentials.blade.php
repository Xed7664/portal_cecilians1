    
  @extends('layouts.auth')
  
  @section('title', 'Reset Credentials')
  
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
                      <img src="{{ asset('img/SCC.png') }}" alt="Portal Logo" class="logo me-2" />
                      <span class="portal-name">Cecilian College Portal</span>
                  </a>
              </div>
          </nav>
      </header>
  
      <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-image text-white text-center py-3">
                        <h3 class="mb-0">Reset Your Credentials</h3>
                    </div>
                    <div class="card-body p-4">
                        @if(session('error'))
                            <div class="alert alert-danger text-center">{{ session('error') }}</div>
                        @endif

                        <form method="POST" action="{{ route('update.credentials', ['id' => $user->id]) }}">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3 position-relative">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" name="username" value="{{ old('username', $user->username) }}" required />
                                </div>
                                @error('username')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" name="password" placeholder="Password must be at least 8 characters" required />
                                </div>
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4 position-relative">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm password" required />
                                </div>
                                @error('password_confirmation')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-sync-alt me-2"></i> Update Credentials
                                </button>
                            </div>
                        </form>
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
  
      /* Header Styles */
      .bg-gradient-primary {
          background: linear-gradient(90deg, #a12c2f, #7f2022);
      }
      .navbar {
          padding: 0.5rem 0;
      }
      .navbar-brand {
          font-size: 1.25rem;
          font-weight: bold;
      }
      .logo {
          width: 40px;
          height: auto;
      }
      .portal-name {
          color: #fff;
          font-weight: bold;
      }
  
      /* Card Styles */
      .card {
          border: none;
          border-radius: 15px;
          overflow: hidden;
      }
      .card-header {
          border-bottom: none;
      }
      .card-header h3 {
          font-size: 1.5rem;
      }
      .bg-image {
          background-image: url('/assets/images/finalhomebg11.png');
          background-size: cover;
          background-position: center;
      }
      .btn-primary {
          background-color: #800000;
          border-color: #800000;
          font-size: 0.9rem;
          padding: 0.375rem 0.75rem;
      }
      .btn-primary:hover {
          background-color: #600000;
          border-color: #600000;
      }
  
      /* Responsive Adjustments */
      @media (max-width: 768px) {
          .portal-name {
              font-size: 1rem;
          }
          .card-header h3 {
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
  </style>
  
  <script>
      // Page Load Transition Effect
      document.addEventListener("DOMContentLoaded", function() {
          document.body.classList.add('fade-in');
      });
  
      document.addEventListener("DOMContentLoaded", function () {
          window.addEventListener("load", function () {
              // Hide the preloader after the page is fully loaded
              var preloader = document.getElementById('preloader');
              preloader.style.opacity = 0;
              setTimeout(function() {
                  preloader.style.display = 'none';
                  document.getElementById('main-content').style.opacity = 1;
              }, 500); // Adjust delay if needed
          });
      });
  </script>
  @endsection
   