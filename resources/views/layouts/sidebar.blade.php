<aside id="sidebar" class="sidebar">
    <button type="button" class="btn btn-sm float-end toggle-sidebar-cstm-btn p-0">
      <i class="bx bx-x float-end text-white"></i>
    </button>
    
    <ul class="sidebar-nav" id="sidebar-nav">
    @if($userPanel === 'student')
    <!-- School Year and Semester Section -->
    <li class="nav-heading">SCHOOL YEAR</li>
    <li class="nav-item school_year" data-syid="{{ Session::get('current_school_year_id') }}" data-semid="{{ Session::get('current_semester_id') }}">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#" aria-expanded="false">
            <i class="bi bi-journal-text"></i>
            <span class="fw-medium">
                {{ App\Http\Controllers\SchoolYearController::getCurrentSchoolYearName() ?? 'Current School Year' }} 
                ({{ App\Http\Controllers\SemesterController::getCurrentSemesterName() ?? 'Current Semester' }})
            </span>
            <i class="bi bi-chevron-down ms-auto"></i>
        </a>

        <ul id="forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <!-- Semesters Based on Selected School Year -->
            @php
                $semesters = App\Http\Controllers\SemesterController::getSemesters();
            @endphp
            @foreach ($semesters as $semester)
                <li class="semester-select" data-semid="{{ $semester->id }}">
                    <a href="javascript:void(0);" class="semester-link {{ $semester->id == Session::get('current_semester_id') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i>
                        <span class="fw-medium">{{ $semester->name }}</span>
                    </a>
                </li>
            @endforeach

            <!-- School Years -->
            <li class="nav-heading">ARCHIVE</li>
            @foreach (App\Http\Controllers\SchoolYearController::getSchoolYears() as $schoolYear)
                <li class="year-select" data-syid="{{ $schoolYear->id }}">
                    <a href="javascript:void(0);" class="year-link {{ $schoolYear->id == Session::get('current_school_year_id') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i>
                        <span class="fw-medium">{{ $schoolYear->name }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </li>


                <script>
           $(document).on('click', '.year-link', function() {
    var schoolYearId = $(this).closest('.year-select').data('syid');
    var semesterId = $('.school_year').data('semid'); // Keep the current semester unless changed

    if (schoolYearId) {
        $.ajax({
            url: "/api/semesters/" + schoolYearId,  // Construct the URL manually
            type: "GET",
            success: function(response) {
                var semesterList = $('#forms-nav');
                semesterList.empty();
                response.forEach(function(semester) { // Handle response directly
                    semesterList.append(
                        `<li class="semester-select" data-semid="${semester.id}">
                            <a href="javascript:void(0);" class="semester-link ${semester.id == semesterId ? 'active' : ''}">
                                <i class="bi bi-circle"></i>
                                <span class="fw-medium">${semester.name}</span>
                            </a>
                        </li>`
                    );
                });
                window.location.href = "{{ route('newsfeed') }}?school_year_id=" + schoolYearId + "&semester_id=" + semesterId;
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
});

                </script>

      <li class="nav-heading">MENU</li>

     <!--  <li class="nav-item">
        <a class="nav-link collapsed" href="#">
          <i class="bx bx-grid-alt"></i>
          <span class="fw-medium">Dashboard</span>
        </a>
      </li> -->
      <!-- End Dashboard Nav -->
      @php
    // Get school year and semester from query parameters or fallback to session
    $currentSchoolYearId = request()->query('school_year_id', Session::get('school_year_id'));
    $currentSemesterId = request()->query('semester_id', Session::get('semester_id'));
    @endphp
    
      <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('newsfeed', ['school_year_id' => $currentSchoolYearId, 'semester_id' => $currentSemesterId]) }}">
              <i class="bx bx-grid-alt"></i>
              <span class="fw-medium">Newsfeed</span>
          </a>
      </li>

            <li class="nav-item">
          <a class="nav-link {{ Route::is('schedule.index') ? '' : 'collapsed' }}" href="{{ route('schedule.index', ['school_year_id' => $currentSchoolYearId, 'semester_id' => $currentSemesterId]) }}">
              <i class="bx bx-calendar"></i>
              <span class="fw-medium">Class Schedule</span>
          </a>
      </li>

      <li class="nav-item">
          <a class="nav-link {{ Route::is('calendar.index') ? '' : 'collapsed' }}" href="{{ route('calendar.index', ['school_year_id' => $currentSchoolYearId, 'semester_id' => $currentSemesterId]) }}">
              <i class="bx bx-calendar-event"></i>
              <span class="fw-medium">Calendar</span>
          </a>
      </li>

      <li class="nav-item">
          <a class="nav-link {{ Route::is('student.grades.index') ? '' : 'collapsed' }}" href="{{ route('student.grades.index', ['school_year_id' => $currentSchoolYearId, 'semester_id' => $currentSemesterId]) }}">
              <i class='bx bx-bar-chart'></i>
              <span class="fw-medium">Grades</span>
          </a>
      </li>

      <li class="nav-item">
    <a class="nav-link {{ Route::is('pre-enrollment.form') ? '' : 'collapsed' }}" href="{{ route('pre-enrollment.form') }}">
        <i class='bx bx-user-plus'></i>
        <span class="fw-medium">Pre-Enrollment</span>
    </a>
  </li>

    
      <li class="nav-item">
          <a class="nav-link {{ Route::is('prospectus.index') ? '' : 'collapsed' }}" href="{{ route('prospectus.index', ['school_year_id' => $currentSchoolYearId, 'semester_id' => $currentSemesterId]) }}">
              <i class='bx bx-book-open'></i>
              <span class="fw-medium">Prospectus</span>
          </a>
      </li> 
 <!-- sidebar.blade.php -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-bs-toggle="modal" data-bs-target="#chatBotModal">
        <i class="bx bx-chat"></i>
        <span class="fw-medium">Chat with Support</span>
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
      <li class="nav-heading">HOME</li>
       <!-- Dashboard Link -->
       <li class="nav-item">
            <a class="nav-link {{ Route::is('admin.dashboard') ? '' : 'collapsed' }}" href="{{ route('admin.dashboard') }}">
                <i class='bx bx-home'></i>
                <span class="fw-medium">Dashboard</span>
            </a>
        </li>

  
      
      <li class="nav-heading">ACADEMIC</li>
  
      <!-- Subjects Link -->
      <li class="nav-item">
          <a class="nav-link {{ Route::is('phead.subjects.index') ? '' : 'collapsed' }}" href="{{ route('phead.subjects.index') }}">
              <i class='bx bx-book'></i>
              <span class="fw-medium">Subjects</span>
          </a>
      </li>
  
      <!-- Admission Section -->
      <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.admissions.index') ? 'active' : 'collapsed' }}" href="{{ route('admin.admissions.index') }}">
              <i class="bi bi-door-open"></i>
              <span>Admissions</span>
          </a>
      </li>
  
      <!-- Pre-Enrollment Settings -->
      <li class="nav-item">
          <a class="nav-link {{ Route::is('admin.pre-enrollment.settings') ? '' : 'collapsed' }}" href="{{ route('admin.pre-enrollment.settings') }}">
              <i class='bx bx-cog'></i>
              <span class="fw-medium">Pre-Enrollment</span>
          </a>
      </li>

      <li class="nav-heading">OTHERS</li>
      <!-- Users Dropdown -->
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs(['admin.users.registered', 'admin.users.student', 'admin.users.employee']) ? '' : 'collapsed' }}" data-bs-target="#users-nav" data-bs-toggle="collapse" href="#">
            <i class="ri ri-group-line"></i>
            <span>Users</span>
            <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="users-nav" class="nav-content collapse {{ request()->routeIs(['admin.users.registered', 'admin.users.student', 'admin.users.employee']) ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
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

    <!-- Analytics Dropdown
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs(['admin.analytics.login']) ? '' : 'collapsed' }}" data-bs-target="#analytics-nav" data-bs-toggle="collapse" href="#">
            <i class="ri ri-bar-chart-line"></i>
            <span>Analytics</span>
            <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="analytics-nav" class="nav-content collapse {{ request()->routeIs(['admin.analytics.login']) ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
            <li>
                <a href="{{ route('admin.analytics.login') }}" class="{{ request()->routeIs('admin.analytics.login') ? 'active' : '' }}">
                    <i class="bi bi-circle"></i><span>Logins</span>
                </a>
            </li>
        </ul>
    </li>
     -->

    <!-- Archived Dropdown -->
    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#archived-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-archive"></i>
            <span>Archived</span>
            <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="archived-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li>
                <a href="{{ route('phead.archived-subjects') }}">
                    <i class="bi bi-circle"></i><span>Subjects</span>
                </a>
            </li>
        </ul>
    </li>

  @endif
  


      @if($userPanel === 'program_head')
      <li class="nav-heading">PHEAD PANEL</li>
       <!-- Dashboard Link -->
       <li class="nav-item">
                    <a class="nav-link {{ Route::is('phead.dashboard') ? '' : 'collapsed' }}" href="{{ route('phead.dashboard') }}">
                        <i class='bx bx-home'></i>
                        <span class="fw-medium">Dashboard</span>
                    </a>
                </li>
                    <!-- Pre-Enrollment Link -->
            <li class="nav-item">
                <a class="nav-link {{ Route::is('phead.preenrollment') ? '' : 'collapsed' }}" href="{{ route('phead.preenrollment') }}">
                    <i class='bx bx-lock'></i>
                    <span class="fw-medium">Pre-Enrollment</span>
                </a>
            </li>

 <!-- CHED CMO Management Link -->
 <li class="nav-item">
        <a class="nav-link {{ Route::is('phead.cmos.*') ? '' : 'collapsed' }}" href="{{ route('phead.cmos.index') }}">
            <i class='bx bx-book'></i>
            <span class="fw-medium">CHED CMO Management</span>
        </a>
    </li>
                <!-- Students Link -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('phead.students.index') ? '' : 'collapsed' }}" href="{{ route('phead.students.index') }}">
                        <i class='bx bx-user'></i>
                        <span class="fw-medium">Students</span>
                    </a>
                </li>
            
                <!-- Year & Section Link -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('phead.yearandsection') ? '' : 'collapsed' }}" href="{{ route('phead.yearandsection') }}">
                        <i class='bx bx-calendar'></i>
                        <span class="fw-medium">Year & Section</span>
                    </a>
                </li>
            
                 <!-- Schedules Link -->
            <li class="nav-item">
                <a class="nav-link {{ Route::is('phead.schedules.index') ? 'active' : 'collapsed' }}" href="{{ route('phead.schedules.index') }}">
                    <i class='bx bx-calendar-event'></i>
                    <span class="fw-medium">Schedules</span>
                </a>
            </li>
            
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('calendar.index') ? '' : 'collapsed' }}" href= "{{ Route::is('calendar.index') ? '' : 'collapsed' }}">
                        <i class="bx bx-calendar-event"></i>
                        <span class="fw-medium">Calendar</span>
                    </a>
                </li>
                <!-- Subjects Link -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('phead.subjects.index') ? '' : 'collapsed' }}" href="{{ route('phead.subjects.index') }}">
                        <i class='bx bx-book'></i>
                        <span class="fw-medium">Subjects</span>
                    </a>
                </li>
            
                <!-- Prospectus Link -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('phead.prospectus') ? '' : 'collapsed' }}" href="{{ route('phead.prospectus') }}">
                        <i class='bx bx-book-open'></i>
                        <span class="fw-medium">Prospectus</span>
                    </a>
                </li>
                          
            <li class="nav-heading">OTHERS</li>

             
            <!-- Archived Dropdown -->
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#archived-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-archive"></i>
                    <span>Archived</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="archived-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('phead.prospectus.archived') }}">
                            <i class="bi bi-circle"></i><span>Prospectus Subjects</span>
                        </a>
                    </li>
                </ul>
            </li>

    @endif


      
      @if($userPanel === 'teacher')
     
