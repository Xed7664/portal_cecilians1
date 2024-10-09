<aside id="sidebar" class="sidebar">
    <button type="button" class="btn btn-sm float-end toggle-sidebar-cstm-btn p-0">
      <i class="bx bx-x float-end text-white"></i>
    </button>
    
    <ul class="sidebar-nav" id="sidebar-nav">
    
    @if($userPanel === 'student')
      <li class="nav-heading">SCHOOL YEAR</li>

      <li class="nav-item school_year" data-syid="{{ Session::get('current_school_year_id'); }}" data-semid="{{ Session::get('current_semester_id') }}">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#" aria-expanded="false">
            <i class="bi bi-journal-text"></i>
            <span class="fw-medium">{{ App\Http\Controllers\SchoolYearController::getCurrentSchoolYearName() }} 
              ({{ App\Http\Controllers\SemesterController::getCurrentSemesterName() }})</span>
            <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav" style="">
            <!-- Semester Start -->
            @foreach (App\Http\Controllers\SemesterController::getSemesters() as $semester)
            <li class="semester-select" data-semid="{{ $semester->id }}">
                <a href="javascript:void(0);" class="{{ $semester->id == Session::get('current_semester_id') ? 'active' : '' }}">
                    <i class="bi bi-circle"></i>
                    <span class="fw-medium">{{ $semester->name }}</span>
                </a>
            </li>
            @endforeach
            <!-- Semester End -->

            <!-- School Year Start -->
            <li class="nav-heading">ARCHIVE</li>
            @foreach (App\Http\Controllers\SchoolYearController::getSchoolYears() as $schoolYear)
            <li class="year-select" data-syid="{{ $schoolYear->id }}">
                <a href="javascript:void(0);">
                    <i class="bi bi-circle"></i>
                    <span class="fw-medium">{{ $schoolYear->name }}</span>
                </a>
            </li>
            @endforeach
            <!-- School Year End -->
        </ul>
      </li>

      <li class="nav-heading">MENU</li>

     <!--  <li class="nav-item">
        <a class="nav-link collapsed" href="#">
          <i class="bx bx-grid-alt"></i>
          <span class="fw-medium">Dashboard</span>
        </a>
      </li> -->
      <!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link {{ Route::is('schedule.index') ? '' : 'collapsed' }}" href="{{ route('schedule.index') }}">
          <i class="bx bx-calendar"></i>
          <span class="fw-medium">Class Schedule</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ Route::is('calendar.index') ? '' : 'collapsed' }}" href="{{ route('calendar.index') }}">
          <i class="bx bx-calendar-event"></i>
          <span class="fw-medium">Calendar</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ Route::is('student.grades.index') ? '' : 'collapsed' }}" href="{{ route('student.grades.index') }}">
            <i class='bx bx-bar-chart'></i>
          <span class="fw-medium">Grade</span>
        </a>
      </li>
      <li class="nav-item">
    <a class="nav-link {{ Route::is('enrollment.create') ? '' : 'collapsed' }}" href="{{ route('enrollment.create') }}">
        <i class='bx bx-user-plus'></i>
        <span class="fw-medium">Enrollment</span>
    </a>
