@extends('layouts.app')

@section('title', 'Year and Section')

@section('content')
<main id="main" class="main">
    <section class="section py-5">
        <div class="container">
            <h2 class="text-center mb-2">Year & Section</h2>

            <div class="row g-4">
                @foreach ($yearLevels as $yearLevel)
                    <div class="col-12">
                        <div class="card shadow-sm year-card h-100 mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title h5 mb-0 text-white">{{ $yearLevel->name }}</h3>
                                <button class="btn btn-link section-toggle p-0" type="button" data-bs-toggle="collapse" data-bs-target="#sections-{{ $yearLevel->id }}" aria-expanded="false" aria-controls="sections-{{ $yearLevel->id }}">
                                    <i class="chevron-icon fas fa-chevron-down text-white"></i>
                                    <span class="visually-hidden">Toggle sections</span>
                                </button>
                            </div>
                            <div class="collapse" id="sections-{{ $yearLevel->id }}">
                                <div class="card-body">
                                    <h4 class="h6 text-dark mb-4">Sections:</h4>
                                    <div class="row g-3">
                                        @if (!empty($sectionsByYear[$yearLevel->name]) && count($sectionsByYear[$yearLevel->name]) > 0)
                                            @foreach ($sectionsByYear[$yearLevel->name] as $section)
                                                <div class="col-md-6">
                                                    <a href="{{ route('phead.section.students', $section->id) }}" 
                                                       class="section-card card shadow-sm text-decoration-none"
                                                       data-section-id="{{ $section->id }}">
                                                        <div class="card-body text-center">
                                                            <h5 class="h6 mb-0 text-white">{{ $section->name }}</h5>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="col-12">
                                                <p class="text-center text-muted">No section available in this year level.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</main>

<!-- Custom styles for modern card look and chevron rotation -->
<style>
    .year-card {
        border-radius: 10px;
        overflow: hidden;
    }

    .card-header {
        background-color: #605a5a;
        color: #ffffff;
        border-bottom: none;
        padding: 1rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .card-header:hover {
        background-color: #6a0d0d;
    }

    .chevron-icon {
        transition: transform 0.3s;
    }

    .section-toggle[aria-expanded="true"] .chevron-icon {
        transform: rotate(180deg);
    }

    .card-body h4 {
        margin-top: 1rem;
        color: #f8f9fa; /* Set lighter color for "Sections:" text */
    }

    .section-card {
        background-color: #605a5a;
        border-radius: 8px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .section-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        background-color: #6a0d0d;
    }

    .section-card .card-body h5 {
        color: #ffffff; /* Set text color inside section cards to white */
    }

    .section-card .card-body {
        padding: 20px;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle individual section chevron rotation
        const toggles = document.querySelectorAll('.section-toggle');
        toggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const expanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !expanded);
            });
        });
    });
</script>
@endsection
