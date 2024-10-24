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




<link href="{{ asset('vendor/dropzone/dropzone.css') }}" rel="stylesheet">
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
  <!-- Include Header, Sidebar, or any components -->
  @include('layouts.header')
  <!-- ======= Sidebar ======= -->
  @include('layouts.sidebar')
  <!-- End Sidebar-->
  <main>
    @yield('content') <!-- This will hold the content specific to the pre-enrollment pages -->
  </main>

  <!-- Include Footer -->
  @include('layouts.footer')

  <!-- Include JavaScript files -->
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('js/main.js') }}"></script>
</body>

</html>
