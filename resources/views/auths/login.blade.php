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
                        <img src="{{ asset('assets/images/schoolbggg.png') }}" class="bldg"> 
                        
                        
            
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

                        <img src="{{ asset('img/svg/coding.png') }}" class="signup">

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

        if (screenWidth <= 900) {
            if (registerCard.style.transform === 'translateX(-150%)') {
                registerCard.style.transform = 'translateX(-50%)';
                registerCard.style.opacity = '1';
                registerCard.style.transition = '0s';
            } else if (loginCard.style.transform === 'translateX(0)') {
                loginCard.style.transform = 'translateX(0)';
                loginCard.style.opacity = '1';
                loginCard.style.transition = '0';
            }
        } else {
            registerCard.style.transform = 'translateX(-150%)';
            registerCard.style.transition = '0.5s ease';
            loginCard.style.transform = 'translateX(0)';
            loginCard.style.opacity = '1';
        }
    }

    // Function to show the register card
    window.showRegCon = function () {
        const screenWidth = window.innerWidth;

        if (circle) {
            circle.style.transform = 'translate(50%, -50%)';
            circle.style.background = 'linear-gradient(180deg, white, rgb(189, 26, 42)';
            
            if (screenWidth <= 900) {
                registerCard.style.transform = 'translateX(-50%)';
            } else {
                registerCard.style.transform = 'translateX(-150%)';
            }

            loginCard.style.transform = 'translateX(-200%)';
            registerCard.style.transition = '0.5s ease';
            registerCard.style.opacity = '1';
            loginCard.style.zIndex = '-10';
            registerCard.style.zIndex = '1';
            h2.style.transform = 'translateX(-200%)';
            bldg.style.transform = 'translateX(-200%)';
            signup.style.transform = 'translateX(-54%)';
            loginFormh2.style.transform = 'translateX(0)';
        }
        history.pushState(null, '', '/auth/login?/registration');
    };

    // Function to show the login card
    window.showLoginCon = function () {
        const screenWidth = window.innerWidth;

        if (circle) {
            circle.style.transform = 'translate(-50%, -50%)';
            circle.style.background = 'linear-gradient(135deg, white, rgb(189, 26, 42)';
            
            loginCard.style.transform = 'translateX(0)';
            loginCard.style.opacity = '1';

            if (screenWidth <= 900) {
                registerCard.style.transform = 'translateX(50%)';
            } else {
                registerCard.style.transform = 'translateX(50%)';
            }

            registerCard.style.transition = '0.2s ease';
            loginCard.style.zIndex = '1';
            registerCard.style.zIndex = '-10';
            registerCard.style.opacity = '0';
            h2.style.transform = 'translateX(0)';
            bldg.style.transform = 'translateX(0)';
            signup.style.transform = 'translateX(200%)';
            loginFormh2.style.transform = 'translateX(200%)';
        }
        history.pushState(null, '', '/auth/login?');
    };

    // Attach resize event listener
    window.addEventListener('resize', adjustCardsForScreenWidth);

    // Initial adjustment based on current screen width
    adjustCardsForScreenWidth();
});

</script>