<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="TemplateMo">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900" rel="stylesheet">

    <title>Cecilian Portal</title>
    <link href="{{ asset('img/favicon.ico') }}" rel="icon">

    <!-- Bootstrap core CSS -->
    <link href="landing-template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">


    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="landing-template/assets/css/fontawesome.css">
    <link rel="stylesheet" href="landing-template/assets/css/templatemo-edu-meeting.css">
    <link rel="stylesheet" href="landing-template/assets/css/owl.css">
    <link rel="stylesheet" href="landing-template/assets/css/lightbox.css">
    <link rel="stylesheet" href="public/assets/script/main.js">

  </head>
<style>
  .caption {
  top: 50% !important;
}

.main-banner {
  background-image: url('assets/images/sccbuilding.png');
  width: 100%;
  height: 100vh;
  background-repeat: no-repeat;
  background-position:center;
  background-size:cover;
  overflow:hidden;
}

.main-button-red {
  display:flex !important;
  gap:10px !important;
}

.button2 {
  background: transparent !important;
  border: 1px solid rgb(190, 190, 190) !important; 
  transition: 0.3s ease-in-out !important;
}

.button2:hover {
  background-color: #a12c2f !important;
  border:1px solid #a12c2f !important;
}

.feature {
  max-width: 350px;
  position: relative !important;
}

.meeting-item .down-content p {
  margin-left:0 !important;
}

.meeting-item .down-content {
  padding: 20px !important;
}

.thumb img {
  height: 200px;
}

.item {
  text-align:center !important; 
  display: flex !important;
  justify-content:center !important;
  align-items:center !important;
  flex-direction: column !important;
  text-align: center !important;
}

.services .item {
  padding: 10px  !important;
  background-image:none !important;
}

.logos p {
  font-size: 24px !important;
  letter-spacing: 20px !important;
  padding: 10px 0 0 20px !important;
  font-weight: 600 !important;
  color: rgb(230,230,230) !important;
}

#down-content {
  max-width: 500px !important;
  /* padding-bottom: 10px !important; */
  position: relative !important;
}

#item-img img {
  height: 170px !important;
}

.tuition {
  position:absolute;
  bottom:25px;
  left:20px;
}

.about-desc {
  background-color: rgb(150,150,150,0.2);
  padding:50px;
  width: 98%;
  position: relative;
  display: flex;
}

.about-desc h3, .about-desc p, .about-desc li {
  color: white;
}

#down-content img {
  width:60px;
  height:60px !important;
}

#down-content h4 {
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:5px 20px;
  margin:0;
}

section.contact-us .right-info {
  overflow:hidden !important;
}

/* .services .owl-nav .owl-next,.services .owl-nav .owl-prev,.our-courses .owl-nav .owl-next,.our-courses .owl-nav .owl-prev {
  display: none !important;
} */

/* .gif {
  width: 900px !important;
  margin-left: -200px !important;
} */
/* .background-header {
  background-color: rgb(143, 0, 0) !important; 
} */

#courses,.video-overlay,.services, #services {
  overflow: hidden !important;
}

#services {
  flex-wrap: wrap;
}

.services img {
  height:200px !important;
  border: 2px solid white !important;
  border-radius: 10px !important;
}

@media (max-width:900px){
  .logos {
    padding-top:0 !important;
    margin-top: 0 !important;
  }
 
  .about-desc {
    flex-direction: column !important;
    display: block !important;  
  }

  .about-desc div {
    width: 100% !important;
  }

  .about-desc div:first-child {
    padding-bottom: 20px !important;
  }

  #services {
    flex-direction: column !important;
    width: 100% !important;
    align-items: center !important;
  }

  #down-content {
    width: 100% !important;
    padding: 0 20px 0 20px !important;
  }

  #item-img {
    position: relative !important;
  }

  .tuition {
    bottom: 25px !important;
  }

  .gif {
    display:none !important;
  }

  #services img {
    max-width:350px !important;
  }

  .feature {
    width: 500px !important;
    margin-bottom: -50px !important;
  }
  
  .services {
    padding: 0 !important;
    margin: 0 !important;
    transform:translateY(-100px) !important;
  }

  #aboutus {
    padding-top: 200px !important;
  }

  .nth-feature {
    padding-bottom: 20px !important;
  }
 
}

