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
            <label class="form-label" style="position:absolute;left:8px">Email or Username</label>
            <input wire:model="username" type="text" class="form-control" style="margin-top:30px" required>
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
                <input wire:model="password" type="password" class="form-control toggle-password" id="password" required>
                <button class="btn btn-outline-secondary toggle-password-button" type="button" onclick="togglePasswordVisibility()">
                    <i class="bi bi-eye" id="toggle-icon"></i>
                </button>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="col-12">
            <div class="form-check">
                <input wire:model="remember" class="form-check-input" type="checkbox" id="select-personal" data-value="rememberMe" style="accent-color:red !important">
                <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>
        </div>

        <div class="col-12 mb-2 mt-4">
            <button type="submit" class="btn btn-danger w-100">Sign In</button>
        </div>

        <hr class="border border-50 opacity-75 mb-0">

        <div class="col-12 mt-1">
            <p class="small mb-0 text-center" style="position: static !important">- OR -</p>
        </div>

        <div class="d-flex justify-content-center column-gap-1">
            <a href="{{ route('login.google') }}" type="button" class="btn btn-outline-danger w-100 fw-semibold">
                <i class="bx bxl-google"></i> Sign in with Google
            </a>

            <a href="#" onclick="showRegCon()" type="button" class="btn btn-outline-light w-100 fw-semibold sign-up">
                Sign Up
            </a>
        </div>
    </form>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.getElementById('toggle-icon');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    }
</script>
