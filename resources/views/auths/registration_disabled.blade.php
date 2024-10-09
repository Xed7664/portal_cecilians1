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
                        <p><b>NOTICE</b>: Registration is currently disabled. Please contact the administrator for assistance.</p>

                        <div class="col-12 mb-2 mt-4">
                            <a class="btn btn-danger w-100" type="submit" href="{{route('login')}}"><i class="bi bi-arrow-left me-1"></i> Go back</a>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    </section>
</div>

@endsection
