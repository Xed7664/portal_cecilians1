@extends('subjects.layout')

@section('title', $subject->subject_code)

@section('subject_content')
<div class="col-12">
  <div class="row">
    <div class="col-xl-5 col-lg-5 col-md-5">
      
        <!-- About User -->
        <div class="card mb-4">
          <div class="card-body">

            <!-- Bio -->
              <div class="card-title text-uppercase pb-0">
                <span>Description</span>
              </div>

              <div class="text-center">
                  <p class="small fst-italic pb-2 prewrap">No description added by the instructor.</p>
              </div>

              <!--/ Bio -->

              <div class="card-title text-uppercase pt-0 pb-0">
                <span>Instructor</span>
              </div>

            <ul class="list-unstyled mb-4 mt-3">
              
              <li class="d-flex align-items-center mb-3">
                <div class="d-flex justify-content-start align-items-center mb-4">
                    <div class="avatar me-3">
                        <img src="{{ asset('img/profile/default-profile.png') }}" alt="Avatar" class="rounded-circle profile-sm">
                    </div>
                    <div class="d-flex flex-column">
                        <a href="" class="text-body text-nowrap">
                        <h6 class="mb-0 fw-medium">{{ ucwords(strtolower($subject->instructor_name)) }}</h6>
                        </a>
                        <small class="text-muted">No portal account</small>
                    </div>
                </div>
              </li>

              <div class="card-title text-uppercase pt-0 pb-0">
                <span>Details</span>
              </div>

              <li class="d-flex align-items-center mb-3">
                <i class="bx bx-calendar"></i>
                <span class="fw-medium mx-2">Class Day:</span> 
                <span class="fw-light text-break">{{ $subject->corrected_day }}</span>
              </li>

              <li class="d-flex align-items-center mb-3">
                <i class="bx bx-time"></i>
                <span class="fw-medium mx-2">Class Hours:</span> 
                <span class="fw-light text-break">{{ $subject->corrected_time }}</span>
                
              </li>

            </ul>

            
          
          </div>
        </div>
        <!--/ About User -->
      
    </div>

    <div class="col-xl-7 col-lg-7 col-md-7">
      <!-- Activity Timeline -->

    </div>



      <!--/ Activity Timeline -->
      
      
    </div>
  </div>
</div>
@endsection
