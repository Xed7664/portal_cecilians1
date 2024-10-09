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
                    {{session('success')}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        <form wire:submit="register" class="row g-3 needs-validation" novalidate>
            <div class="col-12">
                <p>An account verification link has been sent to your email address. Please check your email and click the verification link to complete the registration process.</p>
            </div>
            
            <div class="col-12 mb-0">
                <a href="{{ route('newsfeed') }}" type="button" class="btn btn-danger w-100">Skip for now</a>
            </div>

            <p class="text-center mb-0">
                <span>Didn't receive the mail?</span>
                <a href="{{ route('login') }}">
                    <span class="text-danger fw-semibold">Resend</span>
                </a>
            </p>
        </form>
    </div>
</div>
