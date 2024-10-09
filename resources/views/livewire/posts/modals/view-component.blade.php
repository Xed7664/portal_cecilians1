<div wire:ignore.self class="modal fade" id="viewModal" tabindex="-1" wire:loading>
    <div class="modal-dialog {{ optional($post)->user_id == optional(Auth::user())->id ? 'modal-lg' : 'modal-sm' ?? 'modal-sm' }} modal-dialog-centered" data-bs-backdrop="false">
        <div class="modal-content">
            <div class="modal-body">
                <button wire:click="closeModal()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="my-3">
                    @if($showModal)

                        @if($post->user_id == Auth::user()->id)
                            <h4 class="mb-3 ms-2 fw-bold">Post Analytics</h4>
                            <div class="row gy-3 border rounded-4 m-2 pb-2">
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded-pill bg-label-info me-3 p-2">
                                            <i class="bx bxs-heart bx-sm"></i>
                                        </div>
                                        <div class="card-info">
                                            <h5 class="mb-0">{{ $reactionCount > 0 ? $reactionCount : '0' }}</h5>
                                            <small>Reactions</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded-pill bg-label-info me-3 p-2 btn-heart-hover">
                                            <i class="bx bxs-chat bx-sm"></i>
                                        </div>
                                        <div class="card-info">
                                            <h5 class="mb-0 ">{{ $replyCount > 0 ? $replyCount : '0' }}</h5>
                                            <small>Replies</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded-pill bg-label-danger me-3 p-2">
                                            <i class="bx bx-repost bx-sm"></i>
                                        </div>
                                        <div class="card-info">
                                            <h5 class="mb-0">{{ $repostCount > 0 ? $repostCount : '0' }}</h5>
                                            <small>Repost</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded-pill bg-label-success me-3 p-2">
                                            <i class="bx bx-bar-chart bx-sm"></i>
                                        </div>
                                        <div class="card-info">
                                            <h5 class="mb-0">{{ $viewsCount > 0 ? $viewsCount : '0' }}</h5>
                                            <small>Views</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                        <div class="px-3 px-sm-3 d-flex align-items-center flex-column">
                            <h4 class="mb-2 fw-bold">Views</h4>
                            <p class="fw-normal text-center">Times this post was seen.</p>

                            <button wire:click="closeModal()" type="button" class="w-50 btn btn-portal mt-2" data-bs-dismiss="modal">Dismiss</button>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>