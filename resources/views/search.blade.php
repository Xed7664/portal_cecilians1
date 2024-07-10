@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
<main id="main" class="main">

    <div class="pagetitle">
      
    </div>

    <section class="section">
    <div class="row">

        <!-- Main Content -->
        <div class="col-lg-12 col-xl-6">

            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">You searched for '{!! $query !!}'</h5>
                    <ul class="nav nav-tabs nav-tabs-bordered pt-2" id="borderedTab" role="tablist">

                        {{-- Check which has more results and set the order accordingly --}}
                        <li class="nav-item {{ count($postResults) >= count($userResults) ? 'order-1' : 'order-2' }}" role="presentation">
                            <button class="nav-link {{ count($postResults) >= count($userResults) ? 'active' : '' }}" id="posts-tab" data-bs-toggle="tab" data-bs-target="#bordered-posts" type="button" role="tab" aria-controls="profile" aria-selected="{{ count($postResults) >= count($userResults) ? 'true' : 'false' }}">Posts</button>
                        </li>

                        <li class="nav-item {{ count($postResults) >= count($userResults) ? 'order-2' : 'order-1' }}" role="presentation">
                            <button class="nav-link {{ count($postResults) >= count($userResults) ? '' : 'active' }}" id="people-tab" data-bs-toggle="tab" data-bs-target="#bordered-people" type="button" role="tab" aria-controls="contact" aria-selected="{{ count($postResults) >= count($userResults) ? 'false' : 'true' }}" tabindex="-1">Students</button>
                        </li>

                    </ul>
                </div>
            </div>

            <div class="tab-content" id="borderedTabContent">
                <div class="tab-pane fade show @if(count($postResults) > count($userResults)) active @endif" id="bordered-posts" role="tabpanel" aria-labelledby="posts-tab">
                    <!-- All Post Start -->
                        <div class="newsfeed-posts">    
                            <div class="card newsfeed">                   
                                @foreach ($postResults as $post)
                                    @livewire('posts.single', ['post' => $post, 'isSummary' => false], key($post->id))
                                @endforeach
                            </div>
                        </div>
                    <!-- All Post End -->
                </div>
                
                <div class="tab-pane fade show @if(count($userResults) > count($postResults)) active @endif" id="bordered-people" role="tabpanel" aria-labelledby="people-tab">
                    <!-- Users Start -->
                    <div class="newsfeed-posts">
                        @forelse ($userResults as $user)
                            <div class="card newsfeed mb-2" data-stdid="{{ $user->id }}">
                                <div class="newsfeed-body">
                                    <div class="author-box d-flex align-items-center justify-content-between">
                                        <div class="author">
                                            <img src="{{ asset('img/profile/' . $user->avatar) }}" style="max-height: 40px;" alt="Profile" class="rounded-circle profile-sm">
                                            <div class="info ms-2 mt-3">
                                                <h5>
                                                    <a href="{{ url('./profile/' . $user->id) }}">{{ $user->getFullname() }}</a>
                                                </h5>
                                                <p>
                                                    <a href="{{ url('./profile/' . $user->id) }}" class="text-secondary">{{ $user->type }}</a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="menu">
                                            <a href="{{ url('./profile/' . $user->id) }}">
                                                <button class="btn btn-danger" type="button"><i class="bi bi-eye"></i></button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <!-- Display a message when there are no user results -->
                            <p>No students found.</p>
                        @endforelse
                    </div>
                    <!--/ Users End -->
                </div>


            </div>


        </div>
        <!-- End Main Content -->

    </div>
</section>

  </main><!-- End #main -->


@endsection
