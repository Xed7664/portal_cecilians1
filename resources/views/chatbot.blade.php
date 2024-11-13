<div class="modal fade" id="chatBotModal" tabindex="-1" aria-labelledby="chatBotModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="chatBotModalLabel">Student Support Chatbot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="chatbotContainer" class="chatbot-container">
                    <div id="messages" class="messages-container"></div>
                    <div class="input-container">
                        <input type="text" id="userInput" placeholder="Ask a question..." />
                        <button id="sendButton" onclick="sendMessage()">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .chatbot-container {
        display: flex;
        flex-direction: column;
        height: 500px;
        background-color: #f0f0f5;
        padding: 10px;
        border-radius: 5px;
        overflow: hidden;
    }
    .messages-container {
        flex: 1;
        overflow-y: auto;
        padding: 10px;
        background-color: #1c1c28;
        border-radius: 5px;
        color: #fff;
    }
    .messages-container p {
        padding: 8px 12px;
        border-radius: 20px;
        max-width: 80%;
        margin: 4px 0;
    }
    .messages-container p.user-message {
        background-color: #007bff;
        color: white;
        text-align: right;
        align-self: flex-end;
    }
    .messages-container p.bot-message {
        background-color: #343a40;
        color: white;
        align-self: flex-start;
    }
    .input-container {
        display: flex;
        margin-top: 10px;
    }
    #userInput {
        flex: 1;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 20px;
    }
    button {
        margin-left: 10px;
        padding: 10px 20px;
        border: none;
        border-radius: 20px;
        background-color: #007bff;
        color: #fff;
        font-weight: bold;
    }
</style>

<script defer>
    async function sendMessage() {
        const userMessage = document.getElementById("userInput").value.trim();

        if (!userMessage) return;

        // Display user's message
        const messagesContainer = document.getElementById("messages");
        messagesContainer.innerHTML += `<p class="user-message"><b>You:</b> ${userMessage}</p>`;
        messagesContainer.scrollTop = messagesContainer.scrollHeight;

        // Clear the input field
        document.getElementById("userInput").value = "";

        try {
    const response = await fetch("/chatbot/message", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ message: userMessage })
    });

    if (!response.ok) throw new Error('Network response was not ok');
    const data = await response.json();
    document.getElementById("messages").innerHTML += `<p><b>Bot:</b> ${data.message}</p>`;
} catch (error) {
    console.error('Error:', error);
    document.getElementById("messages").innerHTML += `<p><b>Bot:</b> Sorry, there was an error processing your request.</p>`;
}


        // Display bot's response
        const data = await response.json();
        messagesContainer.innerHTML += `<p class="bot-message"><b>Bot:</b> ${data.message}</p>`;
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
</script>
