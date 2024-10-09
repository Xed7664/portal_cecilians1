@extends('layouts.auth')

@section('title', 'Register')

@section('content')

<div class="container">
    <section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="d-none d-lg-flex col-lg-7 p-0">
                <div class="d-flex justify-content-center align-items-center">
                    <img src="{{ asset('img/gif/login.gif') }}" alt="auth-login-cover" class="img-fluid my-5 pt-5">
                </div>
            </div>
            <div class="col-lg-5 col-md-6 d-flex flex-column">
                <div class="d-flex justify-content-center mt-4">
                    <a href="../" class="logo d-flex align-items-center w-auto">
                    <img src="{{ asset('img/SCC.png') }}" alt="St. Cecilia's College - Cebu, Inc. Logo" style="max-height: 50px; margin-right: 10px;">
                    <span class="d-none d-lg-block text-danger fs-2">Cecilian Portal</span>
                    </a>
                </div>
                <div class="card mb-3 mt-2">
                    <div class="card-header bg-danger text-white text-center">
                        <span class="fw-bold text-uppercase">User Registration </span>
                    </div>
                    <div class="card-body pt-4 border border-danger border-opacity-25 border-25 border-top-0 bg-body-tertiary">

                        <div id="response">

                            @if(session()->has('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{session('error')}}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if(session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{session('success')}}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('registration.post') }}" class="row g-3 needs-validation" novalidate>
                            @csrf
                            <div class="col-12">
                                <label class="form-label">School ID</label>
                                <div class="input-group">
                                    <span class="input-group-text">SCC-</span>
                                    <input type="text" class="form-control @error('schoolid') is-invalid @enderror" name="schoolid" value="{{ old('schoolid') }}" required>
                                    @error('schoolid')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                    <div class="invalid-feedback">Please enter your School ID.</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Birthdate</label>
                                <input type="date" name="birthdate" class="form-control @error('birthdate') is-invalid @enderror" value="{{ old('birthdate') }}" required>
                                @error('birthdate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Please enter your birthdate.</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Please enter a valid username.</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Please enter your email.</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control toggle-password @error('password') is-invalid @enderror" value="{{ old('password') }}" required>
                                    <button class="btn btn-outline-secondary toggle-password-button" type="button">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">Please enter a valid password.</div>
                                    @enderror
                                </div>
                                <div class="password-criteria visually-hidden py-3">
                                    <div class="password-criteria-item">
                                        <span class="criteria-check">
                                        <i class="bi bi-check-circle text-secondary"></i>
                                        </span>
                                        <span class="criteria-text">At least 8 characters</span>
                                    </div>
                                    <div class="password-criteria-item">
                                        <span class="criteria-check">
                                        <i class="bi bi bi-check-circle text-secondary"></i>
                                        </span>
                                        <span class="criteria-text">At least one uppercase letter</span>
                                    </div>
                                    <div class="password-criteria-item">
                                        <span class="criteria-check">
                                        <i class="bi bi-check-circle text-secondary"></i>
                                        </span>
                                        <span class="criteria-text">At least one lowercase letter</span>
                                    </div>
                                    <div class="password-criteria-item">
                                        <span class="criteria-check">
                                        <i class="bi bi-check-circle text-secondary"></i>
                                        </span>
                                        <span class="criteria-text">At least one number</span>
                                    </div>
                                    <div class="password-criteria-item">
                                        <span class="criteria-check">
                                            <i class="bi bi-check-circle text-secondary"></i>
                                        </span>
                                        <span class="criteria-text">At least one special character</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="terms_agreed" value="true" id="rememberMe" required>
                                <label class="form-check-label">I agree to <a href="#" class="text-danger fw-semibold">privacy policy & terms</a></label>
                                </div>
                            </div>

                            <div class="col-12 mb-2">
                                <button class="btn btn-danger w-100" type="submit">Sign Up</button>
                            </div>
                        
                            <hr class="border border-50 opacity-50 mb-0">

                            <p class="text-center mb-0">
                                <span>Already have an account?</span>
                                <a href="{{ route('login') }}">
                                    <span class="text-danger fw-semibold">Login instead</span>
                                </a>
                            </p>

                        </form>

                    </div>
                </div>


            </div>
        </div>
    </div>

    </section>
</div>

<script>
$(document).ready(function() {

    // Function to check username criteria
    function checkUsernameCriteria(username) {
        const usernameRegex = /^[A-Za-z0-9_.]+$/;
        return username.length >= 3 && username.length <= 30 && usernameRegex.test(username);
    }

    // Attach event handlers to the username input field
    const usernameInput = $('#username');
    const feedbackContainer = usernameInput.closest('.col-12');
    const feedbackElement = feedbackContainer.find('.invalid-feedback');

    usernameInput.on('input', function() {
        const username = $(this).val();
        const isValid = checkUsernameCriteria(username);

        if (isValid) {
            feedbackElement.hide();
            $(this).removeClass('is-invalid');
            $(this).addClass('is-valid');
            $('#submitBtn').prop('disabled', false); // Enable submit button
        } else {
            feedbackElement.show();
            $(this).removeClass('is-valid');
            $(this).addClass('is-invalid');
            $('#submitBtn').prop('disabled', true); // Disable submit button
        }
    });

    // Function to check password criteria
    function checkPasswordCriteria(password) {
        const criteria = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /\d/.test(password),
            special: /[!@#$%^&*()_+\-=[\]{};:'"\\|,.<>/?]/.test(password)
        };

        return criteria;
    }

    // Attach event handlers to the password input field
    const passwordInput = $('.toggle-password');
    const criteriaContainer = $('.password-criteria');
    
    passwordInput.focus(function() {
        criteriaContainer.removeClass('visually-hidden');
    });

    passwordInput.blur(function() {
        criteriaContainer.addClass('visually-hidden');
    });

    passwordInput.on('input', function() {
        const password = $(this).val();
        const criteria = checkPasswordCriteria(password);

        // Show/hide criteria and update check marks
        const criteriaItems = criteriaContainer.find('.password-criteria-item');
        criteriaItems.each(function(index, item) {
            const checkElement = $(item).find('.criteria-check i');
            const criteriaText = $(item).find('.criteria-text');
            const isMet = criteria[Object.keys(criteria)[index]];
            const iconClass = isMet ? 'bi-check-circle-fill text-success' : 'bi-check-circle text-secondary';

            checkElement.removeClass().addClass('bi ' + iconClass);
            criteriaText.removeClass('text-success text-dark').addClass(isMet ? 'text-success' : 'text-dark');
        });

        // Password validation
        const isValid = Object.values(criteria).every(Boolean);
        const feedbackContainer = $(this).closest('.col-12');
        const feedbackElement = feedbackContainer.find('.invalid-feedback');
        const validFeedbackElement = feedbackContainer.find('.valid-feedback');
        
        if (isValid) {
            feedbackElement.hide();
            validFeedbackElement.show();
            $(this).removeClass('is-invalid');
            $(this).addClass('is-valid');
            $('#submitBtn').prop('disabled', false); // Enable submit button
        } else {
            feedbackElement.show();
            validFeedbackElement.hide();
            $(this).removeClass('is-valid');
            $(this).addClass('is-invalid');
            $('#submitBtn').prop('disabled', true); // Disable submit button
        }
    });
});

</script>

@endsection
