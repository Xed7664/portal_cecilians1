@extends('layouts.app')

@section('title', 'Login Analytics')

@section('content')
<main id="main" class="main">
    <section class="newsfeed-container">
        <div class="row">

            <!-- Main Content -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body m-3">
                        @livewire('admin.analytics.logins.line-chart')
                    </div>
                </div>
            </div>
            <!-- End Main Content -->


            
            </div>
    </section>
</main>

@livewireChartsScripts

@endsection
