<div class="newsfeed-posts">
   
    <div class="card newsfeed">
        
    @foreach ($posts as $post)
            @livewire('posts.single', ['post' => $post, 'isSummary' => true], key($post->id))
    @endforeach
    </div>

    @if (! $this->allContentLoaded)
        <div class="card newsfeed" wire:loading.class.remove="display"  wire:loading.remove>
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
                        <button type="button" class="btn btn-newsfeed btn-sm disabled placeholder">
                            <span class="text-left heart-container" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="The total hearts received of this comment." style="font-size: 12px;">
                                <i class="bi bi-heart me-1"></i> 
                                <span class="react-count text-danger"></span>
                            </span>
                            <span class="heart-count">Love</span>
                        </button>
                        <button type="button" class="btn btn-newsfeed btn-sm disabled placeholder">
                            <i class="bi bi-chat-square me-1"></i>
                            <span class="comment-count">Comment</span>
                        </button>
                    </div>
                </div>
                <div class="comment-section mt-2">
                </div>
            </div>
        </div>

        <div class="card newsfeed" wire:loading.class.remove="display"  wire:loading.remove>
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
                        <button type="button" class="btn btn-newsfeed btn-sm disabled placeholder">
                            <span class="text-left heart-container" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="The total hearts received of this comment." style="font-size: 12px;">
                                <i class="bi bi-heart me-1"></i> 
                                <span class="react-count text-danger"></span>
                            </span>
                            <span class="heart-count">Love</span>
                        </button>
                        <button type="button" class="btn btn-newsfeed btn-sm disabled placeholder">
                            <i class="bi bi-chat-square me-1"></i>
                            <span class="comment-count">Comment</span>
                        </button>
                    </div>
                </div>
                <div class="comment-section mt-2">
                </div>
            </div>
        </div>
    @else
        @if(Auth::user()->countFollowing() === 0)
            <div class="px-3 px-sm-3">
                <h4 class="mb-2 mt-4 text-center fw-bold">Explore & Connect</h4>
                <p class="text-center fw-normal">Join the community and discover interesting posts by following others!</p>
                <img src="{{ asset('img/svg/encourage-followers.svg') }}" class="img-fluid py-5" alt="Page Not Found">
            </div>
        @else
            <div class="px-3 px-sm-3">
                <h4 class="mb-2 mt-4 text-center fw-bold">End of the Realm</h4>
                <p class="text-center fw-normal">You've reached the end of the newsfeed. Keep an eye out for fresh content coming your way soon!</p>
                <img src="{{ asset('img/svg/no-record.svg') }}" class="img-fluid py-5" alt="Page Not Found">
            </div>
        @endif
    @endif

    @if(isset($postId))
        @livewire('posts.post-modal-component', ['postId' => $postId])
    @endif

    <!-- Start: View Component -->
    @livewire('posts.modals.view-component', ['postId' => $postId])
    <!-- End: View Component -->

    <!-- Start: Reply Component -->
    @livewire('posts.modals.reply-component', ['postId' => $postId])
    <!-- End: Reply Component -->

    <!-- Start: Repost Component -->
    @livewire('posts.modals.repost-component', ['postId' => $postId])
    <!-- End: Repost Component -->

    <!-- Start: Profile Component -->
    @livewire('profile.profile-component')
    <!-- End: Profile Component -->

</div>

<script>
    window.addEventListener('scroll', function () {
    console.log('Window scrolled. InnerHeight:', window.innerHeight, 'ScrollY:', window.scrollY, 'Body Height:', document.body.offsetHeight);
    
    if (!@this.allContentLoaded && window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
            console.log('Scrolled to the bottom');
            @this.call('loadMore');
        }
    });


</script>