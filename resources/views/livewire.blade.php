@extends('layouts.app')

@section('title', 'Livewire Debug')

@section('content')
<main id="main" class="main">

    <section class="newsfeed-container">
        <div class="row">

            <!-- Main Content -->
            <div class="col-lg-12 col-xl-6">
                <livewire:post-feed />
            </div>
            <!-- End Main Content -->

             <!-- Sidebar Side -->
             <div class="col-lg-5">

                <div class="sticky-top z-0" style="top: 70px;">

                    <div class="card mb-3">
                <div class="card-body">
                <h5 class="card-title text-uppercase pb-0 fs-5"><span>Trends for you</span></h5>
                <ol class="list-group list-group-numbered list-group-flush" style="font-size: 0.9rem !important">
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <a href="https://cecilian-portal.online/hashtag/test">#test</a>
                    </div>
                    <span class="badge bg-primary rounded-pill">
                        2 posts
                    </span>
                </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <a href="https://cecilian-portal.online/hashtag/guys">#guys</a>
                    </div>
                    <span class="badge bg-primary rounded-pill">
                        0 post
                    </span>
                </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <a href="https://cecilian-portal.online/hashtag/randomhashtag">#RandomHashtag</a>
                    </div>
                    <span class="badge bg-primary rounded-pill">
                        0 post
                    </span>
                </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <a href="https://cecilian-portal.online/hashtag/walalang">#WalaLang</a>
                    </div>
                    <span class="badge bg-primary rounded-pill">
                        0 post
                    </span>
                </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <a href="https://cecilian-portal.online/hashtag/noclasstomorrow">#NoClassTomorrow</a>
                    </div>
                    <span class="badge bg-primary rounded-pill">
                        0 post
                    </span>
                </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <a href="https://cecilian-portal.online/hashtag/bsba">#BSBA</a>
                    </div>
                    <span class="badge bg-primary rounded-pill">
                        0 post
                    </span>
                </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <a href="https://cecilian-portal.online/hashtag/goforitdepartment">#GoForITDepartment</a>
                    </div>
                    <span class="badge bg-primary rounded-pill">
                        0 post
                    </span>
                </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <a href="https://cecilian-portal.online/hashtag/hashtag">#hashtag</a>
                    </div>
                    <span class="badge bg-primary rounded-pill">
                        0 post
                    </span>
                </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <a href="https://cecilian-portal.online/hashtag/test1">#test1</a>
                    </div>
                    <span class="badge bg-primary rounded-pill">
                        0 post
                    </span>
                </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <a href="https://cecilian-portal.online/hashtag/test2">#test2</a>
                    </div>
                    <span class="badge bg-primary rounded-pill">
                        0 post
                    </span>
                </li>
                    </ol>
                </div>
                </div>


                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-uppercase pb-0 fs-5"><span>Who to follow</span></h5>
                            <div class="list-group list-group-flush mt-3">
                                
                                <a href="https://cecilian-portal.online/profile/mistisakana" tabindex="-1" class="text-portal list-group-item ps-0 pb-0">
                                    <div class="d-flex flex-wrap mb-1">
                                        <div class="avatar me-2">
                                            <img src="https://cecilian-portal.online/img/profile/profile_2_1693566084.jpg" alt="Avatar" class="rounded-circle profile-sm">
                                        </div>
                                        <div class="ms-1 flex-grow-1">
                                            <div class="mb-0 fs-6 fw-medium">James Javeluna</div>
                                            <small class="text-muted fw-light">@mistisakana</small>
                                        </div>
                                        <div class="">
                                            <button type="button" tabindex="-1"  class="btn btn-outline-primary btn-sm">Follow</button>
                                        </div>
                                    </div>
                                </a>

                                <a href="https://cecilian-portal.online/profile/test" tabindex="-1" class="text-portal list-group-item ps-0 pb-0">
                                    <div class="d-flex flex-wrap mb-1">
                                        <div class="avatar me-2">
                                            <img src="https://cecilian-portal.online/img/profile/default-profile.png" alt="Avatar" class="rounded-circle profile-sm">
                                        </div>
                                        <div class="ms-1 flex-grow-1">
                                            <div class="mb-0 fs-6 fw-medium">Camara, Jessa Mae</div>
                                            <small class="text-muted fw-light">@test</small>
                                        </div>
                                        <div class="">
                                            <button type="button" tabindex="-1"  class="btn btn-outline-primary btn-sm">Follow</button>
                                        </div>
                                    </div>
                                </a>

                            </div>
                            
                        </div>
                    </div>

                    
                </div>

            </div>
            <!-- End Sidebar Side -->

            </div>
    </section>



</main>
@endsection
