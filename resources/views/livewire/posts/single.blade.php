<div>
@if ($post->status === 'publish')
    <div class="d-flex p-3 border-bottom">
            <img src="{{ asset('img/profile/' . $post->user->avatar) }}" style="max-height: 40px;" class="rounded-circle profile-sm" alt="Avatar" loading="lazy">
            <div class="d-flex w-100 ps-3">
                <div class="w-100">
                    <h6 class="text-body">
                        <a href="{{ url('./profile/' . $post->user->username) }}" class="text-portal" tabindex="-1">
                            <span class="fw-bold">{{ $post->user->getFullname() }}
                                @if ($post->user->isOfficial())
                                    <i class="bx bxs-badge-check text-primary" style="vertical-align: middle; margin-top: -1px;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Account is official."></i>
                                @endif
                            </span>
                        </a>
                        <a href="{{ url('./profile/' . $post->user->username) }}" tabindex="-1">
                            <span class="small text-muted font-weight-normal">{{ '@'.$post->user->username }}</span>
                        </a>
                        <span class="small text-muted font-weight-normal"> · </span>

                        <a href="{{ url('./profile/' . $post->user->username .'/posts/'. $post->id) }}" tabindex="-1">
                            <span class="small text-muted font-weight-normal">{{ $post->formatTimestamp() }}</span>
                        </a>

                        <span>
                            <i class="bi bi-three-dots float-end" type="button" data-bs-toggle="dropdown"></i>
                            <ul class="dropdown-menu z-3">
                                @if(Auth::user()->id !== $post->user->id)

                                    @if($post->user->followed)
                                        <a wire:click="followUser()" class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                                            <i class="bx bx-user-x me-1"></i>
                                            <span class="mt-1">Unfollow {{ '@'.$post->user->username }}</span>
                                        </a>
                                    @else
                                        <a wire:click="followUser()" class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                                            <i class="bx bx-user-plus me-1"></i>
                                            <span class="mt-1">Follow {{ '@'.$post->user->username }}</span>
                                        </a>
                                    @endif
                                    
                                @endif

                                @if(Auth::user()->id === $post->user->id)
                                    <a wire:click="deletePost()" class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                                        <i class="bx bx-trash me-1"></i> 
                                        <span class="mt-1">Delete post</span>
                                    </a>
                                @else
                                    <a wire:click="reportPost()" class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                                        <i class="bx bx-flag me-1"></i> 
                                        <span class="mt-1">Report post</span>
                                    </a>
                                @endif

                                <a wire:click="copyPostLinkToClipboard()" class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                                    <i class="bx bx-copy me-1"></i> 
                                    <span class="mt-1">Copy link</span>
                                </a>
                            </ul>
                        </span>
                    </h6>
                    @php
                        $content = $post->getContentWithMentionsAndHashtags();
                        $limit = 480;
                        $shortenedContent = strlen($content) > $limit ? substr($content, 0, $limit) : $content;
                    @endphp
                    <p class="fw-light prewrap" style="line-height: 1.2;">{!! $content !!}</p>

                    @if($isRepost)
                        <!-- Start: Repost Original Content -->
                        <div class="border rounded-3 mt-3 p-2">
                            <div class="d-flex align-items-center">
                                @if ($post->originalPost != null)
                                    <img src="{{ asset('img/profile/' . $post->originalPost->user->avatar) }}" style="max-height: 28px;" class="rounded-circle profile-xs" alt="Avatar" loading="lazy">
                                    <div class="d-flex w-100 ps-3">
                                        <div class="w-100">
                                            <h6 class="text-body">
                                                <a href="{{ url('./profile/' . $post->originalPost->user->username) }}" class="text-portal" tabindex="-1">
                                                    <span class="fw-bold">{{ $post->originalPost->user->getFullname() }}
                                                        @if ($post->originalPost->user->isOfficial())
                                                            <i class="bx bxs-badge-check text-primary" style="vertical-align: middle; margin-top: -1px;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Account is official."></i>
                                                        @endif
                                                    </span>
                                                </a>
                                                <a href="{{ url('./profile/' . $post->originalPost->user->username) }}" tabindex="-1">
                                                    <span class="small text-muted font-weight-normal">{{ '@'.$post->originalPost->user->username }}</span>
                                                </a>
                                                <span class="small text-muted font-weight-normal"> · </span>

                                                <a href="{{ url('./profile/' . $post->originalPost->user->username .'/posts/'. $post->originalPost->id) }}" tabindex="-1">
                                                    <span class="small text-muted font-weight-normal">{{ $post->originalPost->formatTimestamp() }}</span>
                                                </a>
                                            </h6>
                                        </div>
                                    </div>
                                @else
                                    <div class="p-2 small">
                                        <span class="fw-bold">This content isn't available right now. </span>
                                        <p class="text-muted small mb-0">
                                            It may have been deleted or is against our terms.
                                        </p>
                                    </div>
                                @endif
                            </div>

                            @if ($post->originalPost != null)
                                @php
                                    $original_content = $post->originalPost->getContentWithMentionsAndHashtags();
                                    $limit = 480;
                                    $shortenedContent = strlen($original_content) > $limit ? substr($original_content, 0, $limit) : $original_content;
                                @endphp
                                <p class="fw-light prewrap small mb-0 mt-3" style="line-height: 1.2;">{!! $original_content !!}</p>
                            @endif
                        </div>
                        <!-- End: Repost Original Content -->
                    @endif

                    <ul class="list-unstyled d-flex justify-content-between align-items-center mb-0 pe-xl-5">
                        <li wire:click="reactToPost" class="d-flex align-items-center btn-heart {{ $reacted ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="React">
                            <div class="badge text-secondary rounded-pill me-1 p-2">
                                <i class="bx {{ $reacted ? 'bxs-heart text-danger' : 'bx-heart' }} bx-tada-hover bx-sm"></i>
                            </div>
                            <small class="text-secondary">{{ $reactionCount > 0 ? $reactionCount : '' }}</small>
                        </li>
                        <li wire:click="openModal('reply', '{{ $post->id }}')" data-bs-toggle="modal" data-bs-target="#replyModal" class="d-flex align-items-center btn-chat">
                            <div class="badge text-secondary rounded-pill me-1 p-2" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Reply">
                                <i class="bx bx-chat bx-tada-hover bx-sm"></i>
                            </div>
                            <small class="text-secondary">{{ $commentsCount > 0 ? $commentsCount : '' }}</small>
                        </li>
                        <li wire:click="openModal('repost', '{{ $isRepost ? optional($post->originalPost)->id : $post->id }}')" 
                            
                            @if($isRepost && $post->originalPost == null) 
                                {{-- If it's a repost and the original content is null, don't include the modal attributes --}}
                            @else
                                data-bs-toggle="modal" data-bs-target="#repostModal"
                            @endif
                        
                            class="d-flex align-items-center btn-repost">
                            <div class="badge text-secondary rounded-pill me-1 p-2" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Repost">
                                <i class="bx bx-repost bx-tada-hover bx-sm"></i>
                            </div>
                            <small class="text-secondary">{{ $repostsCount > 0 ? $repostsCount : '' }}</small>
                        </li>
                        <li wire:click="openModal('views', '{{ $post->id }}')" data-bs-toggle="modal" data-bs-target="#viewModal" class="d-flex align-items-center btn-stat">
                            <div class="badge text-secondary rounded-pill me-1 p-2" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Views">
                                <i class="bx bx-bar-chart bx-tada-hover bx-sm"></i>
                            </div>
                            <small class="text-secondary">{{ $post->views_count > 0 ? $post->formatViewsCount($post->views_count) : '1' }}</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
@endif
</div>