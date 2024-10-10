@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">

<style>
    .rowcon {
        width: 88.5% !important;
        position: relative;
        overflow:hidden;
    }
    .regform {
        width: 35.7%;
        position:absolute;
        right:78px;
    }
    .card-body {
    /* scrollbar-width: thin; 
    scrollbar-color: rgb(90,90,90,0.1) !important;  */
    max-height: 46.1vh;
    text-align:left;
    }

    .card-body {
    overflow-y: auto; 
    }

    
    .card-body::-webkit-scrollbar {
    width: 8px;
    }

    .card-body::-webkit-scrollbar-thumb {
    background-color: #dc3545; 
    border-radius: 4px; 
    }

    .card-body::-webkit-scrollbar-thumb:hover {
    background-color: #c82333; 
    }

    .card-body::-webkit-scrollbar-track {
    background-color: #f8f9fa; 
    }

    .card-header {
        display:flex;
        justify-content:center;
        align-items:center;
    }

     @media (max-width:900px){
    .rowcon {
            width: 100% !important;
            position: relative;
            display: flex;
            flex-direction: column !important;
            justify-content: center;
            align-items: center;
            height: auto !important;
    } 
    
    .card-body {
        overflow: hidden; 
        max-height: 100vh !important;
        padding: 0 !important;
        padding-left: 15px !important;
        padding-bottom: 20px !important;
        padding-top: 20px !important;
    }

    .container {
        overflow-Y: auto !important; 
    }
    .card {
        border-radius: 0 !important;
    }
    .card-header:first-child,.card-body {
        border-radius: 0 !important;
    }
} 

</style>

<div class="container">
<section class="section">
<div class="row justify-content-center align-items-center"> <!-- align-items-center to center vertically -->


        
    <div class="rowcon">
                <div class="left">
                    <h2><b>St. Cecilia's College Portal</b></h2>
                    <p>Your Gateway to Success</p>
                    <img src="{{ asset('img/schoolbg2.png') }}" class="bldg">
                </div>

             <!-- Right Side Column -->
                <div class="right">
                    <p class="footertxt">©2024 •<span> Cecilian College Portal </span>• Alrights Reserved</p>
                </div>
             <!-- End of Right Side Column -->

            
              
             <div class="col-lg-5 col-md-7 d-flex flex-column justify-content-center"> 
                <div class="card mb-3 mx-0 mt-2">
                    <div class="card-header bg-danger text-white text-center">
                    <a href="../" class="logo d-flex align-items-center w-auto" tabindex="-1">
                        <img src="{{ asset('img/SCC.png') }}" alt="St. Cecilia's College - Cebu, Inc. Logo" style="max-height: 50px; margin-right: 10px;">
                        <span class="fw-bold text-uppercase">User Registration</span>
                    </a>
                        
                    </div>
                    @livewire('auth.registration-form')
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
