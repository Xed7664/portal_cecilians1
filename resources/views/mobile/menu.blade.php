@extends('layouts.app')

@section('title', 'Calendar')

@section('content')
<main id="main" class="main">
@php
use App\Models\User;
    $userTheme = session('theme');
    $userPanel = session('panel');
    $currentRoute = \Request::route()->getName();
    $userPermissions = Auth::user()->getUserPermissions();
    $rolePermissions = Auth::user()->getRolePermissions();
@endphp

    <div class="pagetitle">
        <h1>Menu</h1>
    </div>
    @php
        $currentPosition = Auth::user()->getCurrentPosition();
    @endphp

    <section class="section mt-4">
      <div class="card mb-2">
        <div class="card-body p-0">
            <a class="d-flex align-items-center m-2" href="{{ route('profile.show', ['username' => auth()->user()->username]) }}">
                <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar avatar-online">
                        <img src="{{ asset('img/profile/' . Auth::user()->avatar) }}" alt="Profile" class="rounded-circle profile-sm">
                        </div>
                    </div>
                    <div class="flex-grow-1 text-start">
                    @if (Auth::user()->type == 'student' && Auth::user()->student)
                      <span class="fw-medium d-block text-portal">{{ Auth::user()->student->FullName }}</span>
                      @if ($currentPosition != null)
                          <small class="text-muted">{{ $currentPosition->name }}</small>
                      @else
                          <small class="text-muted">{{ Auth::user()->student->Course }}</small>
                      @endif

                  @elseif (Auth::user()->type == 'program_head' && Auth::user()->employee)
                      <span class="fw-medium d-block text-portal">{{ Auth::user()->employee->FullName }}</span>
                      @if ($currentPosition != null)
                          <small class="text-muted">{{ $currentPosition->name }}</small>
                      @else
                          <small class="text-muted">Program Head</small>
                      @endif

                  @elseif (Auth::user()->type == 'teacher' && Auth::user()->employee)
                      <span class="fw-medium d-block text-portal">{{ Auth::user()->employee->FullName }}</span>
                      <small class="text-muted">Teacher</small> <!-- Adjust this based on your requirements -->

                  @elseif (Auth::user()->type == 'admin' && Auth::user()->employee)
                      <span class="fw-medium d-block text-portal">{{ Auth::user()->employee->FullName }}</span>
                      <small class="text-muted">Admin</small> <!-- Adjust this based on your requirements -->
                  @endif

                    </div>
                </div>
            </a>
        </div>
      </div>

      <div class="col-12 mt-2">
        <div class="row row-cols-2 g-2 mt-0">
            <div class="col-xl-3 col-lg-3 col-md-3 mt-0">
                <div class="card mb-2">
                    <div class="card-body text-center m-0 p-0">
                        <a href="{{ route('profile.show', ['username' => auth()->user()->username]) }}" class="m-2">
                            <div class="mx-auto mb-1">
                                <img src="{{ asset('img/icons/trophy-32.png') }}" alt="Badge Image" class="w-px-100">
                            </div>
                            <span class="text-portal">Trophies</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-3 mt-0">
                <div class="card mb-2">
                    <div class="card-body text-center m-0 p-0">
                        <a href="{{ route('account.show', ['page' => 'qrcode']) }}" class="m-2">
                            <div class="mx-auto mb-1">
                                <img src="{{ asset('img/icons/qr-32.png') }}" alt="Badge Image" class="w-px-100">
                            </div>
                            <span class="text-portal">QR Code</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-3 mt-0">
                <div class="card mb-2">
                    <div class="card-body text-center m-0 p-0">
                        <a href="#" class="m-2">
                            <div class="mx-auto mb-1">
                                <img src="{{ asset('img/icons/question-32.png') }}" alt="Badge Image" class="w-px-100">
                            </div>
                            <span class="text-portal">Help</span>
                        </a>
                    </div>
                </div>
            </div>


            <!-- @if(Auth::user()->hasPermission('access_admin'))
                <div class="col-xl-3 col-lg-3 col-md-3 mt-0 switch-panel">
                    <div class="card mb-2">
                        <div class="card-body text-center m-0 p-0">
                            <div class="m-2">
                                <div class="mx-auto mb-1">
                                    <img src="{{ asset('img/icons/admin-32.png') }}" alt="Badge Image" class="w-px-100">
                                </div>
                                <span class="text-panel">{!! ($userPanel === 'user') ? 'Admin Panel' : 'User Panel' !!}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif   -->
            
        </div>
      </div>

    </div>

    <div class="card mb-2 mt-2">
        <div class="card-body p-3">
            <a class="dropdown-item d-flex align-items-center" href="{{ route('account.show', ['page' => 'account']) }}">
                <i class="bi bi-gear me-2"></i>
                <span>Account Settings</span>
            </a>
        </div>
    </div>

    <div class="card mb-2 mt-2">
        <div class="card-body p-3 switch-theme">
            <a class="dropdown-item d-flex align-items-center" href="javascript:void();">
                <i class="bi {{ ($userTheme === 'light') ? 'bi-cloud-moon' : 'bi-cloud-sun-fill'; }} me-2"></i>
                <span>{{ ($userTheme === 'light') ? 'Dark Mode' : 'Light Mode'; }}</span>
            </a>
        </div>
    </div>

    <div class="card mb-2 mt-2">
        <div class="card-body p-3">
            <a class="dropdown-item d-flex align-items-center" href="{{route('logout')}}">
                <i class="bi bi-box-arrow-right me-2"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    </section>
    

  </main>
@endsection
