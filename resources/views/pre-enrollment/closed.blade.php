<!-- resources/views/pre-enrollment/closed.blade.php -->
@extends('layouts.app')

@section('title', 'Pre-Enrollment Closed')

@section('content')
<main id="main" class="main">
    <!-- Page title -->
    <div class="pagetitle">
        <h1>Pre-enrollment Application</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('newsfeed') }}">Home</a></li>
                <li class="breadcrumb-item active">Pre-Enrollment Form</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
<div class="text-center">
    <img src="{{ asset('img/svg/no-record.svg')}}" alt="Closed Icon" style="width:150px;">
    <h1>Pre-Enrollment Closed</h1>
    <p>The pre-enrollment period is currently closed. Please check back during the designated enrollment period, or contact the administration for more information.</p>
</div>
</main>
@endsection
