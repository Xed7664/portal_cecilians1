<div class="d-flex p-3 pb-1 pt-1 border-bottom">
    <img src="{{ asset('img/profile/' . $comment->user->avatar) }}" style="max-height: 40px;" class="rounded-circle profile-sm" alt="Avatar" loading="lazy">
    <div class="d-flex w-100 ps-3">
        <div class="w-100">
            <h6 class="text-body">
                <a href="{{ url('./profile/' . $comment->user->username) }}" class="text-portal" tabindex="-1">
                    <span class="fw-bold">{{ $comment->user->getFullname() }}
                        @if ($comment->user->isOfficial())
                            <i class="bx bxs-badge-check text-primary" style="vertical-align: middle; margin-top: -1px;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Account is official."></i>
                        @endif
                    </span>
                </a>
                <a href="{{ url('./profile/' . $comment->user->username) }}" tabindex="-1">
                    <span class="small text-muted font-weight-normal">{{ '@'.$comment->user->username }}</span>
                </a>
                <span class="small text-muted font-weight-normal"> · </span>

                <a href="{{ url('./profile/' . $comment->user->username .'/posts/'. $comment->id) }}" tabindex="-1">
                    <span class="small text-muted font-weight-normal">{{ $comment->formatTimestamp() }}</span>
                </a>
                
                <span>
                    <i class="bi bi-three-dots float-end" type="button" data-bs-toggle="dropdown"></i>
                    <ul class="dropdown-menu z-3">
                        <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                            <i class="bx bx-user-plus me-1"></i> 
                            <span class="mt-1">Follow @mileycyrus</span>
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                            <i class="bx bx-user-x me-1"></i> 
                            <span class="mt-1">Unfollow @mileycyrus</span>
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                            <i class="bx bx-flag me-1"></i> 
                            <span class="mt-1">Report post</span>
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                            <i class="bx bx-trash me-1"></i> 
                            <span class="mt-1">Delete post</span>
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                            <i class="bx bx-copy me-1"></i> 
                            <span class="mt-1">Copy link</span>
                        </a>
                    </ul>
                </span>
            </h6>
            @php
                $content = $comment->getContentWithMentionsAndHashtags();
                $limit = 480;
                $shortenedContent = strlen($content) > $limit ? substr($content, 0, $limit) : $content;
            @endphp
            <p class="fw-light prewrap" style="line-height: 1.2;">{!! $content !!}</p>
            <ul class="list-unstyled d-flex justify-content-between align-items-center mb-0 pe-xl-5">
                <li wire:click="reactToComment" class="d-flex align-items-center btn-heart {{ $reacted ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="React">
                    <div class="badge text-secondary rounded-pill me-1 p-2">
                        <i class="bx {{ $reacted ? 'bxs-heart text-danger' : 'bx-heart' }} bx-tada-hover bx-sm"></i>
                    </div>
                    <small class="text-secondary">{{ $reactionCount > 0 ? $reactionCount : '' }}</small>
                </li>
                <li class="d-flex align-items-center btn-chat" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Mention">
                    <div class="badge text-secondary rounded-pill me-1 p-2">
                        <i class="bx bx-at bx-tada-hover bx-sm"></i>
                    </div>
                    <small class="text-secondary"></small>
                </li>
                <li class="d-flex align-items-center btn-repost" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Repost">
                    <div class="badge text-secondary rounded-pill me-1 p-2">
                        <i class="bx bx-repost bx-tada-hover bx-sm"></i>
                    </div>
                    <small class="text-secondary"></small>
                </li>
                <li class="d-flex align-items-center btn-stat" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Views">
                    <div class="badge text-secondary rounded-pill me-1 p-2">
                        <i class="bx bx-bar-chart bx-tada-hover bx-sm"></i>
                    </div>
                    <small class="text-secondary">{{ $comment->views_count > 0 ? $comment->getViewsCountAttribute() : '1' }}</small>
                </li>
            </ul>
        </div>
    </div>
</div>