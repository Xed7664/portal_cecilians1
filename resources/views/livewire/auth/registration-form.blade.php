<div class="card-body pt-4 border border-danger border-opacity-25 border-25 border-top-0 bg-body-tertiary registration-form">
        <div id="response">
            @if(session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{session('success')}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        <form wire:submit="register" class="row g-3 needs-validation" novalidate>
            <div class="col-12">
                <label class="form-label">School ID</label>
                <div class="input-group">
                    <span class="input-group-text">SCC-</span>
                    <input wire:model="school_id" type="text" class="form-control @error('school_id') is-invalid @enderror" name="school_id" value="{{ old('school_id') }}" required>
                    @error('school_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @else
                        <div class="invalid-feedback">Please enter your School ID.</div>
                    @enderror
                </div>
            </div>


            <div class="col-12">
                <label class="form-label">Birthdate</label>
                <input wire:model="birthdate" type="date" class="form-control @error('birthdate') is-invalid @enderror" required>
                @error('birthdate') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label class="form-label">Username</label>
                <input wire:model="username" type="text" class="form-control username @error('username') is-invalid @enderror" required>
                @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label class="form-label">Email</label>
                <input wire:model="email" type="email" class="form-control @error('email') is-invalid @enderror" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input wire:model="password" type="password" class="form-control toggle-password @error('password') is-invalid @enderror" required>
                    <button class="btn btn-outline-secondary toggle-password-button" type="button">
                        <i class="bi bi-eye"></i>
                    </button>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="password-criteria visually-hidden py-3">
                    <div class="password-criteria-item">
                        <span class="criteria-check">
                        <i class="bi bi-check-circle text-secondary"></i>
                        </span>
                        <span class="criteria-text">At least 8 characters</span>
                    </div>
                    <div class="password-criteria-item">
                        <span class="criteria-check">
                        <i class="bi bi bi-check-circle text-secondary"></i>
                        </span>
                        <span class="criteria-text">At least one uppercase letter</span>
                    </div>
                    <div class="password-criteria-item">
                        <span class="criteria-check">
                        <i class="bi bi-check-circle text-secondary"></i>
                        </span>
                        <span class="criteria-text">At least one lowercase letter</span>
                    </div>
                    <div class="password-criteria-item">
                        <span class="criteria-check">
                        <i class="bi bi-check-circle text-secondary"></i>
                        </span>
                        <span class="criteria-text">At least one number</span>
                    </div>
                    <div class="password-criteria-item">
                        <span class="criteria-check">
                            <i class="bi bi-check-circle text-secondary"></i>
                        </span>
                        <span class="criteria-text">At least one special character</span>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="form-check">
                    <input wire:model="terms_agreed" type="checkbox" class="form-check-input @error('terms_agreed') is-invalid @enderror" required>
                    <label class="form-check-label">I agree to <a href="#" class="text-danger fw-semibold">privacy policy & terms</a></label>
                </div>
                @error('terms_agreed') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 mb-2">
                <button id="submitBtn" type="submit" class="btn btn-danger w-100">Sign Up</button>
            </div>

            <hr class="border border-50 opacity-50 mb-0">

            <p class="text-center mb-0">
                <span style="color:rgb(90,90,90)">Already have an account?</span>
                <a href="{{ route('login') }}">
                    <span class="text-danger fw-semibold">Login instead</span>
                </a>
            </p>
        </form>
    </div>
</div>
