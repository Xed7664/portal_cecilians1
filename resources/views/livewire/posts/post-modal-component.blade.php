<!-- Post Show Modal-->
<div class="modal fade ps-0" id="postModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen-sm-down">
        <div class="modal-content">
        <div class="modal-header sticky-top bg-body align-content-center text-center">
            <h5 class="modal-title fw-bolder">
                <span class="placeholder col-6"></span>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body z-0 overflow-hidden p-0 bg-body" style="">
            
            <div class="card newsfeed">
                <div class="newsfeed-body card-text placeholder-glow">
                    <div class="author-box d-flex align-items-center justify-content-between">
                        <div class="author">
                            <img class="rounded-circle profile-sm placeholder" alt="no_avatar" style="max-height: 40px;" src="{{ asset('img/profile/default-profile.png') }}">
                            <div class="info ms-2 mt-3">
                            <h5>
                                <a href="" tabindex="-1" class="placeholder">Student Full Name</a>
                            </h5>
                            <p>
                                <a href="#" class="text-secondary placeholder mt-1">Just now</a>
                            </p>
                            </div>
                        </div>
                    </div>
                    <span class="placeholder col-7"></span>
                    <span class="placeholder col-4"></span>
                    <span class="placeholder col-4"></span>
                    <span class="placeholder col-6"></span>
                    <span class="placeholder col-8"></span>
                    <div class="edit-post-section" style="display: none;">
                        <div class="form-group mb-3">
                            <div class="dropdown suggest " data-key="@">
                            <div class="dropdown-menu" role="menu"></div>
                            </div>
                            <textarea class="form-control mention-enabled" id="editPostContent">Hello</textarea>
                        </div>
                        <button type="button" class="btn btn-portal save-edited-post">Save</button>
                        <button type="button" class="btn btn-secondary cancel-edit-post">Cancel</button>
                    </div>
                </div>
                <div class="card-footer card-text placeholder-glow">
                    <div class="d-grid gap-2">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-newsfeed disabled btn-sm placeholder">
                                <i class="bi bi-heart"></i> 
                                <span class="heart-count">Love</span>
                            </button>
                            <button type="button" class="btn btn-newsfeed disabled btn-sm placeholder">
                                <i class="bi bi-chat-square me-1"></i>
                                <span class="comment-count">Comment</span>
                            </button>
                        </div>
                    </div>
                    <div class="comment-section mt-2">
                    <ul class="user-comments list-inline mb-0">
                        <li id="comment-28" class="comment-item" data-commentid="28" data-username="mistisakana">
                            <div class="comment-content">
                                <img src="{{ asset('img/profile/default-profile.png') }}" alt="" class="rounded-circle mt-3 profile-sm">
                                <div class="comment-message">
                                    <div class="alert comment-box alert-dismissible" style="margin-bottom: 0px;">
                                        <h4>
                                            <span class="placeholder col-4"></span>
                                            <span class="placeholder col-4"></span>
                                        </h4>
                                        <span class="placeholder col-4"></span>
                                        <span class="placeholder col-3"></span>
                                        <span class="placeholder col-3"></span>
                                        <span class="placeholder col-2"></span>
                                        <span class="placeholder col-3"></span>
                                        <span class="placeholder col-4"></span>
                                    </div>
                                    <p class="comment-meta">
                                        <span class="ms-2 me-2 text-secondary love-btn ">Love</span>
                                        <span class="me-2 text-secondary reply-btn">Reply</span>
                                        <span data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Thu, October 12, 2023 at 2:07 PM">2 hours ago</span>
                                    </p>
                                </div>
                            </div>
                            <ul class="comment-replies list-inline">
                                
                            </ul>
                        </li>
                    </ul>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="modal-footer sticky-bottom d-flex flex-row bg-body">
            <div class="input-group p-1">
                <textarea class="form-control comment mention-enabled" style="height: 40px; resize: none;" spellcheck="false" placeholder="Write a comment..."></textarea>
                <button type="button" class="btn btn-portal comment-submit" style="display:none;"><i class="ri-send-plane-fill me-1"></i></button>
            </div>
        </div>
        </div>
    </div>
</div>
<!-- End Post Show Modal-->