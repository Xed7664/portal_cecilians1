
<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title text-uppercase pb-0 fs-5">
            <span>Trends for you</span>
            <i class="bx bx-info-circle float-end small bx-xm text-portal" data-bs-toggle="tooltip" data-bs-title="This trends are generated monthly."></i>
        </h5>

        <ol class="list-group list-group-numbered list-group-flush" style="font-size: 0.9rem !important">
            @foreach($trendingHashtags as $hashtag)
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <a href="{{ route('hashtag', Str::lower($hashtag['tag'])) }}">#{{ $hashtag->tag }}</a>
                    </div>
                    <span class="badge bg-primary rounded-pill">
                        {{ $hashtag->count }} {{ $hashtag->count > 1 ? 'posts' : 'post' }}
                    </span>
                </li>
            @endforeach
        </ol>
    </div>
</div>
