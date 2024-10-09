<div wire:ignore.self class="modal fade" id="repostModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen-sm-down modal-dialog-centered" data-bs-backdrop="false">
        <div class="modal-content">
            <div class="modal-body">
                <div class="float-end d-block d-sm-none">
                    <button type="button" class="btn btn-primary  btn-sm" data-bs-dismiss="modal" wire:click="repost" @if(optional($post)->status != 'publish') disabled @endif>Post</button>
                </div>
                <button wire:click="closeModal()" type="button" class="btn-close d-none d-sm-block" data-bs-dismiss="modal" aria-label="Close"></button>
                <button wire:click="closeModal()" type="button" class="btn btn-sm d-block d-sm-none" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bx bx-arrow-back bx-sm"></i>
                </button>
                <div class="mt-2">
                    @if($showModal)
                        <div class="d-flex">
                            <img src="{{ asset('img/profile/' . Auth::user()->avatar) }}" style="max-height: 40px;" class="rounded-circle profile-sm me-2" alt="Avatar" loading="lazy">
                            <div class="d-flex w-100 ps-3">
                                <div class="w-100">
                                    <textarea wire:model="content" class="form-control post border-0" style="height: 40px; overflow-y: hidden;" spellcheck="false" placeholder="What's on your mind?"></textarea>
                                    <div class="border rounded-3 mt-3 p-2">
                                        <div class="d-flex align-items-center">
                                            @if(optional($post)->status === 'publish' || optional($post)->status != null)
                                                <img src="{{ asset('img/profile/' . $post->user->avatar) }}" style="max-height: 28px;" class="rounded-circle profile-xs" alt="Avatar" loading="lazy">
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

                                            @if(optional($post)->status === 'publish' || optional($post)->status != null)
                                                @php
                                                    $content = $post->getContentWithMentionsAndHashtags();
                                                    $limit = 480;
                                                    $shortenedContent = strlen($content) > $limit ? substr($content, 0, $limit) : $content;
                                                @endphp
                                                <p class="fw-light prewrap small mb-0 mt-3" style="line-height: 1.2;">{!! $content !!}</p>
                                            @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer p-1 d-sm-block d-none d-md-block d-lg-block d-lg-block">
                <button type="button" class="btn btn-primary  btn-sm float-end" data-bs-dismiss="modal" wire:click="repost" @if(optional($post)->status != 'publish') disabled @endif>Post</button>
                <button type="button" class="btn btn-secondary btn-sm float-end" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
        
    </div>
</div>