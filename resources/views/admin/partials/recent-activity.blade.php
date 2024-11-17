@if(count($recentActivities) > 0)
    @foreach($recentActivities as $activity)
        <div class="activity-item">
            <div class="activite-label">{{ $activity['time'] }}</div>
            <div class="activity-content">
                {{ $activity['message'] }}
            </div>
        </div>
    @endforeach
@else
    <p>No recent activities.</p>
@endif

<style>
.activity-item {
    position: relative; /* Ensures the content within each activity item is positioned relative */
    padding: 1rem 0 1rem 1.5rem; /* Creates consistent spacing and an offset for the left border */
    border-left: 2px solid #e9ecef; /* Creates a timeline effect */
    margin-left: 1rem; /* Keeps alignment consistent */
   
}

.activity-item:not(:last-child) {
    margin-bottom: 1.5rem; /* Adds consistent spacing between activity items */
}

.activite-label {
    position: relative;
    font-size: 0.75rem;
    color: #888;
    margin-bottom: 5px; /* Adds space below the time */
}

.activity-content {
    position: relative;
    font-size: 0.875rem;
    word-break: break-word; /* Prevents long text from disrupting layout */
}

/* Optional: Styling for the timeline dot */
.activity-item:before {
    content: '';
    position: absolute;
    top: 1rem; /* Adjust to vertically center the dot */
    left: -8px; /* Adjust based on the size of the dot */
    width: 10px;
    height: 10px;
    background-color: #007bff; /* Blue timeline dot */
    border-radius: 50%; /* Makes it circular */
}

/* Ensures no overlapping or fixed positions */
body {
    overflow-y: scroll; /* Enables scrolling if the list is long */
}
</style>