</li>

      <li class="nav-item">
        <a class="nav-link {{ Route::is('prospectus.index') ? '' : 'collapsed' }}" href="{{ route('prospectus.index') }}">
            <i class='bx bx-book-open'></i>
          <span class="fw-medium">Prospectus</span>
        </a>
      </li>

      <li class="nav-heading">Social Media</li>
      <li class="nav-item">
          <a class="nav-link collapsed" href="https://www.facebook.com/St.CeciliasCollege" target="_blank">
            <i class="bi bi-facebook"></i>
            <span class="fw-medium">Facebook  
            
                      </span>
          </a>
        </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="https://www.instagram.com/sccstudentcouncilcollegedept" target="_blank">
          <i class="bi bi-instagram"></i>
          <span class="fw-medium">Instagram</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#">
          <i class="bi bi-youtube"></i>
          <span class="fw-medium">Youtube</span>
        </a>
      </li>
      
      <li class="nav-heading">Others</li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="https://www.facebook.com/messages/t/502287686497369" target="_blank">
            <i class="bi bi-messenger"></i>
            <span class="fw-medium">Messenger  
            
                      </span>
          </a>
        </li>

      @endif

      @if($userPanel === 'admin')

      <li class="nav-heading">ADMIN PANEL</li>

      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs(['admin.users.registered', 'admin.users.student', 'admin.users.employee']) ? '' : 'collapsed' }}" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="ri ri-group-line"></i>
          <span>Users</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>

        <ul id="components-nav" class="nav-content collapse {{ request()->routeIs(['admin.users.registered', 'admin.users.student', 'admin.users.employee']) ? 'show' : '' }}" data-bs-target="#components-nav" data-bs-parent="#sidebar-nav">
            <li>
                <a href="{{ route('admin.users.registered') }}" class="{{ request()->routeIs('admin.users.registered') ? 'active' : '' }}">
                    <i class="bi bi-circle"></i><span>Registered</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.student') }}" class="{{ request()->routeIs('admin.users.student') ? 'active' : '' }}">
                    <i class="bi bi-circle"></i><span>Students</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.employee') }}" class="{{ request()->routeIs('admin.users.employee') ? 'active' : '' }}">
                    <i class="bi bi-circle"></i><span>Employees</span>
                </a>
            </li>
        </ul>

      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#">
          <i class="bi bi-book"></i>
          <span>Subjects</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs(['admin.users.registered', 'admin.users.student', 'admin.users.employee']) ? '' : 'collapsed' }}" data-bs-target="#analytics-nav" data-bs-toggle="collapse" href="#">
          <i class="ri ri-bar-chart-line"></i>
          <span>Analytics</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>

        <ul id="analytics-nav" class="nav-content collapse {{ request()->routeIs(['admin.analytics.login']) ? 'show' : '' }}" data-bs-target="#components-nav" data-bs-parent="#sidebar-nav">
            <li>
                <a href="{{ route('admin.analytics.login') }}" class="{{ request()->routeIs('admin.users.registered') ? 'active' : '' }}">
                    <i class="bi bi-circle"></i><span>Logins</span>
                </a>
            </li>
        </ul>

      </li>

      @endif

      @if($userPanel === 'program_head')
      <li class="nav-heading">PHEAD PANEL</li>
      
      <li class="nav-item school_year" data-syid="{{ Session::get('current_school_year_id'); }}" data-semid="{{ Session::get('current_semester_id') }}">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#" aria-expanded="false">
            <i class="bi bi-journal-text"></i>
            <span class="fw-medium">{{ App\Http\Controllers\SchoolYearController::getCurrentSchoolYearName() }} 
              ({{ App\Http\Controllers\SemesterController::getCurrentSemesterName() }})</span>
            <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav" style="">
            <!-- Semester Start -->
            @foreach (App\Http\Controllers\SemesterController::getSemesters() as $semester)
            <li class="semester-select" data-semid="{{ $semester->id }}">
                <a href="javascript:void(0);" class="{{ $semester->id == Session::get('current_semester_id') ? 'active' : '' }}">
                    <i class="bi bi-circle"></i>
                    <span class="fw-medium">{{ $semester->name }}</span>
                </a>
            </li>
            @endforeach
            <!-- Semester End -->

            <!-- School Year Start -->
            <li class="nav-heading">ARCHIVE</li>
            @foreach (App\Http\Controllers\SchoolYearController::getSchoolYears() as $schoolYear)
            <li class="year-select" data-syid="{{ $schoolYear->id }}">
                <a href="javascript:void(0);">
                    <i class="bi bi-circle"></i>
                    <span class="fw-medium">{{ $schoolYear->name }}</span>
                </a>
            </li>
            @endforeach
            <!-- School Year End -->
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ Route::is('program-head.grades.index') ? '' : 'collapsed' }}" href="{{ route('program-head.grades.index') }}">
            <i class='bx bx-bar-chart'></i>
          <span class="fw-medium">Grade</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ Route::is('prospectus.index') ? '' : 'collapsed' }}" href="{{ route('prospectus.index') }}">
            <i class='bx bx-book-open'></i>
          <span class="fw-medium">Prospectus</span>
        </a>
      </li>
      @endif


      
      @if($userPanel === 'teacher')
     
<li class="nav-heading">MENU</li>
      <li class="nav-item">
        <a class="nav-link {{ Route::is('teacher.grades.index') ? '' : 'collapsed' }}" href="{{ route('teacher.grades.index') }}">
            <i class='bx bx-bar-chart'></i>
          <span class="fw-medium">Grade</span>
        </a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link {{ Route::is('admin.users.student') ? '' : 'collapsed' }}" href="{{ route('admin.users.student') }}">
            <i class='bx bx-bar-chart'></i>
          <span class="fw-medium">Student</span>
        </a>
      </li> -->

      @endif
    </ul>


  </aside>  
  