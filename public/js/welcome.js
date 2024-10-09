// Function to start the confetti animation and play the sound
function startConfettiAndSound() {
    // Start the confetti animation (from your code)
    confetti.start();

    // Play the sound
    const tadaAudio = new Audio('./../../assets/snd/tada.mp3');
    tadaAudio.play();

    // Stop the confetti after 5 seconds
    setTimeout(function () {
        confetti.stop();
    }, 5000); // 5000 milliseconds = 5 seconds
}

// Wait for the DOM to be ready
$(document).ready(function () {
    // Run the confetti and sound on page load
    startConfettiAndSound();

    // Select the button element by its ID
    const buttonElement = document.getElementById("confettiButton");

    // Add a click event listener to the button element
    buttonElement.addEventListener("click", startConfettiAndSound);
});
