<html>
<head>
    <title>Chatbot</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="chat-container">
        <div id="chat-box"></div>
        <input type="text" id="chat-input" placeholder="Type your message..." />
        <button onclick="sendMessage()">Send</button>
    </div>

    <script>
    async function sendMessage() {
        const userInput = document.getElementById("userInput");
        const userMessage = userInput.value.trim();

        if (!userMessage) return;

        const messagesContainer = document.getElementById("messages");
        messagesContainer.innerHTML += `<p class="user-message"><b>You:</b> ${userMessage}</p>`;
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
        userInput.value = "";

        try {
            const response = await fetch("/chatbot/message", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ message: userMessage })
            });

            if (!response.ok) {
                console.error("Response error:", response.statusText);
                messagesContainer.innerHTML += `<p class="bot-message"><b>Bot:</b> Sorry, something went wrong.</p>`;
                return;
            }

            const data = await response.json();
            console.log("Bot response:", data);

            messagesContainer.innerHTML += `<p class="bot-message"><b>Bot:</b> ${data.message}</p>`;
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

        } catch (error) {
            console.error("Fetch error:", error);
            messagesContainer.innerHTML += `<p class="bot-message"><b>Bot:</b> Sorry, unable to reach the server.</p>`;
        }
    }
</script>


</body>
</html>