@media (max-width:360px) {
  .feature {
    padding-right: 15px !important;
  }
  .logo {
    margin-left: 0 !important;
  }
  .header-area .main-nav .menu-trigger {
      margin-right: -20px !important;
  }
}

</style>
<body>

  <script>
     document.addEventListener('DOMContentLoaded', function() {
        const text = "Welcome to Cecilian's Portal";
        const typingText = document.getElementById('typingText');
        let index = 0;

        function type() {
            if (index < text.length) {
                typingText.textContent += text.charAt(index);
                index++;
                setTimeout(type, 80); // Adjust speed by changing the timeout value (100 ms per character here)
            }
        }

        type();
    });
  </script>
  
  <!-- ***** Header Area Start ***** -->
  <header class="header-area header-sticky" style="top:0">
      <div class="container">
          <div class="row">
              <div class="col-12">
                  <nav class="main-nav" style="display;flex;align-items:center">
                      <!-- ***** Logo Start ***** -->
                
                    
                      <a href="#" class="logo">
                          Cecilian Portal
                      </a>
                      
                     
                      <!-- ***** Logo End ***** -->
                      <!-- ***** Menu Start ***** -->
                      <ul class="nav">
                          <li class="scroll-to-section"><a href="#top" class="active">Home</a></li>
                          <li><a href="#aboutus">About</a></li>
                          <li class="scroll-to-section"><a href="#services">Services</a></li>
                          <li class="scroll-to-section"><a href="#courses">Courses</a></li>
                          <li class="scroll-to-section"><a href="#contact">Contact Us</a></li> 
                      </ul>        
                      <a class='menu-trigger'>
                          <span>Menu</span>
                      </a>
                      <!-- ***** Menu End ***** -->
                  </nav>
              </div>
          </div>
      </div>
  </header>
  <!-- ***** Header Area End ***** -->

