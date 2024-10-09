import "./bootstrap";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    wsHost:
        import.meta.env.VITE_PUSHER_HOST ||
        `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT || 443,
    wssPort: import.meta.env.VITE_PUSHER_PORT || 443,
    enabledTransports: ["ws", "wss"],
});
// Listen to the private 'grades' channel
Echo.private('grades.' + subjectEnrolledId)
    .listen('GradeUpdated', (e) => {
        console.log('Grade updated:', e);
    });

