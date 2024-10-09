@inject('postController', 'App\Http\Controllers\PostController')

<div class="useless">
        <div class="d-flex p-3">
            <img src="{{ asset('img/profile/' . $post->user->avatar) }}" style="max-height: 40px;" class="rounded-circle profile-sm" alt="Avatar" loading="lazy">
            <div class="d-flex w-100 ps-3">
                <div class="w-100">
                    <h6 class="text-body mb-0">
                        <a href="{{ url('./profile/' . $post->user->username) }}" class="text-portal" tabindex="-1">
                            <span class="fw-bold">{{ $post->user->getFullname() }}
                                @if ($post->user->isOfficial())
                                    <i class="bx bxs-badge-check text-primary" style="vertical-align: middle; margin-top: -1px;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Account is official."></i>
                                @endif
                            </span>
                        </a>
                        
                        <div class="mt-0">
                            <a href="{{ url('./profile/' . $post->user->username) }}" tabindex="-1">
                                <span class="small text-muted font-weight-normal">{{ '@'.$post->user->username }}</span>
                            </a>
                        </div>

                    </h6>

                </div>
                <span>
                    <i class="bi bi-three-dots flex-shrink-1 float-end" type="button" data-bs-toggle="dropdown"></i>
                    <ul class="dropdown-menu z-3">
                        <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                            <i class="bx bx-user-plus me-1"></i> 
                            <span class="mt-1">Follow {{ '@'.$post->user->username }}</span>
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                            <i class="bx bx-user-x me-1"></i> 
                            <span class="mt-1">Unfollow {{ '@'.$post->user->username }}</span>
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
            </div>
</div>         

            <div class="d-flex w-100 ps-3">
                <div class="w-100">
                    
                    @php
                        $content = $post->getContentWithMentionsAndHashtags();
                        $limit = 480;
                        $shortenedContent = strlen($content) > $limit ? substr($content, 0, $limit) : $content;
                    @endphp
                    <p class="fw-light prewrap" style="line-height: 1.2;">{!! $content !!}</p>

                    
                    <a href="{{ url('./profile/' . $post->user->username .'/posts/'. $post->id) }}" tabindex="-1">
                        <span class="small text-muted font-weight-normal">{{ $post->created_at->format('h:i A · M j, Y') }}</span>
                    </a>

                    <ul class="list-unstyled d-flex justify-content-between align-items-center pe-xl-5 mb-2 border-bottom border-top mt-3">
                        <li wire:click="reactToPost" class="d-flex align-items-center btn-heart {{ $reacted ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="React">
                            <div class="badge text-secondary rounded-pill me-1 p-2">
                                <i class="bx {{ $reacted ? 'bxs-heart text-danger' : 'bx-heart' }} bx-tada-hover bx-sm"></i>
                            </div>
                            <small class="text-secondary">{{ $reactionCount > 0 ? $reactionCount : '' }}</small>
                        </li>
                        <li class="d-flex align-items-center btn-chat" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Reply">
                            <div class="badge text-secondary rounded-pill me-1 p-2">
                                <i class="bx bx-chat bx-tada-hover bx-sm"></i>
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
                            <small class="text-secondary">{{ $post->views_count > 0 ? $post->formatViewsCount($post->views_count) : '1' }}</small>
                        </li>
                    </ul>

                </div>
                
        </div>

        <!-- Comments -->
        @foreach ($post->comments as $comment)
            @livewire('posts.comment-component', ['comment' => $comment], key($comment->id))
        @endforeach


        <!-- Commenting -->
        <div class="d-flex p-3 border-bottom">
            <img src="{{ asset('img/profile/' . Auth::user()->avatar) }}" style="max-height: 40px;" class="rounded-circle profile-sm" alt="Avatar" loading="lazy">
            <div class="d-flex w-100 ps-3">
                <div class="w-100">
                    <h6 class="text-body">
                        <span class="small text-muted fw-normal"> Replying to</span>

                        <a href="{{ url('./profile/' . $post->user->username) }}" tabindex="-1">
                            <span class="small text-primary font-weight-normal">{{ '@'.$post->user->username }}</span>
                        </a>
                        
                    </h6>

                    <textarea wire:model="commentContent" id="commentInput" class="form-control post mention-enabled" style="height: 40px; overflow-y: hidden;" spellcheck="false" placeholder="Type your reply"></textarea>
                    @error('commentContent') <span class="text-danger">{{ $message }}</span> @enderror

                    <div class="d-flex justify-content-between mt-2">
                        <div class="small">
                            <button type="button" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Emoji">
                                <i class="bi bi-emoji-smile"></i>
                            </button>
                        </div>
                        <button wire:click="addComment" type="button" data-enable-if="commentInput" class="btn btn-portal btn-sm visually-hidden">
                            Reply
                        </button>
                    </div>
                    
                </div>
            </div>
        </div>

</div>

