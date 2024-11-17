@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">


<div class="container1">
    <section class="section"> 
        
    <div class="headertxt" style="display:none;color:white">
        <h2><b>St. Cecilia's College Portal</b></h2>
        <p>Your Gateway to Success</p>
    </div>
          
           
            <!-- start of row -->
            <div class="row">
                
            <div class="circle"></div>

                <!-- start of left -->
                <div class="left">

                    <!-- start of left-con1 -->
                    <div class="left-con1">
    
                        <h2><b>New here?</b><br><span>Sign up to stay connected and get the latest updates! </span><br>
                        <button onclick="showRegCon()">SIGN UP</button>
                        </h2>
                        <img src="{{ asset('img/schoolbg2.png') }}" class="bldg"> 
                        
                        
            
                    </div>
                    <!-- end of left-con1 -->
                    
                    
                    <!-- start of left-con2 -->
                    <div class="left-con2 loginForm"> 
                        
                        <h2><b>Already have an account?</b><br><span>Sign in to access your dashboard.</span><br>
                        <button onclick="showLoginCon()">SIGN IN</button>
                        </h2>

                        <!-- start of loginCard -->
                        <div class="card mb-3 mx-0 mt-2 loginCard">
                            <div class="card-header bg-danger text-white text-center">
                                <a href="../" class="logo d-flex align-items-center w-auto" tabindex="-1">
                                    <img src="{{ asset('img/SCC.png') }}" alt="St. Cecilia's College - Cebu, Inc. Logo" style="max-height: 50px; margin-right: 10px;">
                                    <span class="fw-bold text-uppercase">User Login</span>
                                </a> 
                            </div>
                            @livewire('auth.login-form')
                        </div>
                        <!-- end of loginCard -->

                        <img src="{{ asset('img/svg/coding.svg') }}" class="signup">

                    </div>
                     <!-- end of left-con2 -->

                </div>
                <!-- end of left -->


             <!-- Right Side Column -->
                <div class="right">
                    <p class="footertxt">©2024 •<span> Cecilian College Portal </span>• Alrights Reserved</p>
                </div>
             <!-- End of Right Side Column -->

             

            <div class="footertxt" style="display:none">
            <p class="footertxt" style="color:rgb(150,150,150) !important">©2024 •<span style="color:rgb(190,190,190) !important"> Cecilian College Portal </span>• Alrights Reserved</p>
            </div>

            <!-- start of registerCard -->
            <div class="card mb-3 mx-0 mt-2 registerCard">
                                <div class="card-header bg-danger text-white text-center">
                                <a href="../" class="logo d-flex align-items-center w-auto" tabindex="-1">
                                    <img src="{{ asset('img/SCC.png') }}" alt="St. Cecilia's College - Cebu, Inc. Logo" style="max-height: 50px; margin-right: 10px;">
                                    <span class="fw-bold text-uppercase">User Registration</span>
                                </a>
                                </div>
                                @livewire('auth.registration-form')
                        </div>
                        <!-- end of registerCard -->

       </div>
        <!-- end of row -->

    </section>
</div>
@endsection
    

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const circle = document.querySelector('.circle');
    const loginCard = document.querySelector('.loginCard');
    const registerCard = document.querySelector('.registerCard');
    const h2 = document.querySelector('.left-con1 h2');
    const loginFormh2 = document.querySelector('.loginForm h2');
    const bldg = document.querySelector('.bldg');
    const signup = document.querySelector('.signup');

    // Function to adjust layout based on screen width and current state
    function adjustCardsForScreenWidth() {
        const screenWidth = window.innerWidth;

       
    }

    // Function to show the register card
    window.showRegCon = function () {
        if (circle) {
            circle.style.transform = 'translate(50%, -50%)';
            circle.style.background = 'linear-gradient(190deg, rgb(255, 216, 216), rgb(220, 53, 69)';
            loginCard.style.transform = 'translateX(-200%)';
            registerCard.style.transform = 'translateX(-150%)';
            registerCard.style.opacity = '1';
            loginCard.style.zIndex = '-10';
            registerCard.style.zIndex = '1';
            h2.style.transform = 'translateX(-150%)';
            bldg.style.transform = 'translateX(-150%)';
            signup.style.transform = 'translateX(-20%)';
            loginFormh2.style.transform = 'translateX(0)';
        }
        history.pushState(null, '', '/auth/login?/registration');
    };

    // Function to show the login card
    window.showLoginCon = function () {
        if (circle) {
            circle.style.transform = 'translate(-50%, -50%)';
            circle.style.background = 'linear-gradient(145deg, rgb(255, 216, 216), rgb(220, 53, 69)';
            loginCard.style.transform = 'translateX(0)';
            loginCard.style.opacity = '1';
            registerCard.style.transform = 'translateX(50%)';
            loginCard.style.zIndex = '1';
            registerCard.style.zIndex = '-10';
            registerCard.style.opacity = '0';
            h2.style.transform = 'translateX(0)';
            bldg.style.transform = 'translateX(0)';
            signup.style.transform = 'translateX(100%)';
            loginFormh2.style.transform = 'translateX(100%)';
        }
        history.pushState(null, '', '/auth/login?');
    };

    // Attach resize event listener
    window.addEventListener('resize', adjustCardsForScreenWidth);

    // Initial adjustment based on current screen width
    adjustCardsForScreenWidth();
});

</script>