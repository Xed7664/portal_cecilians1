@extends('profiles.layout')

@section('title', $user->getFullName())

@section('profile_content')
<div class="col-12">
  <div class="row">
    <div class="col-xl-5 col-lg-5 col-md-5">
      
        <!-- About User -->
        <div class="card mb-4">
          <div class="card-body">

            <!-- Bio -->
              <div class="card-title text-uppercase pb-0">
                <span>Bio</span>
              </div>

              <div class="text-center">
                  <p class="small fst-italic pb-2 prewrap">{{ $user->bio ?? 'Amidst the silence, a blank canvas awaits...' }}</p>
              </div>

              <!--/ Bio -->

              <div class="card-title text-uppercase pt-0 pb-0">
                <span>About</span>
              </div>

            <ul class="list-unstyled mb-4 mt-3">
              @if($user->getCurrentPosition() !== null)
                  <li class="d-flex align-items-center mb-3">
                      <i class="bx bx-briefcase"></i>
                      <span class="fw-medium mx-1"></span>
                      <span class="fw-light text-break"><b>{{ $user->getCurrentPosition()->name }}</b> in <b>{{ $user->getCurrentPosition()->organization->name }}</b></span>
                  </li>
              @endif
              <li class="d-flex align-items-center mb-3">
                <i class="bx bx-user"></i>
                <span class="fw-medium mx-2">Full Name:</span> 
                <span class="fw-light text-break">{{ $user->getFullname() }}</span>
              </li>
              @if($user->type === 'student')
                <li class="d-flex align-items-center mb-3">
                    <i class="bx bxs-school"></i>
                    <span class="fw-medium mx-2">Degree Program:</span> 
                    <span class="fw-light">{{ Auth::user()->program_code }}</span>
                </li>
                <li class="d-flex align-items-center mb-3">
                    <i class="bx bx-book-content"></i>
                    <span class="fw-medium mx-2">Year Level:</span> 
                    <span class="fw-light">{{ $user->student->yearLevel->name ?? 'No Year Level' }}</span>
                </li>
                <li class="d-flex align-items-center mb-3">
                    <i class="bx bx-label"></i>
                    <span class="fw-medium mx-2">Section:</span> 
                    <span class="fw-light">{{ $user->student->section->name ?? 'No Section' }}</span>
                </li>
                <li class="d-flex align-items-center mb-3">
                    <i class="bx bx-user-check"></i>
                    <span class="fw-medium mx-2">Major:</span> 
                    <span class="fw-light">{{ $user->student->Major ?? 'No Major' }}</span>
                </li>
                <li class="d-flex align-items-center mb-3">
                  <i class="bx bx-infinite"></i>
                  <span class="fw-medium mx-2">Status:</span> 
                  <span class="fw-light">{{ $user->student->Status ?? 'No Status' }}</span>
                </li>
              @endif
            </ul>

            @if (!empty($user->facebook_link) || !empty($user->twitter_link) || !empty($user->instagram_link) || !empty($user->youtube_link) || !empty($user->tiktok_link))
              <div class="card-title text-uppercase pt-0 pb-0">
                <span>Social Media</span>
              </div>

            <ul class="list-unstyled mb-4 mt-3">
              @if(!empty($user->facebook_link))
                <li class="d-flex align-items-center mb-3">
                    <i class="bx bxl-facebook me-2"></i>
                    <a class="fw-light text-break" href="https://www.facebook.com/{{ $user->facebook_link }}" target="_blank">{{ $user->facebook_link }}</a>
                </li>
              @endif

              @if(!empty($user->twitter_link))
                <li class="d-flex align-items-center mb-3">
                    <i class="bx bxl-twitter me-2"></i>
                    <a class="fw-light text-break" href="https://twitter.com/{{ $user->twitter_link }}" target="_blank">{{ $user->twitter_link }}</a>
                </li>
              @endif

              @if(!empty($user->instagram_link))
                <li class="d-flex align-items-center mb-3">
                    <i class="bx bxl-instagram me-2"></i>
                    <a class="fw-light text-break" href="https://www.instagram.com/{{ $user->instagram_link }}" target="_blank">{{ $user->instagram_link }}</a>
                </li>
              @endif

              @if(!empty($user->youtube_link))
                <li class="d-flex align-items-center mb-3">
                    <i class="bx bxl-youtube me-2"></i>
                    <a class="fw-light text-break" href="https://www.youtube.com/{{ '@'.$user->youtube_link }}" target="_blank">{{ $user->youtube_link }}</a>
                </li>
              @endif

              @if(!empty($user->tiktok_link))
                <li class="d-flex align-items-center mb-3">
                    <i class="bx bxl-tiktok me-2"></i>
                    <a class="fw-light text-break" href="https://www.tiktok.com/{{ '@'.$user->tiktok_link }}" target="_blank">{{ $user->tiktok_link }}</a>
                </li>
              @endif
              
            </ul>

          @endif
          
          </div>
        </div>
        <!--/ About User -->
      <div class="sticky-top z-0" style="top: 70px;">
        <!-- Profile Overview -->
        <div class="card mb-4">
          <div class="card-body">
            
            <div class="card-title text-uppercase pb-0">
              <span>Overview</span>
            </div>

            <ul class="list-unstyled mb-0">
              <li class="d-flex align-items-center mb-3">
                <i class="bx bx-heart-circle"></i>
                <span class="fw-medium mx-2">Followers:</span> 
                <span class="fw-light">{{ $user->followers()->count() }} {{ $user->followers()->count() === 1 ? 'follower' : 'followers' }}</span>
              </li>

              <li class="d-flex align-items-center mb-3">
                <i class="bx bx-trophy"></i>
                <span class="fw-medium mx-2">Trophies:</span> 
                <span class="fw-light">0</span>
              </li>
              
              <li class="d-flex align-items-center mb-3">
                <i class="bi bi-chat-text-fill "></i>
                <span class="fw-medium mx-2">Posts:</span> 
                <span class="fw-light">0</span>
              </li>

              <li class="d-flex align-items-center mb-3">
                <i class="bx bx-heart"></i>
                <span class="fw-medium mx-2">Hearts:</span> 
                <span class="fw-light">0</span>
              </li>
            </ul>
          </div>
        </div>
        <!--/ Profile Overview -->
      </div>
    </div>

    <div class="col-xl-7 col-lg-7 col-md-7">
      <!-- Activity Timeline -->
      @livewire('posts.create-post')

      <div class="newsfeed-posts">
        <div class="card newsfeed">
            @foreach ($posts as $post)
                  @livewire('posts.single', ['post' => $post, 'isSummary' => true], key($post->id))
            @endforeach
        </div>
      </div>
    </div>



      <!--/ Activity Timeline -->
      
      
    </div>
  </div>
</div>
@endsection
