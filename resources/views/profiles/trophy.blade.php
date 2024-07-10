@extends('profiles.layout')

@section('title', $user->getFullName())

@section('profile_content')

<div class="col-12">
  <div class="row g-4 mt-0">
    <div class="col-xl-3 col-lg-3 col-md-3 mt-0">
        <div class="card">
            <div class="card-body text-center">
                <div class="mx-auto mt-3">
                    <img src="{{ asset('img/badges/icons8-early-users.png') }}" alt="Badge Image" class="w-px-100">
                </div>
                <h4 class="mb-0 pb-1 pt-2 card-title">Early Users</h4>
                <span class="pb-1 fw-lighter small">September 27, 2023</span>
                <div class="d-flex align-items-center justify-content-center my-3 gap-2">
                    <span class="badge bg-light-subtle text-light">Common</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-3 col-md-3 mt-0">
        <div class="card">
            <div class="card-body text-center">
                <div class="mx-auto mt-3">
                    <img src="{{ asset('img/badges/icons8-verified.png') }}" alt="Badge Image" class="w-px-100">
                </div>
                <h4 class="mb-0 pb-1 pt-2 card-title">Email Verified</h4>
                <span class="pb-1 fw-lighter small">September 27, 2023</span>
                <div class="d-flex align-items-center justify-content-center my-3 gap-2">
                    <span class="badge bg-light-subtle text-light">Common</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-3 col-md-3 mt-0">
        <div class="card">
            <div class="card-body text-center">
                <div class="mx-auto mt-3">
                    <img src="{{ asset('img/badges/icons8-officer.png') }}" alt="Badge Image" class="w-px-100">
                </div>
                <h4 class="mb-0 pb-1 pt-2 card-title">14th SCO</h4>
                <span class="pb-1 fw-lighter small">September 27, 2023</span>
                <div class="d-flex align-items-center justify-content-center my-3 gap-2">
                    <span class="badge bg-info-subtle text-info">Epic</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-3 col-md-3 mt-0">
        <div class="card">
            <div class="card-body text-center">
                <div class="mx-auto mt-3">
                    <img src="{{ asset('img/badges/icons8-founder.png') }}" alt="Badge Image" class="w-px-100">
                </div>
                <h4 class="mb-0 pb-1 pt-2 card-title">Founder</h4>
                <span class="pb-1 fw-lighter small">September 27, 2023</span>
                <div class="d-flex align-items-center justify-content-center my-3 gap-2">
                    <span class="badge bg-warning-subtle text-warning">Legendary</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-3 col-md-3 mt-0">
        <div class="card bg-body-tertiary">
            <div class="card-body text-center">
                <div class="mx-auto mt-3">
                    <img src="{{ asset('img/badges/icons8-singer.png') }}" alt="Badge Image" class="w-px-100">
                </div>
                <h4 class="mb-0 pb-1 pt-2 card-title">IT Singing Contest</h4>
                <span class="pb-1 fw-lighter small">Not Unlocked</span>
                <div class="d-flex align-items-center justify-content-center my-3 gap-2">
                    <span class="badge bg-info-subtle text-info">Epic</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-3 col-md-3 mt-0">
        <div class="card bg-body-tertiary">
            <div class="card-body text-center">
                <div class="mx-auto mt-3">
                    <img src="{{ asset('img/badges/icons8-election.png') }}" alt="Badge Image" class="w-px-100">
                </div>
                <h4 class="mb-0 pb-1 pt-2 card-title">2K23 Election</h4>
                <span class="pb-1 fw-lighter small">Not Unlocked</span>
                <div class="d-flex align-items-center justify-content-center my-3 gap-2">
                    <span class="badge bg-info-subtle text-info">Epic</span>
                </div>
            </div>
        </div>
    </div>


  </div>
</div>
@endsection
