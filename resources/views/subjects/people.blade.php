@extends('subjects.layout')

@section('title', $subject->subject_code)

@section('subject_content')

<div class="col-12">
  <div class="row g-4 mt-0">

    <!-- User Profile -->
    @foreach ($students as $student)
        @livewire('users.single', ['student' => $student])
    @endforeach
    <!--/ User Profile -->

  </div>

   <!-- Start: Profile Component -->
   @livewire('profile.profile-component')
    <!-- End: Profile Component -->
</div>
@endsection
