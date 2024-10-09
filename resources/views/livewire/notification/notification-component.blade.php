<ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages"
    style="max-height: 500px; overflow-y: auto; width: 350px;"
    @if (!$allContentLoaded)
        wire:scroll="loadMore()"
    @endif
>    
    <div class="container py-2">
        <div class="d-flex justify-content-between ">
            <div class="h5 fw-semibold">
                Notifications
            </div>
            <div class="">
                <span class="badge rounded-pill bg-primary p-2 ms-2 mark-all-read" type="button">Mark all as read</span>
            </div>
        </div>
    </div>

    <li>
        <hr class="dropdown-divider">
    </li>
    @foreach ($notifications as $notification)
        <li class="message-item notification-item">
            <a wire:click="markAsRead({{ $notification->id }})" class="d-flex align-content-between flex-row" href="{{ $notification->notificationUrl() }}">
                <img src="{{ asset('img/profile/'.$notification->sender->avatar) ?? asset('img/profile/default-profile.png') }}" alt="" class="rounded-circle profile-sm">
                <div class="flex-grow-1">
                    @php
                        // Call parseContent method with the necessary data
                        $parsedContent = $notification->portalNotification->parseContent([
                            'sender_id' => $notification->sender_id,
                        ]);
                    @endphp
                    <p>{!! $parsedContent !!}</p>
                    <p>{{ $notification->created_at->diffForHumans() }}</p>
                </div>

                @if ($notification->read_at === null)
                    <div class="float-end">
                        <i class="bi bi-dot fs-3"></i>
                    </div>
                @endif

            </a>
        </li>
        <li>
            <hr class="dropdown-divider">
        </li>
    @endforeach


    @if ($allContentLoaded)
        <li class="dropdown-footer">
            <span class="fw-light fst-italic">We're all caught up! No more notifications to display.</span>
        </li>
    @endif
</ul>