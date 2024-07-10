@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="container">
    <section class="section">
    <div class="container m-4 ms-0">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6 d-flex flex-column">
                <div class="d-flex justify-content-center mt-4">
                    <a href="../" class="logo d-flex align-items-center w-auto" tabindex="-1">
                    <img src="{{ asset('img/SCC.png') }}" alt="St. Cecilia's College - Cebu, Inc. Logo" style="max-height: 50px; margin-right: 10px;">
                    <span class="d-none d-lg-block text-danger fs-2">Cecilian Portal</span>
                    </a>
                </div>
                <div class="card mb-3 mx-0 mt-2">
                    <div class="card-header bg-danger text-white text-center">
                        <span class="fw-bold text-uppercase">User Login </span>
                    </div>
                    @livewire('auth.login-form')
                </div>
            </div>
            <div class="d-none d-lg-flex col-lg-7 p-0">
                <div class="d-flex justify-content-center align-items-center">
                    <img src="{{ asset('img/auth/test2.png') }}" alt="auth-login-cover" class="img-fluid my-5">
                </div>
            </div>
        </div>
    </div>
    

    </section>
</div>
@endsection
