<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-heading">SCHOOL YEAR</li>

      <li class="nav-item school_year" data-syid="<?php echo $_SESSION['academic']['sy_id']; ?>" data-semid="<?php echo $_SESSION['academic']['sem_id']; ?>">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#" aria-expanded="false">
          <i class="bi bi-journal-text"></i>
          <span><?php echo $_SESSION['academic']['sy_name']; ?> (<?php echo $_SESSION['academic']['sem_name_acro'] ; ?>)</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav" style="">
          
         <!-- Semester Start -->
            <?php
            $semestersData = json_decode($academic_period->getSemesters($_SESSION['academic']['sy_id']), true);
            $semesters = isset($semestersData['data']) ? $semestersData['data'] : array();
            
            if (empty($semesters)) {
                echo '<li>
                         <a href="#"><i class="bi bi-circle"></i><span>No records available.</span></a>
                     </li>';
            } else {
                foreach ($semesters as $semester) {
                    $semesterType = $semester['semester_type'];
                    $semesterLabel = '';
            
                    switch ($semesterType) {
                        case 'first':
                            $semesterLabel = 'First Semester';
                            break;
                        case 'second':
                            $semesterLabel = 'Second Semester';
                            break;
                        case 'summer':
                            $semesterLabel = 'Summer Semester';
                            break;
                        // Add more cases for other semester types if needed
                    }
            
                    $isActive = ($semester['id'] == $_SESSION['academic']['sem_id']) ? 'active' : '';
            
                    echo '<li class="semester-select" data-semid="' . $semester['id'] . '">';
                    echo '<a href="javascript:void(0);" class="' . $isActive . '"><i class="bi bi-circle"></i><span>' . $semesterLabel . '</span></a>';
                    echo '</li>';
                }
            }
            ?>
            
         <!-- Semester End -->


          <!-- School Year Start -->
          <li class="nav-heading">Previous</li>
          
          <?php
            $school_years_data = json_decode($academic_period->getSchoolYears($_SESSION['academic']['sy_id']), true);
            $school_years = isset($school_years_data['data']) ? $school_years_data['data'] : array();
            
            if (empty($school_years)) {
                echo '<li>';
                echo '<a href="javascript:void(0);"><i class="bi bi-circle"></i><span>No records available.</span></a>';
                echo '</li>';
            } else {
                foreach ($school_years as $school_year) {
                    echo '<li class="year-select" data-syid="'.$school_year['id'].'">';
                    echo '<a href="javascript:void(0);"><i class="bi bi-circle"></i><span>' . $school_year['name'] . '</span></a>';
                    echo '</li>';
                }
            }
          ?>
          
          <!-- School Year End -->



        </ul>
      </li>

      <li class="nav-heading">MENU</li>

      

      <li class="nav-item">
        <a class="nav-link <?php if($page != 'newsfeed.php' && $page != 'newsfeed_community.php' && $page != 'newsfeed_single.php') { echo 'collapsed'; } ?>" href="./../../newsfeed">
          <i class="bi bi-newspaper"></i>
          <span>Newsfeed</span>
        </a>
      </li>
      

      

      
    <li class="nav-heading">Others</li>
    <li class="nav-item">
        <a class="nav-link <?php if($page != 'support.php' && $page != 'support_preview.php') { echo 'collapsed'; } ?>" href="./../support">
          <i class="bi bi-life-preserver"></i>
          <span>Support  
          
          <?php

          require_once 'class/Support.php';
          $support = new Support();

          $pendingCount_raw = json_decode($support->getPendingCount(), true);
          $pendingCount = $pendingCount_raw['data']['PendingCount'];


         if($pendingCount > 0) {
           echo '<span class="badge bg-danger badge-number">'.$pendingCount.'</span>';
         }
         ?>
          </span>
        </a>
      </li>
    </ul>


  </aside>