<!-- ***** Main Banner Area Start ***** -->
<section class="section main-banner" id="top" data-section="section1">

    <div class="video-overlay header-text">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="caption">
                        <h6>Hello Students</h6>
                        <h2 id="typingText"></h2>
                        <p>The College Student Portal is your digital companion throughout your academic journey...</p>
                        <div class="main-button-red">
                            <a href="{{ route('login') }}">Signin</a>
                            <!-- Link to Admission Form -->
                            <a href="{{ route('admission.tracker') }}"class="button2">Admission Tracker</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

  <!-- ***** Main Banner Area End ***** -->

  <section class="services">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="owl-service-item owl-carousel">
          
            <!-- <div class="item logos">
            
              <img src="assets/images/image1.png" alt="">
            </div>

            <div class="item logos">
             
              <img src="assets/images/image2.png" alt="">
          
            </div> -->

            <div class="item logos">
              <!-- <div class="icon">
                <img src="landing-template/assets/images/service-icon-01.png" alt="">
              </div>
              <div class="down-content">
                <h4>Best Education</h4>
                <p>Suspendisse tempor mauris a sem elementum bibendum. Praesent facilisis massa non vestibulum.</p>
              </div> -->
              <img src="assets/images/image3.png" alt="">
              
            </div>

            <div class="item logos">
             
              <img src="assets/images/image4.png" alt="">
            </div>
         
            <div class="item logos">
             
              <img src="assets/images/image5.png" alt="">
            </div>

            <div class="item logos">
            
              <img src="assets/images/image6.png" alt="">
            </div>

            <div class="item logos">
            
              <img src="assets/images/image7.png" alt="">
            </div>

            <div class="item logos">
            
              <img src="assets/images/image8.png" alt="">
            </div>

            <div class="item logos">
            
              <img src="assets/images/image9.png" alt="">
            </div>

            <div class="item logos">
            
              <img src="assets/images/image10.png" alt="">
            </div>
            
           

          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- About Section - Home Page -->
  <section class="upcoming-meetings" id="aboutus">
    <div class="container">
      <div class="row" style="display:flex;justify-content:center">
        <div class="col-lg-12">
          <div class="section-heading">
            <h2>About Cecilian Portal<br><span style="font-size:14px;font-weight:normal;color:rgb(150,150,150);text-transform:capitalize">The Cecilian Portal is a dedicated platform designed to enhance the academic journey of our students at Cecilian College...
            </span></h2>
          </div>
        </div>

        <div class="about-desc"style="gap:50px">

            <div style="width:40%">
              <img src="assets/images/immersion.png" alt="" style="border-radius:10px">
            </div>

            <div style="width:60%"> 
              <h3>Our Mission</h3>
                <p>Our mission is to provide an accessible, user-friendly digital platform...</p>
                <br>
                <h3>Our Vision</h3>
                <p>We envision a future where the Cecilian Portal becomes an integral part of every student's academic life...</p>
                <br>
                <h3>Our Values</h3>
                <ul>
                  <li><strong>Innovation:</strong> We embrace new ideas and technologies...</li>
                  <li><strong>Accessibility:</strong> We believe in making educational resources available to all students...</li>
                  <li><strong>Community:</strong> We foster a supportive environment...</li>
                  <li><strong>Excellence:</strong> We are committed to delivering high-quality services...</li>
                </ul>
              
                
                <!-- <h3>Join Us Today!</h3>
                <p>Ready to take advantage of everything the Cecilian Portal has to offer? <a href="{{ route('registration') }}">Create an account</a> or <a href="{{ route('login') }}">sign in</a> to get started!</p> -->
            </div>

          </div>
               

          </div>
        </div>
      </div>
    </div>
  </section><!-- End About Section -->

 

  <section class="upcoming-meetings" id="services"style="margin-top:0 !important;padding-top:30px !important;">
    <div class="container">
      <div class="row">

        <div class="col-lg-12">
          <div class="section-heading">
            <h2>What We Do <br><span style="font-size:14px;font-weight:normal;color:rgb(150,150,150);text-transform:capitalize">Explore Our Comprehensive Suite of Student Services</span></h2>
          </div>
        </div>
       
      
        <div style="display:flex;justify-content:center;gap:50px" id="services">
            <div class="col-lg-6 feature">
              <div class="meeting-item">
                <div class="thumb">
                  <a href="#"><img src="assets/images/viewgrades.jpg" alt=""></a>
                </div>
                <div class="down-content">
                  <a href="#"><h4>Grades</h4></a>
                  <p>Track your academic progress.</p>
                </div>
              </div>
            </div>

            <div class="col-lg-6 feature">
              <div class="meeting-item">
                <div class="thumb">
                  <a href="#"><img src="assets/images/viewprospectus.jpg" alt=""></a>
                </div>
                <div class="down-content">
                  <a href="#"><h4>Prospectus</h4></a>
                  <p>Plan your educational journey.</p>
                </div>
              </div>
            </div>

            <div class="col-lg-6 feature">
              <div class="meeting-item">
                <div class="thumb">
                  <a href="#"><img src="assets/images/viewschedule.jpg" alt=""></a>
                </div>
                <div class="down-content">
                  <a href="#"><h4>Schedule</h4></a>
                  <p>Manage your classes and activities.</p>
                </div>
              </div>
            </div>
         
            <div class="col-lg-6 feature">
              <div class="meeting-item">
                <div class="thumb">
                  <a href="#"><img src="assets/images/viewcalendar.jpg" alt=""></a>
                </div>
                <div class="down-content">
                  <a href="#"><h4>Calendar</h4></a>
                  <p>Stay updated with important dates.</p>
                </div>
              </div>
            </div>

            <div class="col-lg-6 feature">
              <div class="meeting-item">
                <div class="thumb">
                  <a href="#"><img src="assets/images/viewprofile.png" alt=""></a>
                </div>
                <div class="down-content">
                  <a href="#"><h4>Profile</h4></a>
                  <p>Personalized your student experience</p>
                </div>
              </div>
            </div>

            <div class="col-lg-6 feature nth-feature">
              <div class="meeting-item">
                <div class="thumb">
                  <a href="#"><img src="assets/images/viewnewsfeed.jfif" alt=""></a>
                </div>
                <div class="down-content">
                  <a href="#"><h4>Newsfeed</h4></a>
                  <p>Stay informed with school updates.</p>
                </div>
              </div>
            </div>
        </div>  
       

      </div>
    </div>
  </section>

  <section class="apply-now" id="apply">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 align-self-center">
          <div class="row">
            <div class="col-lg-12">
              <div class="item">
                <h3>Apply for Your Degree Program</h3>
                <p>Ready to take the next step in your academic journey? Our portal provides a seamless application process for all our degree programs. Whether you’re looking to enroll in BSIT, BSBA, BSHM, or any other course, we’re here to guide you through every step.</p>
                <div class="main-button-red">
                  <div class="scroll-to-section"><a href="#contact">Join Us Now!</a></div>
              </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="item">
              <h3>Discover Our Programs</h3>
              <p>Explore our diverse range of programs designed to meet your academic and career goals. From technology and business to hospitality and criminology, find the program that fits your aspirations and join a community dedicated to your success.</p>
                <div class="main-button-yellow">
                  <div class="scroll-to-section"><a href="#contact">Join Us Now!</a></div>
              </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="accordions is-first-expanded">
            <article class="accordion">
                <div class="accordion-head">
                    <span>Application Process Overview</span>
                    <span class="icon">
                        <i class="icon fa fa-chevron-right"></i>
                    </span>
                </div>
                <div class="accordion-body">
                    <div class="content">
                    <p>Our application process is designed to be simple and efficient. Begin by filling out the online application form. Submit required documents and track your application status directly through our portal. Need assistance? Our support team is here to help at every step.</p>
                    </div>
                </div>
            </article>
            <article class="accordion">
                <div class="accordion-head">
                    <span>Why Choose Cecilian College?</span>
                    <span class="icon">
                        <i class="icon fa fa-chevron-right"></i>
                    </span>
                </div>
                <div class="accordion-body">
                    <div class="content">
                    <p>Cecilian College offers a supportive learning environment with cutting-edge resources. Our programs are designed to equip you with the knowledge and skills necessary to excel in your chosen field. Learn from experienced faculty and gain access to valuable networking opportunities.</p>
                    </div>
                </div>
            </article>
            <article class="accordion">
                <div class="accordion-head">
                    <span>Student Support Services</span>
                    <span class="icon">
                        <i class="icon fa fa-chevron-right"></i>
                    </span>
                </div>
                <div class="accordion-body">
                    <div class="content">
                    <p>Our portal provides access to a range of student support services including academic advising, career counseling, and mental health resources. We are committed to supporting you throughout your academic journey and ensuring your success.</p>
                    </div>
                </div>
            </article>
            <article class="accordion last-accordion">
                <div class="accordion-head">
                    <span>Get In Touch With Us</span>
                    <span class="icon">
                        <i class="icon fa fa-chevron-right"></i>
                    </span>
                </div>
                <div class="accordion-body">
                    <div class="content">
                    <p>If you have any questions or need further assistance, don’t hesitate to reach out. Our admissions team is ready to provide the information you need and help you get started with your application. Contact us today to begin your journey with Cecilian College.</p>
                    </div>
                </div>
            </article>
        </div>
        </div>
      </div>
    </div>
  </section>

  <section class="our-courses" id="courses">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="section-heading">
            <h2>Our Courses Offered</h2>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="owl-courses-item owl-carousel">

            <div class="item" id="item-img">
              <img src="assets/images/bsitimg.jpg" alt="Course One">
              <div class="down-content" id="down-content">
                <h4>BSIT <img src="assets/images/bsit.png" alt=""></h4> 
                <p>Bachelor of Science in Information Technology</p>
                <div class="info">
                  <div class="row">
                    <div class="col-8">
                      
                    </div>
                    <div class="col-4">
                      <p class="tuition">Tuition</p>
                       <span>28,000</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="item" id="item-img">
              <img src="assets/images/bsbaimg.jpg" alt="Course Two">
              <div class="down-content"id="down-content">
              <h4>BSBA <img src="assets/images/bsba.png" alt=""></h4>
                <p>Bachelor of Science in Bussiness Administration</p>
                <div class="info">
                  <div class="row">
                    <div class="col-8">
                      
                    </div>
                    <div class="col-4">
                    <p class="tuition">Tuition</p>
                    <span>18,000</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="item" id="item-img">
              <img src="assets/images/bshmimg.png" alt="">
              <div class="down-content" id="down-content">
              <h4>BSHM <img src="assets/images/bshtm.png" alt=""></h4>
                <p>Bachelor of Science in Hospitality Management</p>
                <div class="info">
                  <div class="row">
                    <div class="col-8">
                      
                    </div>
                    <div class="col-4">
                    <p class="tuition">Tuition</p>
                    <span>28,000</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="item" id="item-img">
              <img src="assets/images/bscrim.jfif" alt="">
              <div class="down-content" id="down-content">
              <h4>BSCRIM <img src="assets/images/crim.png" alt=""></h4>
                <p>Bachelor of Science in Criminology <span style="color:white">asidjisasaij</span></p>
                <div class="info">
                  <div class="row">
                    <div class="col-8">
                      
                    </div>
                    <div class="col-4">
                    <p class="tuition">Tuition</p>
                    <span>28,000</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="item" id="item-img">
              <img src="assets/images/bsedimg.jpg" alt="">
              <div class="down-content" id="down-content">
              <h4>BSED <img src="assets/images/bsed.png" alt=""></h4>
                <p>Bachelor of Science in Secondary Education</p>
                <div class="info">
                  <div class="row">
                    <div class="col-8">
                      
                    </div>
                    <div class="col-4">
                    <p class="tuition">Tuition</p>
                    <span>28,000</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="item" id="item-img">
              <img src="assets/images/bstmimg.jpg" alt="">
              <div class="down-content" id="down-content">
              <h4>BSTM <img src="assets/images/bshtm.png" alt=""></h4>
                <p>Bachelor of Science in Tourism Management</p>
                <div class="info">
                  <div class="row">
                    <div class="col-8">
                     
                    </div>
                    <div class="col-4">
                    <p class="tuition">Tuition</p>
                    <span>28,000</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>


          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="our-facts">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="row">
            <div class="col-lg-12">
              <h2>A Few Facts About Our School</h2>
            </div>
            <div class="col-lg-6">
              <div class="row">
                <div class="col-12">
                  <div class="count-area-content percentage">
                    <div class="count-digit">94</div>
                    <div class="count-title">Succesed Students</div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="count-area-content">
                    <div class="count-digit">126</div>
                    <div class="count-title">Current Teachers</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="row">
                <div class="col-12">
                  <div class="count-area-content new-students">
                    <div class="count-digit">2345</div>
                    <div class="count-title">New Students</div>
                  </div>
                </div> 
                <div class="col-12">
                  <div class="count-area-content">
                    <div class="count-digit">32</div>
                    <div class="count-title">Awards</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> 
        <div class="col-lg-6 align-self-center">
          <div >
            <img src="assets/images/scclogo.gif" alt="" class="gif" style="transform: translateY(-20px)">
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="contact-us" id="contact">
    <div class="container">
      <div class="row">
        <div class="col-lg-9 align-self-center">
          <div class="row">
            <div class="col-lg-12">
              <form id="contact" action="" method="post">
                <div class="row">
                  <div class="col-lg-12">
                    <h2>Let's get in touch</h2>
                  </div>
                  <div class="col-lg-4">
                    <fieldset>
                      <input name="name" type="text" id="name" placeholder="YOURNAME...*" required="">
                    </fieldset>
                  </div>
                  <div class="col-lg-4">
                    <fieldset>
                    <input name="email" type="text" id="email" pattern="[^ @]*@[^ @]*" placeholder="YOUR EMAIL..." required="">
                  </fieldset>
                  </div>
                  <div class="col-lg-4">
                    <fieldset>
                      <input name="subject" type="text" id="subject" placeholder="SUBJECT...*" required="">
                    </fieldset>
                  </div>
                  <div class="col-lg-12">
                    <fieldset>
                      <textarea name="message" type="text" class="form-control" id="message" placeholder="YOUR MESSAGE..." required=""></textarea>
                    </fieldset>
                  </div>
                  <div class="col-lg-12">
                    <fieldset>
                      <button type="submit" id="form-submit" class="button">SEND MESSAGE NOW</button>
                    </fieldset>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="right-info">
            <ul>
              <li>
                <h6>Phone Number</h6>
                <span>010-020-0340</span>
              </li>
              <li>
                <h6>Email Address</h6>
                <span>stcecilia@gmail.com</span>
              </li>
              <li>
                <h6>Street Address</h6>
                <span>Poblacion Ward II, Minglanilla, Cebu</span>
              </li>
              <li>
                <h6>Website URL</h6>
                <span>stcecilia.edu.ph</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="footer">
      <p>Copyright © 2024 CECILIAN PORTAL. All Rights Reserved. 
          <!-- <br>
          Design: <a href="https://templatemo.com" target="_parent" title="free css templates">TemplateMo</a>
          <br>
          Distibuted By: <a href="https://themewagon.com" target="_blank" title="Build Better UI, Faster">ThemeWagon</a>
        </p> -->
    </div>
  </section>

  <!-- Scripts -->
  <!-- Bootstrap core JavaScript -->
    <script src="landing-template/vendor/jquery/jquery.min.js"></script>
    <!-- <script src="landing-template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script> -->

    <script src="landing-template/assets/js/isotope.min.js"></script>
    <script src="landing-template/assets/js/owl-carousel.js"></script>
    <script src="landing-template/assets/js/lightbox.js"></script>
    <script src="landing-template/assets/js/tabs.js"></script>
    <script src="landing-template/assets/js/video.js"></script>
    <script src="landing-template/assets/js/slick-slider.js"></script>
    <script src="landing-template/assets/js/custom.js"></script>
    <script>
        //according to loftblog tut
        $('.nav li:first').addClass('active');

        var showSection = function showSection(section, isAnimate) {
          var
          direction = section.replace(/#/, ''),
          reqSection = $('.section').filter('[data-section="' + direction + '"]'),
          reqSectionPos = reqSection.offset().top - 0;

          if (isAnimate) {
            $('body, html').animate({
              scrollTop: reqSectionPos },
            800);
          } else {
            $('body, html').scrollTop(reqSectionPos);
          }

        };

        var checkSection = function checkSection() {
          $('.section').each(function () {
            var
            $this = $(this),
            topEdge = $this.offset().top - 80,
            bottomEdge = topEdge + $this.height(),
            wScroll = $(window).scrollTop();
            if (topEdge < wScroll && bottomEdge > wScroll) {
              var
              currentId = $this.data('section'),
              reqLink = $('a').filter('[href*=\\#' + currentId + ']');
              reqLink.closest('li').addClass('active').
              siblings().removeClass('active');
            }
          });
        };

        $('.main-menu, .responsive-menu, .scroll-to-section').on('click', 'a', function (e) {
          e.preventDefault();
          showSection($(this).attr('href'), true);
        });

        $(window).scroll(function () {
          checkSection();
        });



    </script>
</body>

</body>
</html>