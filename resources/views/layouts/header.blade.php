
<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
      <a href="{{ route('newsfeed') }}" class="logo d-flex align-items-center">
        <img src="{{ asset('img/SCC.png') }}" alt="">
        <span class="d-none d-lg-block">Cecilian Portal</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="GET" action="{{ url('/search/') }}">
            @csrf
            <input type="text" name="query" placeholder="Search" title="Enter search keyword" value="{{ request('query') }}">
            <button type="submit" title="Search"><i class="bi bi-search"></i></button>
        </form>
    </div><!-- End Search Bar -->


    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item d-none d-sm-block">
          <a class="nav-link nav-icon" href="./../../scan">
            <i class="ri ri-qr-scan-line "></i>
          </a>
        </li>

        <li class="nav-item dropdown">
          
          
          <a class="nav-link nav-icon " href="#" data-bs-toggle="dropdown" data-bs-auto-close="inside" aria-expanded="true" >
            <i class="ri ri-notification-2-line"></i>
            @php
                $newNotificationCount = Auth::user()->unreadNotificationCount();
                $displayCount = $newNotificationCount > 99 ? '99+' : $newNotificationCount;
            @endphp

            @if($newNotificationCount > 0)
                <span class="badge bg-primary badge-number notification-count">{{ $displayCount }}</span>
            @endif

          </a>
          <!-- End Notification Icon -->


        <!-- Start Notification Dropdown Items -->
        @livewire('notification.notification-component')
        <!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->

        <li class="nav-item dropdown pe-3 d-none d-sm-block">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="{{ asset('img/profile/' . Auth::user()->avatar) }}" alt="Profile" class="rounded-circle profile-sm">
            <span class="d-none d-md-block dropdown-toggle ps-2"></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          @php
                $currentPosition = Auth::user()->getCurrentPosition();
            @endphp

            <li class="px-2 py-0 dropdown-header">
              <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.show', ['username' => auth()->user()->username]) }}">
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
            </li>

            <li class="pt-1 pb-1">
              <hr class="dropdown-divider">
            </li>

            <li class="px-2 py-25"> 
                <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.show', ['username' => auth()->user()->username]) }}">
                    <i class="bi bi-person"></i>
                    <span>My Profile</span>
                </a>
            </li>

            <li class="px-2 py-25">  
              <a class="dropdown-item d-flex align-items-center" href="{{ route('account.show', ['page' => 'qrcode']) }}">
                <i class="ri ri-qr-code-line"></i>
                <span>My QR code</span>
              </a>
            </li>

            <li class="px-2 py-25"> 
              <a class="dropdown-item d-flex align-items-center" href="{{ route('account.show', ['page' => 'account']) }}">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>

            <li class="py-1">
              <hr class="dropdown-divider">
            </li>

            <li class="px-2 py-25"> 
              <a class="dropdown-item d-flex align-items-center" href="#">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li>

            <li class="px-2 py-25 switch-theme"> 
              <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                <i class="bi {{ ($userTheme === 'light') ? 'bi-cloud-moon' : 'bi-cloud-sun-fill'; }}"></i>
                <span><span>{{ ($userTheme === 'light') ? 'Dark Mode' : 'Light Mode'; }}</span></span>
              </a>
            </li>

            @if(Auth::user()->hasPermission('access_admin'))
            <li class="px-2 py-25 switch-panel">
              <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                <i class="ri {{ ($userPanel === 'employee') ? 'ri-user-settings-line' : 'ri-user-settings-fill' }}"></i>
                <span>{!! ($userPanel === 'employee') ? 'Switch to <b>Admin Panel</b>' : 'Switch to <b>User Panel</b>' !!}</span>
              </a>
            </li>
            @endif  

            <li class="py-1">
              <hr class="dropdown-divider">
            </li>
            
            <li class="px-2 pt-25 pb-2">
              <a id="logout" class="dropdown-item d-flex align-items-center" href="{{route('logout')}}">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header>  
