@extends('layouts.auth')

@section('title', 'Email Verification')

@section('content')
<style>
    .container1 {
        width: 100%;
        height:100vh;
        display:flex;
        justify-content:center;
        align-items:center;
    }
  
</style>
<div class="container1">
    <section class="section">
    <div class="container">
        <div class="row justify-content-center">
          
            <div class="col-lg-5 col-md-6 d-flex flex-column">
                <div class="d-flex justify-content-center mt-4">
                    <a href="../" class="logo d-flex align-items-center w-auto">
                    <img src="{{ asset('img/SCC.png') }}" alt="St. Cecilia's College - Cebu, Inc. Logo" style="max-height: 50px; margin-right: 10px;">
                    <span class="d-none d-lg-block text-danger fs-2">Cecilian Portal</span>
                    </a>
                </div>
                <div class="card mb-3 mx-0 mt-2">
                    <div class="card-header bg-danger text-white text-center">
                        <span class="fw-bold text-uppercase">Email Verification</span>
                    </div>
                    @livewire('auth.verify-email-form')

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
    const usernameInput = $('.username');
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
