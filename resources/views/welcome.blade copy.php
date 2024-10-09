<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Student Portal</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <link rel="stylesheet" href="styles/style/css">
    <link rel="stylesheet" href="assets/css/main.css">
     <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

  <!-- Styles -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">
  <link href="styles/style.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/2.0.12/typed.min.js"></script>
  <script src="script/script.js"></script>
</head>

<body class="index-page" data-bs-spy="scroll" data-bs-target="#navmenu">


  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="container-fluid d-flex align-items-center justify-content-between">

      <a href="#" class="logo d-flex align-items-center me-auto me-xl-0">
       <h2>CSP</h2>
        <span>.</span>
      </a>

      <!-- Nav Menu -->
      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#services">Services</a></li>
          <li><a href="#team">Team</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
         
        
        <i class="mobile-nav-toggle d-xl-none bi bi-list" id="bars"></i>
      </nav><!-- End Nav Menu -->

    </div>
  </header><!-- End Header -->

  <main id="main">
      
    <!-- Hero Section - Home Page -->
    <section id="hero" class="hero">
    
      <img src="assets/images/finalhomebg.png" alt="" data-aos="fade-in">

      <div class="container">
        <div class="row">
          <div class="col-lg-10">
            
            <h2 data-aos="fade-up" data-aos-delay="100"><span class="text"></span></h2>
            <p id="subtitle" data-aos="fade-up" data-aos-delay="200">Enhancing Academic Success through Digital Connectivity</p>
         

          <div class="buttons">
          <a href="{{ route('login') }}"><button>Sign In</button></a>
            <a href="login-register.php"><button>Register</button></a>
        </div>
        </div>
      </div>
    
    </section><!-- End Hero Section -->

    

    <div class="hero-bottom">
            <p>COLLEGE OF</p>
            <h1>Information Technology</h1>
              <img src="assets/images/bsitlogo1.png" alt="" id="bsitlogo">
            </div>

    <!-- About Section - Home Page -->
    <section id="about" class="about" style="background-image: url('assets/images/aboutbg2.png')">

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row align-items-xl-center gy-5">

          <div class="col-xl-5 content">
            <h3 id="about">About Us</h3>
            <h2>Welcome to the College Student Portal</h2>
            <p>The College Student Portal is your digital companion throughout your academic journey. Designed to streamline your experience and enhance your academic success, our portal offers a comprehensive array of tools and resources tailored specifically to meet the needs of today's college students. From managing your courses and assignments to accessing campus services and staying informed about events and opportunities, the College Student Portal is your go-to destination for everything you need to thrive in college. Join us as we embark on this journey together and unlock the full potential of your college experience.</p>
            <a href="#" class="read-more"><span>Read More</span><i class="bi bi-arrow-right"></i></a>
          </div>

          <div class="col-xl-7">
            <div class="row gy-4 icon-boxes">
              <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">

                <div class="icon-box">
                <img src="assets/images/csplogo.png" alt="" id="scc">
                </div>
              


            </div>
          </div>

        </div>
      </div>

    </section><!-- End About Section -->

   

      <!-- Services Section - Home Page -->
      <section id="services" class="services" style="background-image: url('assets/images/servicesbg.png')">

        <!--  Section Title -->
        <div class="container section-title" data-aos="fade-up">
          <h2>Services</h2>
          <p>Explore Our Comprehensive Suite of Student Services</p>
        </div><!-- End Section Title -->

        <div class="container">

          <div class="row gy-4">

            <div class="col-lg-6 " class="service-container" data-aos="fade-up" data-aos-delay="600">
              <div class="service-item d-flex">
                <div class="icon flex-shrink-0"><img src="assets/images/enrollment.png" alt=""></div>
                <div >
                  <h4 class="title"><a href="#" class="stretched-link">Enrollment</a></h4>
                  <p class="description">Efficiently manage your enrollment process with our user-friendly online platform. Easily fill out forms, review data, and ensure accuracy for a streamlined experience</p>
                </div>
              </div>
            </div>
            <!-- End Service Item -->

            <div class="col-lg-6 " data-aos="fade-up" data-aos-delay="500">
              <div class="service-item d-flex">
                <div class="icon flex-shrink-0"><img src="assets/images/announcement.png" alt=""></i></div>
                <div>
                  <h4 class="title"><a href="#" class="stretched-link">Announcement</a></h4>
                  <p class="description">Stay informed with important updates and announcements directly from the school, ensuring you're always up-to-date with relevant information and events</p>
                </div>
              </div>

            </div><!-- End Service Item -->

            <div class="col-lg-6 " data-aos="fade-up" data-aos-delay="400">
              <div class="service-item d-flex">
                <div class="icon flex-shrink-0"><img src="assets/images/schedule.png" alt=""></i></div>
                <div class="yawa">
                  <h4 class="title"><a href="#" class="stretched-link">Viewing of Class Schedule</a></h4>
                  <p class="description">Easily access your class schedule online, ensuring you stay organized and prepared for your academic commitments</p>
                </div>
              </div>
            </div><!-- End Service Item -->

            <div class="col-lg-6 " data-aos="fade-up" data-aos-delay="300">
              <div class="service-item d-flex">
                <div class="icon flex-shrink-0"><img src="assets/images/grade.png" alt=""></div>
                <div>
                  <h4 class="title"><a href="#" class="stretched-link">Viewing of Grades</a></h4>
                  <p class="description">Conveniently check your grades online through our platform, providing easy access to your academic performance</p>
                </div>
              </div>
            
            </div><!-- End Service Item -->

            <div class="col-lg-6 " data-aos="fade-up" data-aos-delay="200">
              <div class="service-item d-flex">
                <div class="icon flex-shrink-0"><img src="assets/images/course-catalog.png" alt=""></div>
                <div>
                  <h4 class="title"><a href="#" class="stretched-link">Course Catalog</a></h4>
                  <p class="description">Provide a comprehensive catalog of available courses with detailed descriptions, prerequisites, and course schedules</p>
                </div>
              </div>
            
            </div><!-- End Service Item -->

            <div class="col-lg-6 " data-aos="fade-up" data-aos-delay="100">
              <div class="service-item d-flex">
                <div class="icon flex-shrink-0"><img src="assets/images/profile-management.png" alt=""></div>
                <div>
                  <h4 class="title"><a href="#" class="stretched-link">Student Profile Management</a></h4>
                  <p class="description">Allow students to update personal information, contact details, emergency contacts, and preferences within the portal</p>
                </div>
              </div>
            
            </div><!-- End Service Item -->


          </div>

        </div>

      </section><!-- End Services Section -->


    <!-- Team Section - Home Page -->
    <section id="team" class="team" style="background-image: url('assets/images/aboutbg2.png')">

      <!--  Section Title -->
      <div class="container section-title" data-aos="fade-up" >
        <h2>Team</h2>
        <p>Meet the Dedicated Team Behind Your Student Experience</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-5" id="gy-5">

          <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="100">
            <div class="member-img">
              <img src="assets/images/peter1.png" class="img-fluid" alt="">
              <div class="social">
                <a href="#"><i class="bi bi-twitter"></i></a>
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
            <div class="member-info text-center">
              <h4>Peter John Beroy</h4>
              <span>Project Manager</span>
              <p>I have a keen attention to detail and strong leadership skills, I ensures that projects are delivered on time and within budget, driving our team towards success</p>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="200">
            <div class="member-img">
            <img src="assets/images/johnpaul1.png" class="img-fluid" alt="">
              <div class="social">
                <a href="#"><i class="bi bi-twitter"></i></a>
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
            <div class="member-info text-center">
              <h4>John Paul Cano</h4>
              <span>System Analyst</span>
              <p>With a sharp analytical mind and deep understanding of system dynamics, I ensures seamless functionality and efficiency across all our digital platforms</p>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="300">
            <div class="member-img">
              <img src="assets/images/edwin1.png" class="img-fluid" alt="">
              <div class="social">
                <a href="#"><i class="bi bi-twitter"></i></a>
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
            <div class="member-info text-center">
              <h4>Edwin Ortega</h4>
              <span>Back-end Developer</span>
              <p>I ensures the smooth functioning and robust performance of our systems, enabling seamless user experiences</p>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="400">
            <div class="member-img">
            <img src="assets/images/mark1.png" class="img-fluid" alt="">
              <div class="social">
                <a href="#"><i class="bi bi-twitter"></i></a>
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
            <div class="member-info text-center">
              <h4>Mark Eliezon Aniñon</h4>
              <span>Front-end Developer</span>
              <p>With a keen eye for design and proficiency in coding languages, I transforms ideas into engaging user interfaces</p>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="500">
            <div class="member-img">
              <img src="assets/images/maudy.png" class="img-fluid" alt="">
              <div class="social">
                <a href="#"><i class="bi bi-twitter"></i></a>
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
            <div class="member-info text-center">
              <h4>Maudy Saylanon</h4>
              <span>Documenter</span>
              <p>With attention to detail and strong organizational skills, I meticulously records project processes, specifications, and outcomes</p>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="600">
            <div class="member-img">
            <img src="assets/images/jei.png" class="img-fluid" alt="">
              <div class="social">
                <a href="#"><i class="bi bi-twitter"></i></a>
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
            <div class="member-info text-center">
              <h4>Jei Navarro</h4>
              <span>Software Tester</span>
              <p> With a curious mindset and a commitment to learning, I'm eager to contribute to the field of quality assurance in software development.</p>
            </div>
          </div><!-- End Team Member -->

        </div>

      </div>

    </section><!-- End Team Section -->

   

    <!-- Contact Section - Home Page -->
    <section id="contact" class="contact" style="background-image: url('assets/images/contactusbg3.png')">

      <!--  Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Contact</h2>
        <p>Reach Out to Us for Support and Assistance</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-6" id="col-lg-6">

            <div class="row gy-4">
              <div class="col-md-6">
                <div class="info-item" data-aos="fade" data-aos-delay="200">
                  <i class="bi bi-geo-alt"></i>
                  <h3>Address</h3>
                  <p>Poblacion Ward II, Minglanilla <br> Cebu, Philippines</p>
                </div>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <div class="info-item" data-aos="fade" data-aos-delay="300">
                  <i class="bi bi-telephone"></i>
                  <h3>Call Us</h3>
                  <p>+1 5589 55488 55</p>
                  <p>+1 6678 254445 41</p>
                </div>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <div class="info-item" data-aos="fade" data-aos-delay="400">
                  <i class="bi bi-envelope"></i>
                  <h3>Email Us</h3>
                  <p>scccsp@gmail.com</p>
                </div>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <div class="info-item" data-aos="fade" data-aos-delay="500">
                  <i class="bi bi-clock"></i>
                  <h3>Work Hours</h3>
                  <p>Monday - Friday</p>
                  <p>8:00AM - 05:00PM</p>
                  <p>Saturday</p>
                  <p>8:00AM - :12:00PM</p>
                </div>
              </div><!-- End Info Item -->

            </div>

          </div>

          <div class="col-lg-6">
            <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
              <div class="row gy-4">

                <div class="col-md-6">
                  <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                </div>

                <div class="col-md-6 ">
                  <input type="email" class="form-control" name="email" placeholder="Your Email" required>
                </div>

                <div class="col-md-12">
                  <input type="text" class="form-control" name="subject" placeholder="Subject" required>
                </div>

                <div class="col-md-12">
                  <textarea class="form-control" name="message" rows="6" placeholder="Message" required></textarea>
                </div>

                <div class="col-md-12 text-center">
                  <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Your message has been sent. Thank you!</div>

                  <button type="submit" id="send">Send Message</button>
                </div>

              </div>
            </form>
          </div><!-- End Contact Form -->

        </div>

      </div>

    </section><!-- End Contact Section -->

  </main>

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-5 col-md-12 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
            <span id="span">CSP</span>
          </a>
          <p>Stay Connected with Us - Your Trusted Partner in Academic Success. Explore our services, meet our team, and get in touch for any assistance or inquiries. Together, let's empower your journey towards excellence.</p>
          <div class="social-links d-flex mt-4" id="social-links">
            <a href="#" class="social-icon"><i class="bi bi-twitter"></i></a>
            <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
            <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
            <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
            <img src="assets/images/cspsccgif.gif" alt="" id="cspscc">
          </div>
        </div>

        <div class="col-lg-2 col-6 footer-links" id="footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">About us</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Terms of service</a></li>
            <li><a href="#">Privacy policy</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-6 footer-links" id="footer-links">
          <h4>Our Services</h4>
          <ul>
            <li><a href="#">Enrollment</a></li>
            <li><a href="#">Announcement</a></li>
            <li><a href="#">Viewing of Grades</a></li>
            <li><a href="#">Viewing of Class Schedule</a></li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start" id="footer-links">
          <h4>Contact Us</h4>
          <p>Poblacion Ward II, Minglanilla</p>
          <p>Cebu, Philippines</p>
          <p class="mt-4"><strong>Phone:</strong> <span>+1 5589 55488 55</span></p>
          <p><strong>Email:</strong> <span>scccsp@gmail.com</span></p>
        </div>

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <div>
      
      </div>
      <p>&copy; <span>Copyright</span> <strong class="px-1">College Student Portal</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
      </div>
    </div>

  </footer><!-- End Footer -->

  <!-- Scroll Top Button -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader">
    <div></div>
    <div></div>
    <div></div>
    <div></div>
  </div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>
</html>