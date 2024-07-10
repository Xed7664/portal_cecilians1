<div class="card">
    <div class="card-body">
        <h5 class="card-title">Change Password</h5>

        <div class="tab-content pt-2" id="myTabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="home-tab">
            
            <form class="row">

            <div class="mb-3 col-md-6">
                    <label class="form-label">Current Password</label>
                    <input wire:model="password" type="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @else
                        <div class="invalid-feedback">Please enter your current password</div>
                    @enderror
                </div>

                <div class="mb-3 col-md-6 w-100">
                </div>
                

                <div class="mb-3 col-md-6">
                    <label class="form-label">New Password</label>
                    <input wire:model="newPassword" type="password" class="form-control @error('newPassword') is-invalid @enderror">
                    @error('newPassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @else
                        <div class="invalid-feedback">Please enter your new password</div>
                    @enderror
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">Confirm New Password</label>
                    <input wire:model="confirmNewPassword" type="password" class="form-control @error('confirmNewPassword') is-invalid @enderror">
                    @error('confirmNewPassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @else
                        <div class="invalid-feedback">Please enter your confirm new password</div>
                    @enderror
                </div>

                <div class="col-12 mt-3 mb-3">
                    <h6>Password Requirements:</h6>
                    <ul class="ps-3 mb-0">
                        <li class="mb-1 text-muted">At least 8 characters</li>
                        <li class="mb-1 text-muted">At least one uppercase letter</li>
                        <li class="mb-1 text-muted">At least one lowercase letter</li>
                        <li class="mb-1 text-muted">At least one number</li>
                        <li class="text-muted">At least one special character</li>
                    </ul>
                </div>

                <div class="text-start mt-2">
                    <button type="button" class="btn btn-portal" wire:click="changePassword">Save Changes</button>
                </div>


            </form>

        </div>
        
        </div>

    </div>
</div>