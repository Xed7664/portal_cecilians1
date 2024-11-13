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

            <li class="nav-item dropdown">
                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-grid-3x3-gap-fill"></i> <!-- Icon for grid-style dropdown -->
                </a>
                <div class="dropdown-menu dropdown-menu-end p-3" style="width: 250px; max-width: 100%; border-radius: 10px;">
                    <div class="row g-2">
                        <!-- Quick Action Icons (like Google Apps) -->
                        <div class="col-4 text-center">
                            <a href="#" class="d-block">
                                <i class="bi bi-journal-text fs-3"></i>
                                <small>Grades</small>
                            </a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#" class="d-block">
                                <i class="bi bi-file-earmark-arrow-up fs-3"></i>
                                <small>Upload</small>
                            </a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#" class="d-block">
                                <i class="bi bi-calendar-event fs-3"></i>
                                <small>Schedule</small>
                            </a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#" class="d-block">
                                <i class="bi bi-chat-dots fs-3"></i>
                                <small>Messages</small>
                            </a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#" class="d-block">
                                <i class="bi bi-people fs-3"></i>
                                <small>Students</small>
                            </a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#" class="d-block">
                                <i class="bi bi-file-earmark-check fs-3"></i>
                                <small>Attendance</small>
                            </a>
                        </div>
                        <!-- Add more quick actions as needed -->
                    </div>
                </div>
            </li>

            <li class="nav-item d-none d-sm-block">
                <a class="nav-link nav-icon" href="./../../scan">
                    <i class="ri ri-qr-scan-line"></i>
                </a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown" data-bs-auto-close="inside">
                    <i class="ri ri-notification-2-line"></i>
                    @php
                        $newNotificationCount = Auth::user()->unreadNotificationCount();
                        $displayCount = $newNotificationCount > 99 ? '99+' : $newNotificationCount;
                    @endphp

                    @if($newNotificationCount > 0)
                        <span class="badge bg-primary badge-number notification-count">{{ $displayCount }}</span>
                    @endif
                </a>
                <!-- Start Notification Dropdown Items -->
                @livewire('notification.notification-component')
                <!-- End Notification Dropdown Items -->
            </li><!-- End Notification Nav -->

            <li class="nav-item dropdown pe-3 d-none d-sm-block">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="{{ asset('img/profile/' . Auth::user()->avatar) }}" alt="Profile" class="rounded-circle profile-sm">
                    <span class="d-none d-md-block dropdown-toggle ps-2"></span>
                </a><!-- End Profile Image Icon -->

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
                         <small class="text-muted">{{ Auth::user()->program_code }}</small>
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
                            <i class="bi {{ ($userTheme === 'light') ? 'bi-cloud-moon' : 'bi-cloud-sun-fill' }}"></i>
                            <span>{{ ($userTheme === 'light') ? 'Dark Mode' : 'Light Mode' }}</span>
                        </a>
                    </li>

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
