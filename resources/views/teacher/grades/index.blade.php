<!-- resources/views/teacher/grades/index.blade.php -->
@extends('layouts.app')

@section('title', 'Grade')

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
                        <h4 class="mb-0">Subject Description</h4>
                      </div>
                      <span class="fw-light mt-0">Subject Code</b></span>
                      
                      <ul class="list-inline mt-2 mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                        <li class="list-inline-item d-flex gap-1">
                          <i class="bx bxs-school mt-1"></i> 
                          <span class="fw-light">Room Name</span>
                        </li>
                        <li class="list-inline-item d-flex gap-1">
                          <i class="bx bx-group mt-1"></i>
                          <span class="followers-count fw-light">00</span>
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
                      <a class="nav-link" type="button" tabindex="-1">
                        <i class="bx bx-user-check me-1"></i> Details</a>
                  </li>
                  <li class="nav-item" role="presentation">
                      <a class="nav-link"type="button" tabindex="-1">
                        <i class="bx bx-group me-1"></i> People</a>
                  </li>
              </ul>
    <div class="pagetitle">
        <h1>Grade Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('newsfeed') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Grades</li>
            </ol>
        </nav>
    </div>
  
            <!-- Main Content -->
        
            <form id="filter-form">
                <div class="row">
                    <!-- <div class="col-md-3">
                        <label for="school_year">School Year</label>
                        <select id="school_year" name="school_year" class="form-control">
                          
                            @foreach($schoolYears as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="semester">Semester</label>
                        <select id="semester" name="semester" class="form-control">
                         
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="department">Department</label>
                        <select id="department" name="department" class="form-control">
                           
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="section">Section</label>
                        <select id="section" name="section" class="form-control" disabled>
                          
                        </select>
                    </div> -->

                </div>
            </form>
      

  
</div>
</div>
</div>
<div class="row" id="subjects-container">
    @foreach($subjectsEnrolled as $subjectEnrolled)
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $subjectEnrolled->subject->subject_code }} - {{ $subjectEnrolled->section->name }}</h5>
                    <p class="card-text">{{ $subjectEnrolled->subject->description }}</p>
                    <a href="{{ route('teacher.subject.grades', ['subjectEnrolledId' => $subjectEnrolled->id]) }}" class="btn btn-primary">View Grades</a>
                    </div>
            </div>
        </div>
    @endforeach
</div>
</section>

@endsection
</main>

