@extends('layouts.app')

@section('content')

<main id="main" class="main">

    <section class="section profile">

        <!-- Profile Header Start -->
        <div class="row">
          <div class="col-12">
            <div class="card mb-4">
              <div class="user-profile-header-banner">
                <img src="{{ asset('img/profile/cover/scc.jpg') }}" alt="Banner image" class="rounded-top">
              </div>
              <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-3">
                <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                <img src="{{ asset('img/profile/'.$user->avatar.'') }}" alt="user image" class="d-block ms-0 ms-sm-4 rounded user-profile-img border-dark" style="width: 120px; height: 120px; object-fit: cover;">
                </div>
                <div class="flex-grow-1 mt-3 mt-sm-5">
                  <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                    <div class="user-profile-info">
                      <div class="d-flex justify-content-sm-start justify-content-center">
                        <h4 class="mb-0">{{ $user->getFullname() }}</h4>
                        @if ($user->isOfficial())
                            <i class="bx bxs-badge-check text-danger align-self-center ps-1" style="vertical-align: middle; margin-top: -1px;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Account is official."></i>
                        @endif
                      </div>
                      <span class="fw-light mt-0">{{ '@'.$user->username }}</b></span>
                      
                      <ul class="list-inline mt-2 mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                        @if($user->type === 'student')
                        <li class="list-inline-item d-flex gap-1">
                          <i class="bx bxs-school mt-1"></i> 
                          <span class="fw-light"><b>{{ Auth::user()->program_code }}</b> Department</span>
                        </li>
                        @else
                        <li class="list-inline-item d-flex gap-1">
                          <i class="bx bxs-school mt-1"></i> 
                          <span class="fw-light"><b>College Faculty</b> Department</span>
                        </li>
                        @endif
                        <li class="list-inline-item d-flex gap-1">
                          <i class="bx bx-heart-circle mt-1"></i>
                          <span class="followers-count fw-light"><b>{{ $user->followers()->count() }}</b> {{ $user->followers()->count() === 1 ? 'follower' : 'followers' }}</span>
                        </li>
                        <li class="list-inline-item d-flex gap-1">
                          <i class="bx bx-calendar mt-1"></i>
                          <span class="fw-light">Joined on <b>{{ $user->created_at->format('F Y') }}</b></span>
                        </li>
                      </ul>
                    </div>
                    <div class="">
                      @if(auth()->check() && auth()->user()->id !== $user->id)
                          <button class="follow-button btn btn-portal"
                              data-user-id="{{ $user->id }}"
                              data-operation="follow">
                              <i class="{{ auth()->user()->isFollowing($user) ? 'bx bxs-heart-circle' : 'bx bx-heart-circle' }} me-1 mt-1"></i>
                              <span class="button-text">{{ auth()->user()->isFollowing($user) ? 'Unfollow' : 'Follow' }}</span>
                          </button>
                      @endif

                      @if(auth()->check() && $user->id === auth()->user()->id)
                      
                        <a href="{{ route('account.show', ['page' => 'account']) }}" class="btn btn-danger">
                          <span class="d-none d-sm-block small"><i class="ri ri-edit-box-line me-1"></i> Edit</span>
                          <i class="ri ri-edit-box-line d-block d-sm-none"></i>
                        </a>
                      @endif

                      <button type="button" class="btn" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="bi bi-three-dots-vertical"></i>
                          <div class="dropdown-menu dropdown-menu-end" style="overflow: hidden;">
                              <a class="dropdown-item edit-post" href="javascript:void(0);"><i class="bi bi-exclamation-circle me-1"></i> Report user</a>
                              <a class="dropdown-item share-post" href="javascript:void(0);"><i class="ri ri-file-copy-line me-1"></i> Copy link</a>
                          </div>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <ul class="nav nav-tabs nav-tabs-bordered border-top pt-3 border-light-subtle border-opacity-10 border-1" role="tablist">
                  <li class="nav-item" role="presentation">
                      <a class="nav-link {{ request()->is("profile/$user->username") ? 'active' : '' }}" href="{{ route('profile.show', ['username' => $user->username]) }}" type="button"><i class="bx bx-user-check me-1"></i> Posts</a>
                  </li>
                  <li class="nav-item" role="presentation">
                      <a class="nav-link {{ request()->is("profile/$user->username/trophies") ? 'active' : '' }}" href="{{ route('profile.trophy', ['username' => $user->username]) }}" type="button" tabindex="-1"><i class="bx bx-trophy me-1"></i> Trophies</a>
                  </li>
              </ul>

            </div>
        </div>

        <!-- Profile Header End -->

        <!-- ======= Profile Content ======= -->
          @yield('profile_content')
        <!-- ======/ Profile Content ======= -->

      </div>
    </section>
</main>

<script>
$(document).ready(function () {
    $('.follow-button').on('click', function () {
        const button = $(this);
        const userId = button.data('user-id');
        const operation = button.data('operation'); // Get the operation from data attribute

        // Define the request data
        const requestData = {
            id: userId,
            // Include any other data you need for the request
        };

        sendAjaxRequest(operation, requestData, function (response) {
            if (response.result === true) {
                // Update the button text based on the follow boolean
                const buttonText = response.data.following ? 'Unfollow' : 'Follow';
                button.find('.button-text').text(buttonText);

                // Update the icon based on the follow boolean
                const iconClass = response.data.following ? 'bx bxs-heart-circle' : 'bx bx-heart-circle';
                button.find('i').removeClass().addClass(iconClass); // Remove existing classes and set the new class

                // Optionally update the followers count on the page
                const followersCountElement = $('.followers-count');
                if (followersCountElement.length) {
                    followersCountElement.html('<b>' + response.data.followers_count + '</b> ' + response.data.followers_label);
                }
            }

            // Display a toast message
            createToast('Success', response.message, 'success');
        }, function (errorMessage) {
            // Display an error toast message
            createToast('Error', errorMessage, 'danger');
        });
    });
});
</script>


@endsection