<li class="nav-heading">MENU</li>
  
    
    <!-- Dashboard Link -->
    <li class="nav-item">
        <a class="nav-link {{ Route::is('teacher.dashboard') ? '' : 'collapsed' }}" href="{{ route('teacher.dashboard') }}">
            <i class='bx bx-home'></i>
            <span class="fw-medium">Dashboard</span>
        </a>
    </li>

       <!-- Schedules Link -->

        <!-- Students List Link for Teacher -->
        <li class="nav-item">
            <a class="nav-link {{ Route::is('teacher.fetchEnrolledStudents') ? '' : 'collapsed' }}" href="{{ route('teacher.fetchEnrolledStudents') }}">
                <i class='bx bx-user'></i>
                <span class="fw-medium">Enrolled Students List</span>
            </a>
        </li>


      <li class="nav-item">
        <a class="nav-link {{ Route::is('teacher.grades.index') ? '' : 'collapsed' }}" href="{{ route('teacher.grades.index') }}">
            <i class='bx bx-bar-chart'></i>
          <span class="fw-medium">Grade Management</span>
        </a>
      </li>
         <!-- Subjects Link -->
         <li class="nav-item">
                    <a class="nav-link {{ Route::is('phead.subjects.index') ? '' : 'collapsed' }}" href="{{ route('phead.subjects.index') }}">
                        <i class='bx bx-book'></i>
                        <span class="fw-medium">Subject's Masterlist</span>
                    </a>
                </li>
            
      <!-- Prospectus Link -->
      <li class="nav-item">
                    <a class="nav-link {{ Route::is('phead.prospectus') ? '' : 'collapsed' }}" href="{{ route('phead.prospectus') }}">
                        <i class='bx bx-book-open'></i>
                        <span class="fw-medium">Student's Prospectus</span>
                    </a>
                </li>
                
                <li class="nav-heading">Archived</li>
                  <!-- Subjects Link -->
               <li class="nav-item">
                    <a class="nav-link {{ Route::is('phead.subjects.index') ? '' : 'collapsed' }}" href="{{ route('phead.subjects.index') }}">
                        <i class='bx bx-book'></i>
                        <span class="fw-medium">Archived Subjects</span>
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

