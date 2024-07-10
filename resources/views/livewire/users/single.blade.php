<div wire:key="{{ $student->id }}" class="col-xl-4 col-lg-4 col-md-4 mt-0">
    <div class="card mb-3">
        <div class="card-body p-0">
            <div class="d-flex flex-wrap p-2">
                <div class="avatar me-2">
                    <img src="https://cecilian-portal.online/img/profile/default-profile.png" alt="Avatar" class="rounded-circle profile-sm">
                </div>
                <a href="{{ $isRegistered ? route('profile.show', $registeredUserDetails->username) : '' }}" tabindex="-1" class="ms-1 flex-grow-1">
                    <div class="mb-0 fs-6 fw-medium text-portal">
                        {{ Str::limit($student->FullName, 18) }}


                    </div>
                    <small class="text-muted fw-light">{{ $isRegistered ? '@'.$registeredUserDetails->username : 'Not registered' }}</small>
                </a>

                <div class="float-end d-flex align-items-center">

                    @if($isRegistered)
                        @if(auth()->user()->id != $registeredUserDetails->id)
                            <button wire:click="followUser()" type="button" tabindex="-1" class="btn btn-outline-primary btn-sm" wire:loading.attr="disabled">
                            @if($isFollowing)
                                Unfollow
                            @else
                                Follow
                            @endif
                            </button>
                        @else
                            <a type="button" href="{{ $isRegistered ? route('profile.show', $registeredUserDetails->username) : '' }}" tabindex="-1" class="btn btn-outline-primary btn-sm" wire:loading.attr="disabled">
                                My Profile
                            </a>
                        @endif

                    @endif

                </div>
            </div>
        </div>
    </div>
</div>