<div class="card">
    <div class="card-body">
        <h5 class="card-title">Account Details</h5>

        <div class="tab-content pt-2" id="myTabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="home-tab">
            <div class="d-flex align-items-start align-items-sm-center gap-4">
                @if($avatarReset == true)
                    <img id="profile-pic" src="{{ asset('img/profile/default-profile.png') }}" alt="user-avatar" class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" style="width: 100px; height: 100px; object-fit: cover;">
                @else
                    <img id="profile-pic" src="{{ asset('img/profile/' . Auth::user()->avatar) }}" alt="user-avatar" class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" style="width: 100px; height: 100px; object-fit: cover;">
                @endif
                <div class="button-wrapper">
                    <label for="upload" class="btn btn-portal me-2 mb-3" tabindex="0">
                        <span class="d-none d-sm-block small">Upload new photo</span>
                        <i class="ri ri-image-add-line d-block d-sm-none"></i>
                        
                        <input wire:model="avatar" type="file" id="upload" class="account-file-input @error('avatar') is-invalid @enderror" hidden="" accept="image/png, image/jpeg">
                    </label>
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">Please enter your Bio</div>
                        @enderror
                    <button type="button" class="btn bg-light-subtle mb-3" wire:click="resetAvatar">
                        <i class="bx bx-rotate-left d-block d-sm-none my-25"></i>
                        <span class="d-none d-sm-block small">Reset</span>
                    </button>

                    <div class="text-muted small">Allowed JPG, GIF or PNG. Max size of 800K</div>
                </div>
            </div>

            <hr class="mt-4 my-0">

            <form class="row mt-4">

            
                <div class="mb-3 col-md-12">
                    <label class="form-label">Bio</label>
                    <textarea wire:model="bio" type="text" class="form-control @error('bio') is-invalid @enderror"></textarea>
                    @error('bio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @else
                        <div class="invalid-feedback">Please enter your Bio</div>
                    @enderror
                </div>

                <div class="text-start mt-2">
                    <button type="button" class="btn btn-portal" wire:click="submitForm">Save Changes</button>
                </div>

                <hr class="mt-4 my-0">

                <h5 class="card-title ms-3">Registrar's Data</h5>

                <div class="mb-3 col-md-6">
                    <label class="form-label">Full Name</label>
                    <input wire:model="fullname" type="text" class="form-control" disabled>
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">Email</label>
                    <input wire:model="email" type="text" class="form-control" disabled>
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">Birthday</label>
                    <input wire:model="birthday" type="date" class="form-control" disabled>
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">Birth Place</label>
                    <input wire:model="birthplace" type="text" class="form-control" disabled>
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">Religion</label>
                    <input wire:model="religion" type="text" class="form-control" disabled>
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">Citizenship</label>
                    <input wire:model="citizenship" type="text" class="form-control" disabled>
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">Address</label>
                    <input wire:model="address" type="text" class="form-control" disabled>
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">Type</label>
                    <select wire:model="type" class="form-select" disabled>
                      <option value="student" selected="">Student</option>
                      <option value="employee">Employee</option>
                    </select>
                </div>


            </form>

        </div>
        
        </div>

    </div>
</div>