<div class="card-body pt-4 border border-danger border-opacity-25 border-25 border-top-0 bg-body-tertiary">
    <div>
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
                    <span class="input-group-text" style="font-size:14px">SCC-</span>
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
                <button id="submitBtn" type="submit" class="btn btn-danger w-100" style="z-index:20">Sign Up</button>
            </div>

            <hr class="border border-50 opacity-50 mb-0">

            <p class="text-center mb-0 signinbtn">
                <span style="color:rgb(90,90,90);font-size:18px">Already have an account?</span>
                <a href="#" onclick="showLoginCon()">
                    <span class="text-danger fw-semibold" style="font-size:18px"> Sign in</span>
                </a>
            </p>
        </form>
    </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Function to check username criteria
    function checkUsernameCriteria(username) {
        const usernameRegex = /^[A-Za-z0-9_.]+$/;
        return username.length >= 3 && username.length <= 30 && usernameRegex.test(username);
    }

    // Attach event handlers to the username input field
    const usernameInput = $('.username');
    const feedbackContainer = usernameInput.closest('.col-12');
    const feedbackElement = feedbackContainer.find('.invalid-feedback');

    usernameInput.on('input', function() {
        const username = $(this).val();
        const isValid = checkUsernameCriteria(username);

        if (isValid) {
            feedbackElement.hide();
            $(this).removeClass('is-invalid');
            $(this).addClass('is-valid');
            $('#submitBtn').prop('disabled', false); // Enable submit button
        } else {
            feedbackElement.show();
            $(this).removeClass('is-valid');
            $(this).addClass('is-invalid');
            $('#submitBtn').prop('disabled', true); // Disable submit button
        }
    });

    // Function to check password criteria
    function checkPasswordCriteria(password) {
        const criteria = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /\d/.test(password),
            special: /[!@#$%^&*()_+\-=[\]{};:'"\\|,.<>/?]/.test(password)
        };

        return criteria;
    }

    // Attach event handlers to the password input field
    const passwordInput = $('.toggle-password');
    const criteriaContainer = $('.password-criteria');
    
    passwordInput.focus(function() {
        criteriaContainer.removeClass('visually-hidden');
    });

    passwordInput.blur(function() {
        criteriaContainer.addClass('visually-hidden');
    });

    passwordInput.on('input', function() {
        const password = $(this).val();
        const criteria = checkPasswordCriteria(password);

        // Show/hide criteria and update check marks
        const criteriaItems = criteriaContainer.find('.password-criteria-item');
        criteriaItems.each(function(index, item) {
            const checkElement = $(item).find('.criteria-check i');
            const criteriaText = $(item).find('.criteria-text');
            const isMet = criteria[Object.keys(criteria)[index]];
            const iconClass = isMet ? 'bi-check-circle-fill text-success' : 'bi-check-circle text-secondary';

            checkElement.removeClass().addClass('bi ' + iconClass);
            criteriaText.removeClass('text-success text-dark').addClass(isMet ? 'text-success' : 'text-dark');
        });

        // Password validation
        const isValid = Object.values(criteria).every(Boolean);
        const feedbackContainer = $(this).closest('.col-12');
        const feedbackElement = feedbackContainer.find('.invalid-feedback');
        const validFeedbackElement = feedbackContainer.find('.valid-feedback');
        
        if (isValid) {
            feedbackElement.hide();
            validFeedbackElement.show();
            $(this).removeClass('is-invalid');
            $(this).addClass('is-valid');
            $('#submitBtn').prop('disabled', false); // Enable submit button
        } else {
            feedbackElement.show();
            validFeedbackElement.hide();
            $(this).removeClass('is-valid');
            $(this).addClass('is-invalid');
            $('#submitBtn').prop('disabled', true); // Disable submit button
        }
    });
});


</script>