<!DOCTYPE html>
@php
use App\Models\User;
    $userTheme = session('theme');
    $userPanel = session('panel');
    $currentRoute = \Request::route()->getName();
    $userPermissions = Auth::user()->getUserPermissions();
    $rolePermissions = Auth::user()->getRolePermissions();
@endphp

<html lang="en" data-bs-theme="{{ $userTheme === 'light' ? 'light' : 'dark' }}">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>@yield('title') - {{ \App\Models\SystemSetting::getSetting('app_name') }}</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  


  <!-- Favicons -->
  <link href="{{ asset('img/favicon.ico') }}" rel="icon">
  <link href="{{ asset('img/apple-touch-icon.png') }}" rel="apple-touch-icon">

 <!-- Include Leaflet CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<!-- Include Leaflet Geosearch for Nominatim -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<!-- FullCalendar -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.js'></script>
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.0.0/main.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/gsap@3.9.1/dist/gsap.min.js"></script>
<!-- Bootstrap CSS -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery (if needed for Bootstrap 4, but not required for Bootstrap 5) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>



  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>document.getElementsByTagName("html")[0].className += " js";</script>

  <!-- Vendor CSS Files -->
  <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/quill/quill.snow.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/quill/quill.bubble.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/remixicon/remixicon.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/bs-stepper/bs-stepper.css') }}" rel="stylesheet">

<script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>


<!--Toast -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>




@if(in_array($currentRoute, ['admin.users.student']))
<link href="{{ asset('vendor/dropzone/dropzone.css') }}" rel="stylesheet">
  @endif
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  @if(in_array($currentRoute, ['account.show']))
<!-- Croppie css -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
  @endif

@if(in_array($currentRoute, ['calendar.index']))
@endif

@if(in_array($currentRoute, ['schedule.index']))
<link href="{{ asset('vendor/schedule/style.css') }}" rel="stylesheet">
@endif

  
  <!-- Template Main CSS File -->
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <link href="{{ asset('css/grade.css') }}" rel="stylesheet">

  


</head>

<body>

  <!-- ======= Header ======= -->
  @if(!in_array($currentRoute, ['posts.show']))
    @include('layouts.header')
  @else 
  <header id="header" class="header fixed-top d-flex align-items-center ps-2">
    <div class="d-flex align-items-center justify-content-between">
      <a href="{{ route('newsfeed') }}" type="button" class="btn btn-sm">
        <i class="bx bx-arrow-back bx-sm"></i>
      </a>
      <span class="fw-bold ms-3">Posts</span> 
    </div>
  </header> 
  @endif
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
    @include('layouts.sidebar')
  <!-- End Sidebar-->

  <!-- ======= Main ======= -->
    @yield('content')

  <!-- End #main -->

  <!-- ======= Footer ======= -->
    @include('layouts.footer')
  <!-- End Footer -->

  
  <nav class="navbar bg-body-tertiary fixed-bottom py-0 d-block d-sm-none">
    <div class="container-fluid">
      <div class="d-flex w-100">
        <a type="button" href="{{ route('newsfeed') }}" tabindex="-1" class="btn flex-fill p-2">
          <div class="align-self-center icon">
            <i class="ri ri-home-4-line fw-lighter fs-1"></i>
          </div>
        </a>
       
        <a type="button" href="{{ route('scan.page') }}" tabindex="-1" class="btn flex-fill p-2">
          <div class="align-self-center icon">
            <i class="ri ri-qr-scan-line fw-lighter fs-1"></i>
          </div>
        </a>

        <a type="button" href="{{ route('newsfeed') }}" tabindex="-1" class="btn flex-fill p-2">
          <div class="align-self-center icon">
            <i class="ri ri-add-box-line fw-lighter fs-1"></i>
          </div>
        </a>

        <a type="button" href="{{ route('newsfeed') }}" tabindex="-1" class="btn flex-fill p-2">
          <div class="align-self-center icon">
            <i class="ri ri-notification-2-line fw-lighter fs-1"></i>
          </div>
        </a>

        <a type="button" href="{{ route('menu') }}" tabindex="-1" class="btn flex-fill p-2">
          <div class="align-self-center icon mt-2">
          <img src="{{asset('img/profile/'.Auth::user()->avatar.'') }}" alt="" class="rounded-circle profile-xs">
          </div>
        </a>
      </div>
    </div>
  </nav>

  <div class="d-none d-sm-block">
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
      <i class="bi bi-arrow-up-short"></i>
    </a>
  </div>

  
 <div id="toastContainer" class="position-fixed bottom-0 end-0 p-3"></div>

  <!-- Vendor JS Files -->
  @if(in_array($currentRoute, ['admin.analytics.login']))
    @livewireChartsScripts
  @endif

  <!-- <script src="{{ asset('vendor/apexcharts/apexcharts.min.js') }}"></script> -->
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('vendor/chart.js/chart.umd.js') }}"></script>
  <script src="{{ asset('vendor/echarts/echarts.min.js') }}"></script>
  <script src="{{ asset('vendor/quill/quill.min.js') }}"></script>
  <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ asset('vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('vendor/bs-stepper/bs-stepper.js') }}"></script>

  <script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
 
  @if(in_array($currentRoute, ['admin.users.student']))
  <script src="{{ asset('vendor/dropzone/dropzone.js') }}"></script>
  <script src="{{ asset('js/dropzone.js') }}"></script>
  @endif
 
  @if(in_array($currentRoute, ['schedule.index']))
  <script src="{{ asset('vendor/schedule/util.js') }}"></script>
  <script src="{{ asset('vendor/schedule/main.js') }}"></script>
  @endif
   <!-- Scripts -->
 
  @livewireScripts

  <!-- Template Main JS File -->
  <script src="{{ asset('js/main.js') }}"></script>
  <script src="{{ asset('js/app.js') }}"></script>
  @if(in_array($currentRoute, ['newsfeed', 'profile', 'posts.show']))
  <script src="{{ asset('js/post.js') }}"></script>
  @endif

  @if(in_array($currentRoute, ['account.show']))
<!-- Croppie js -->
<script src="{{ asset('vendor/croppie/croppie.js') }}"></script>
@endif

@if(in_array($currentRoute, ['calendar.index']))
<script src="{{ asset('vendor/fullcalendar/index.global.js') }}"></script>
@endif

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('show-toast', (eventData) => {
            const event = eventData[0]; // Access the first (and only) object in the array
           
            // Check if the message is not null
            if (event.message !== null) {
                createToast(event.title, event.message, event.type);
            }

            if (event.sound && typeof event.sound === 'string') {
                // Create a new instance of the audio element
                var audio = new Audio('/snd/' + event.sound);

                // Play the audio
                audio.play();
            }
        });

        Livewire.on('copy-post-link', (data) => {
            const postLink = data[0].postLink;
            copyToClipboard(postLink);
        });

        Livewire.on('scroll-to-child-and-focus', (data) => {
            const elementId = data[0].elementId;
            const childSelector = data[0].childSelector; // Add this line to get the childSelector if provided
            scrollToChildAndFocus(elementId, childSelector); // Pass the childSelector to scrollToElement
        });


    });
</script>
</body>

</html>
