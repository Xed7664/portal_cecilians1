<div class="card">
    <div class="card-body">
        <h5 class="card-title text-uppercase pb-0 fs-5">
            <span>Who to follow</span>
            <i wire:click="refresh()" wire:loading.class="bx-spin" class="bx bx-refresh float-end small bx-xm text-portal" data-bs-toggle="tooltip" data-bs-title="Refresh"></i>
        </h5>
        <div class="list-group list-group-flush mt-3">

            @foreach($usersToFollow as $user)
                <div wire:key="{{ $user->id }}" class="list-group-item ps-0 pb-0">
                    <div class="d-flex flex-wrap mb-1">
                        <a href="{{ route('profile.show', $user->username) }}" tabindex="-1" class="avatar me-2">
                            <img src="{{ asset('img/profile/'.$user->avatar) }}" alt="Avatar" class="rounded-circle profile-sm">
                        </a>
                        <a href="{{ route('profile.show', $user->username) }}" tabindex="-1" class="ms-1 flex-grow-1">
                            <div class="mb-0 fs-6 fw-medium text-portal">
                                {{ Str::limit($user->getFullname(), 18) }}

                                @if($user->isOfficial())
                                    <i class="bx bxs-badge-check text-primary" style="vertical-align: middle; margin-top: -1px;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Account is official."></i>
                                @endif


                            </div>
                            <small class="text-muted fw-light">{{ '@' . $user->username }}</small>
                        </a>
                        <div class="float-end">

                        <button wire:click="followUser({{ $user }})" type="button" tabindex="-1" class="btn btn-outline-primary btn-sm" wire:loading.attr="disabled">
                            @if($user->followed)
                                Unfollow
                            @else
                                Follow
                            @endif
                        </button>


                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>
