@extends('posts.layout')

@section('title', 'Newsfeed')

@section('content_post')
    @inject('postController', 'App\Http\Controllers\PostController')

    @livewire('posts.create-post')
    @livewire('posts.post-component')
@endsection

@section('content_sidebar')
    <!-- Include the enrollment status in the sidebar -->
    <div class="sticky-top z-0" style="top: 70px;">
        @livewire('posts.sidebar.trending-hashtags')

        <div class="card mb-3">
   <div class="card-body">
      <h5 class="card-title text-uppercase pb-0 fs-5"><span>Your Enrollment Status </span></h5>

      @if(isset($student))
      <div class="enrollment-flow">
         <div class="enrollment-step {{ $student->isAdmissionComplete ? 'completed' : '' }}">
            <div class="icon"><i class="fas fa-user-check"></i></div>
            <p>Admission Process</p> <!-- Hidden by default, shown on hover -->
         </div>

         <div class="enrollment-step {{ $student->isDocumentSubmitted ? 'completed' : '' }}">
            <div class="icon"><i class="fas fa-file-alt"></i></div>
            <p>Document Submission</p>
         </div>

         <div class="enrollment-step {{ $student->isEnrolled ? 'completed' : '' }}">
            <div class="icon"><i class="fas fa-user-graduate"></i></div>
            <p>Enrollment Process</p>
         </div>
         <div class="enrollment-step {{ $student->isConfirmed ? 'completed' : '' }}">
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <p>Final Confirmation</p>
         </div>
       
         <div class="enrollment-step {{ $student->isPaymentComplete ? 'completed' : '' }}">
            <div class="icon"><i class="fas fa-credit-card"></i></div>
            <p>Payment</p>
         </div>

      
         <div class="enrollment-step {{ $student->isScheduleAssigned ? 'completed' : '' }}">
                <div class="icon"><i class="fas fa-building"></i></div>
                <p>Department</p>
            </div>


      </div>
      @else
         <p>No enrollment status available.</p>
      @endif
   </div>
</div>


        @livewire('posts.sidebar.who-to-follow')
    </div>
@endsection
<script>
   // GSAP animation for smooth transitions
   document.addEventListener('DOMContentLoaded', function() {
      gsap.from(".enrollment-step", {
         duration: 0.8,
         opacity: 0,
         y: 50,
         stagger: 0.2,
         ease: "power1.inOut"
      });
   });
</script>
<style>
 .enrollment-flow {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* Responsive 3-column layout */
    gap: 30px; /* Space between steps */
    padding: 10px;
    position: relative;
}

.enrollment-step {
    position: relative;
    background-color: #f8f9fa;
    border-radius: 50%;
    width: 60px;  /* Step icon size */
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.3s ease-in-out;
    cursor: pointer;
}

/* Completed steps */
.enrollment-step.completed {
    background-color: #28a745;
    color: white;
}

/* Line between steps */
.enrollment-step::after {
    content: '';
    position: absolute;
    background-color: #007bff;
    width: 3px;
    height: 60px; /* Default line height */
    transition: all 0.3s ease-in-out;
}

/* Horizontal line for top and middle row */
.enrollment-step:nth-child(1)::after,
.enrollment-step:nth-child(2)::after {
    top: 50%;
    right: -70px;
    transform: translateY(-50%);
    width: 70px; /* Horizontal line width */
    height: 3px; /* Horizontal line thickness */
}


.enrollment-step:nth-child(4)::after {
    top: 50%;
    right: -70px;
    transform: translateY(-50%);
    width: 70px; /* Horizontal line width */
    height: 3px; /* Horizontal line thickness */
}
.enrollment-step:nth-child(5)::after {
    top: 50%;
    right: -70px;
    transform: translateY(-50%);
    width: 70px; /* Horizontal line width */
    height: 3px; /* Horizontal line thickness */
}
/* Vertical line for next row */

.enrollment-step:nth-child(3)::before {
    content: '';
    position: absolute;
    background-color: #007bff;
    width: 3px;
    height: 60px;
    top: 100%; /* Position it directly below */
    left: 50%;
    transform: translateX(-50%);
}

/* Hide the line after the last step */
.enrollment-step:nth-child(6)::after,
.enrollment-step:nth-child(3)::after {
    display: none;
}

.enrollment-step:nth-child(6)::before {
    display: none;
}

/* Text hidden initially */
.enrollment-step p {
    display: none;
    position: absolute;
    bottom: -40px;
    background-color: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    white-space: nowrap;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.enrollment-step:hover p {
    display: block;
    opacity: 1;
}

</style>