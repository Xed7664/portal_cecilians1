<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="
      
    <?php 
    
    $panel = $_SESSION['user']['panel'];

    if($panel === 'admin'){
        echo './../../admin/students';
    } else {
        echo './../../';
    }
    
    ?>" class="logo d-flex align-items-center">
        <img src="./../../assets/img/SCC.png" alt="">
        <span class="d-none d-lg-block">Cecilian Portal</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword" value="<?php if($page == 'search.php') { echo htmlspecialchars(filter_var($_GET['keyword'], FILTER_SANITIZE_STRING), ENT_QUOTES, 'UTF-8');; } ?>">
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

        <li class="nav-item">
          <a class="nav-link nav-icon" href="./../../scanner-1">
            <i class="bi bi-upc-scan"></i>
          </a>
        </li>

        <li class="nav-item dropdown">
          
          <?php
                // Updates notification count session
                $_SESSION['user']['notification_count'] = $notification->countNotifications();
          ?>

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <?php
                if ($_SESSION['user']['notification_count'] > 0) {
                    echo '<span class="badge bg-primary badge-number">' . $_SESSION['user']['notification_count'] . '</span>';
                }
            ?>
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages" style="max-height: 500px; overflow-y: auto;">
            
            <?php
                

                $notifications = $notification->getNotifications();
                $notifications_data = json_decode($notifications, true); // Convert JSON string to associative array
                //var_dump($notifications_data);

                // Check if notifications data is empty
                if (empty($notifications_data['data'])) {
                    // Handle empty results here, you can display a message or perform any action as needed.
                    ?>
                    <li class="dropdown-header">
                      You have no notifications
                    </li>
                <?php
                } else {
                    ?>
                    <div class="container text-center p-3">
                      <div class="row align-items-start">
                        <div class="col h5 fw-semibold">
                          Notifications
                        </div>
                        <div class="col">
                          <span class="badge rounded-pill bg-primary p-2 ms-2 mark-all-read" type="button">Mark all as read</span>
                        </div>
                      </div>
                    </div>

                    <li>
                      <hr class="dropdown-divider">
                    </li>
                    <?php
                    foreach ($notifications_data['data'] as $notification) {
                        ?>
                        <li class="message-item notification-item" data-notifid="<?php echo $notification['id']; ?>">
                          <a href="<?php echo $notification['location']; ?>">
                            <img src="<?php echo $notification['sender_image']; ?>" alt="" class="rounded-circle profile-sm">
                            <div>
                              <p><?php echo $notification['text']; ?></p>
                              <p><?php echo $notification['time_ago']; ?></p>
                            </div>
                            <?php

                            if($notification['status'] === 'new'){
                                ?>
                                <div>
                                <i class="bi bi-dot"></i>
                                </div>
                                <?php
                            }

                            ?>
                          </a>
                        </li>
                        <li>
                          <hr class="dropdown-divider">
                        </li>
                        <?php
                    }
                    ?>
                    <li class="dropdown-footer">
                      <span class="fw-light fst-italic">In the realm of notifications, an echo lingers and journeys remain unfinished.</span>
                    </li>
                    <?php
                }
            ?>


            

          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->
        <?php
        /*
        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-chat-left-text"></i>
            <span class="badge bg-success badge-number">1</span>
          </a><!-- End Messages Icon -->
          
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
            <li class="dropdown-header">
              You have 1 new messages
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="../assets/img/profile/james.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>James Javeluna</h4>
                  <p>Hi, this is a test message.</p>
                  <p>4 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="dropdown-footer">
              <a href="#">Show all messages</a>
            </li>
          
          </ul><!-- End Messages Dropdown Items -->
          
        </li><!-- End Messages Nav -->
        */
        ?>

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <?php
                $avatar = isset($_SESSION['user']['avatar']) ? $_SESSION['user']['avatar'] : 'default-profile.png';   

                echo '<img src="./../../assets/img/profile/'.$avatar.'" alt="Profile" class="rounded-circle profile-sm">';
            ?>
          
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $utility->abbreviateName($_SESSION['user']['FullName']); ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $_SESSION['user']['FullName']; ?></h6>
              <span><?php echo $utility->capitalizeFirstLetter($_SESSION['user']['role']); ?></span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="<?php echo './../../profile/'.$_SESSION['user']['id']; ?>">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="./../../myqrcode">
                <i class="ri ri-qr-code-line"></i>
                <span>My QR code</span>
              </a>
            </li>


            <li>
              <hr class="dropdown-divider">
            </li>


            <li>
              <a class="dropdown-item d-flex align-items-center" href="./../../settings">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>

             <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="./../../help">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            
            <?php

            if($utility->hasPermission(PERM_ADMIN_PANEL)){
                if($_SESSION['user']['panel'] === 'admin'){
                    echo '<li class="switchPanels">
                      <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                        <i class="bi bi-toggle-on text-danger"></i>
                        <span>Admin Panel</span>
                      </a>
                    </li>
                    <li>
                      <hr class="dropdown-divider">
                    </li>';
                } else {
                    echo '<li class="switchPanels">
                      <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                        <i class="bi bi-toggle-off"></i>
                        <span>Admin Panel</span>
                      </a>
                    </li>
                    <li>
                      <hr class="dropdown-divider">
                    </li>';
                }
            }

            ?>

            <li id="switchTheme">
              <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                <i class="bi bi-cloud-sun-fill"></i>
                <span><?php echo ($userTheme === 'light') ? 'Dark Mode' : 'Light Mode'; ?></span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a id="logout" class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header>