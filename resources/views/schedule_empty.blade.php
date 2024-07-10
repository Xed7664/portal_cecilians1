@extends('layouts.app')

@section('title', 'Calendar')

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      
    </div>

    <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
        
        <center>
            <h2>No record found</h2>
        </center>
        

                <p class="fs-6 text-center"><b>Oops!</b> We couldn't find your schedule for the selected school year and semester. Try changing the year or semester, or contact support for help.</p>
        <img src="{{ asset('img/svg/no-record.svg') }}" class="img-fluid py-5" alt="Page Not Found">
      </section>
  </main><!-- End #main -->

@endsection
