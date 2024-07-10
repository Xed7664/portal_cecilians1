@extends('layouts.app')

@section('title', 'Welcome Page')

@section('content')
<main id="main" class="main px-0 px-sm-3">

    <section class="newsfeed-container">
        <div class="row">

            <!-- Main Content -->
            <div class="col-lg-12 col-xl-6 px-0 px-sm-3">
                @yield('content_post')
            </div>
            <!-- End Main Content -->

            <!-- Sidebar Side -->
            <div class="col-lg-5 d-none d-sm-block">

                <div class="sticky-top z-0" style="top: 70px;">

                    @livewire('posts.sidebar.trending-hashtags')
                    
                    @livewire('posts.sidebar.who-to-follow')
                    
                </div>
                
            </div>
            <!-- End Sidebar Side -->

        </div>
    </section>



</main>


@endsection
