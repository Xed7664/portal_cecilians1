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
    <h3>Pre-enrollment is currently closed.</h3>
    <p>Please check back later or contact your Program Head for more information.</p>
</div>
</main>
@endsection
