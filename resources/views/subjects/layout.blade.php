@extends('layouts.app')

@section('content')

<main id="main" class="main">

    <section class="section profile">

        <!-- Subject Header Start -->
        <div class="row">
          <div class="col-12">
            <div class="card mb-4">
              <div class="user-profile-header-banner">
                <img src="{{ asset('img/profile/cover/scc.jpg') }}" alt="Banner image" class="rounded-top">
              </div>
              <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-3">
                <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                <img src="{{ asset('img/course/default.png') }}" alt="user image" class="d-block ms-0 ms-sm-4 rounded user-profile-img border-dark" style="width: 120px; height: 120px; object-fit: cover;">
                </div>
                <div class="flex-grow-1 mt-3 mt-sm-5">
                  <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                    <div class="user-profile-info">
                      <div class="d-flex justify-content-sm-start justify-content-center">
                        <h4 class="mb-0">{{ $subject->description }}</h4>
                      </div>
                      <span class="fw-light mt-0">{{ $subject->subject_code }}</b></span>
                      
                      <ul class="list-inline mt-2 mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                        <li class="list-inline-item d-flex gap-1">
                          <i class="bx bxs-school mt-1"></i> 
                          <span class="fw-light">{{ $subject->room_name }}</span>
                        </li>
                        <li class="list-inline-item d-flex gap-1">
                          <i class="bx bx-group mt-1"></i>
                          <span class="followers-count fw-light">{!! $enrolleesCount > 0 ? '<b>' . $enrolleesCount . '</b> Enrollees' : 'No Enrollees' !!}</span>
                        </li>
                      </ul>
                    </div>
                    <div class="">
                     

                      <button type="button" class="btn d-none d-md-block d-xl-none d-xxl-none" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="bi bi-three-dots-vertical"></i>
                          <div class="dropdown-menu dropdown-menu-end" style="overflow: hidden;">
                              <a class="dropdown-item" href="javascript:void(0);"><i class="bi bi-exclamation-circle me-1"></i> Report Course</a>
                              <a class="dropdown-item" href="javascript:void(0);"><i class="ri ri-file-copy-line me-1"></i> Copy link</a>
                          </div>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <ul class="nav nav-tabs nav-tabs-bordered border-top pt-3 border-light-subtle border-opacity-10 border-1" role="tablist">
                  <li class="nav-item active" role="presentation">
                      <a class="nav-link {{ $page == 'detail' ? 'active' : '' }}" href="{{ route('subject.details', ['subject_id' => $subject->id]) }}" type="button" tabindex="-1">
                        <i class="bx bx-user-check me-1"></i> Details</a>
                  </li>
                  <li class="nav-item" role="presentation">
                      <a class="nav-link {{ $page == 'people' ? 'active' : '' }}" href="{{ route('subject.people', ['subject_id' => $subject->id]) }}" type="button" tabindex="-1">
                        <i class="bx bx-group me-1"></i> People</a>
                  </li>
              </ul>

            </div>
        </div>

        <!-- Subject Header End -->

        <!-- ======= Subject Content ======= -->
          @yield('subject_content')
        <!-- ======/ Subject Content ======= -->

      </div>
    </section>
</main>

@endsection
