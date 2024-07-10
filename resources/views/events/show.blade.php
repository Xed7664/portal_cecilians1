@extends('layouts.app')

@section('title', 'Event')

@section('content')
<main id="main" class="main">

    <div class="pagetitle">
      <h1>Event Title</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('newsfeed') }}">Home</a></li>
          <li class="breadcrumb-item">Events</li>
          <li class="breadcrumb-item active">Event Title</li>
        </ol>
      </nav>
    </div>

    <section class="section mt-4">
      
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Event Details</h5>
                    <p class="mb-2">Embark on a night of innovation, collaboration, and networking at the ITech Society Acquaintance Party - a celebration of tech enthusiasts, aspiring innovators, and future leaders in the realm of Information Technology.</p>
                    <h5 class="card-title fs-6">Date and Time</h5>
                    <p class="mb-2">Join us on <b>November 14, 2023</b> starting at <b>1:00 PM to 7:00 PM</b>. Prepare for an evening that merges cutting-edge technology with a spirit of camaraderie and fun.</p>
                    <h5 class="card-title fs-6">Dress Code</h5>
                    <p class="mb-2">Dress the part in attire that exudes Mafia Elegance. Think sleek suits, glamorous gowns, and accessories that would make even the most notorious tech moguls envious. Embrace the dark and sophisticated side of the tech world.</p>
                </div>
            </div>

            <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title m-0">Programme Flow</h5>
                    </div>
                    <div class="card-body">
                        <ul class="timeline pb-0 mb-0">
                        <li class="timeline-item timeline-item-transparent border-primary">
                            <span class="timeline-point timeline-point-primary"></span>
                            <div class="timeline-event">
                                <div class="timeline-header">
                                    <h6 class="mb-0 fw-bold">Registration</h6>
                                    <span class="text-muted">1:30 PM</span>
                                </div>
                                <p class="mt-2 small">Allow these officers scan your QR Code:</p>
                                <div class="d-flex justify-content-start align-items-center">
                                    <div class="avatar me-2 mt-2">
                                        <img src="{{ asset('img/profile/1699894716_655255bc2b441.jpg') }}" alt="Avatar" class="rounded-circle profile-sm">
                                    </div>
                                    <div class="d-flex flex-column mt-2">
                                        <a href="" class="text-body text-nowrap">
                                        <h6 class="mb-0 fw-medium">James Javeluna</h6>
                                        </a>
                                        <small class="text-muted">BSIT</small>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="timeline-item timeline-item-transparent border-primary">
                            <span class="timeline-point timeline-point-primary"></span>
                            <div class="timeline-event">
                                <div class="timeline-header">
                                    <h6 class="mb-0 fw-bold">Invocation</h6>
                                    <span class="text-muted">2:30 PM</span>
                                </div>
                            </div>
                        </li>
                        <li class="timeline-item timeline-item-transparent border-primary">
                            <span class="timeline-point timeline-point-primary"></span>
                            <div class="timeline-event">
                                <div class="timeline-header">
                                    <h6 class="mb-0 fw-bold">Opening Remarks</h6>
                                    <span class="text-muted">3:15 PM</span>
                                </div>
                                <div class="d-flex justify-content-start align-items-center mb-4">
                                    <div class="avatar me-2 mt-2">
                                        <img src="{{ asset('img/profile/default-profile.png') }}" alt="Avatar" class="rounded-circle profile-sm">
                                    </div>
                                    <div class="d-flex flex-column mt-2">
                                        <a href="" class="text-body text-nowrap">
                                        <h6 class="mb-0 fw-medium">Liezl T. Ocaba</h6>
                                        </a>
                                        <small class="text-muted">Officer, Student Affairs</small>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="timeline-item timeline-item-transparent border-primary">
                            <span class="timeline-point timeline-point-primary"></span>
                            <div class="timeline-event">
                            <div class="timeline-header">
                                <h6 class="mb-0 fw-bold">Entertainment & Performance</h6>
                                <span class="text-muted">3:45 PM</span>
                            </div>
                            <p class="mt-2 small">Got Talent Participants:</p>
                            
                                
                            </div>
                        </li>
                        <li class="timeline-item timeline-item-transparent border-left-dashed">
                            <span class="timeline-point timeline-point-primary"></span>
                            <div class="timeline-event">
                            <div class="timeline-header">
                                <h6 class="mb-0 fw-bold">Awarding Proper (Ongoing)</h6>
                                <span class="text-muted">5:45 PM</span>
                            </div>
                            </div>
                        </li>
                        <li class="timeline-item timeline-item-transparent border-transparent pb-0">
                            <span class="timeline-point timeline-point-secondary"></span>
                            <div class="timeline-event pb-0">
                            <div class="timeline-header">
                                <h6 class="mb-0 fw-bold">Closing Remarks</h6>
                                <span class="text-muted">6:20 PM</span>
                            </div>
                            </div>
                        </li>
                        </ul>
                    </div>
                    </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Organizer Details</h5>
                    <div class="d-flex justify-content-start align-items-center mb-4">
                        <div class="avatar me-2">
                            <img src="{{ asset('img/profile/1699894716_655255bc2b441.jpg') }}" alt="Avatar" class="rounded-circle profile-sm">
                        </div>
                        <div class="d-flex flex-column">
                            <a href="" class="text-body text-nowrap">
                            <h6 class="mb-0 fw-medium">James Javeluna</h6>
                            </a>
                            <small class="text-muted">BSIT</small></div>
                        </div>
                    </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title m-0 fs-6">Location</h6>
                    <p class="mb-0">South Town Centre</p>
                    <p class="mb-0">Bulacao, Talisay City, Cebu</p>
            </div>
        </div>
    </div>

    </section>

  </main><!-- End #main -->

@endsection
