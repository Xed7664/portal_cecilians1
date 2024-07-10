<div class="card-body pt-4 border border-danger border-opacity-25 border-25 border-top-0 bg-body-tertiary">
    <div id="response">
        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <form wire:submit.prevent="login" class="row g-3 needs-validation" novalidate>
        
        <div class="col-12">
            <label class="form-label">Email or Username</label>
            <input wire:model="username" type="text" class="form-control" required>
            @error('login') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <div class="d-flex justify-content-between">
                <label class="form-label">Password</label>
                <a href="reset-password" tabindex="-1">
                    <small class="text-danger fw-semibold">Forgot Password?</small>
                </a>
            </div>
            <div class="input-group">
                <input wire:model="password" type="password" class="form-control toggle-password" required>
                <button class="btn btn-outline-secondary toggle-password-button" type="button">
                    <i class="bi bi-eye"></i>
                </button>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="col-12">
            <div class="form-check">
                <input wire:model="remember" class="form-check-input" type="checkbox" id="select-personal" data-value="rememberMe" >
                <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>
        </div>

        <div class="col-12 mb-2 mt-4">
            <button type="submit" class="btn btn-danger w-100">Sign In</button>
        </div>

        <hr class="border border-50 opacity-75 mb-0">

        <p class="text-center">
            <span>New on our platform?</span>
            <a href="{{ route('registration') }}" tabindex="-1">
                <span class="text-danger fw-semibold">Create an account</span>
            </a>
        </p>

        <div class="col-12 mt-1">
            <p class="small mb-0 text-center">- OR -</p>
        </div>

        <div class="d-flex justify-content-center column-gap-1">
            <a href="{{ route('login.google') }}" type="button" class="btn btn-outline-danger w-100 fw-semibold">
                <i class="bx bxl-google"></i> Sign in with Google
            </a>
        </div>
    </form>
</div>
