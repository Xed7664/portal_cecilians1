<!-- resources/views/grades/index.blade.php -->

@extends('layouts.app')

@section('title', 'Grade')

@section('content')
<main id="main" class="main">
<div class="pagetitle">
      
    </div>
    <h1>Grades</h1>
    <!-- Add logic to display grades -->
    @if(!empty($grades))
        <ul>
            @foreach($grades as $grade)
                <li>{{ $grade }}</li>
            @endforeach
        </ul>
    @else
    <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
        
    <center>
        <p>No grades available.</p>
        </center>
        <p class="fs-6 text-center"><b>Oops!</b> We couldn't find your grade for the selected school year and semester. Try changing the year or semester, or contact support for help.</p>
        <img src="{{ asset('img/svg/no-record.svg') }}" class="img-fluid py-5" alt="Page Not Found">
    @endif
    </section>
</div>
</main>
@endsection

