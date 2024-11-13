<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot Interface</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }
        #app {
            max-width: 500px;
            width: 100%;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        #chatHeader {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            text-align: center;
            font-weight: bold;
        }
        #chatOutput {
            max-height: 400px;
            overflow-y: auto;
            padding: 15px;
            border-bottom: 1px solid #eaeaea;
        }
        #chatOutput p {
            margin: 5px 0;
        }
        #chatOutput p strong {
            display: block;
            font-weight: bold;
        }
        .user-message {
            text-align: right;
            color: #007bff;
        }
        .bot-message {
            text-align: left;
            color: #333;
        }
        #chatForm {
            display: flex;
            padding: 10px;
        }
        #userInput {
            flex-grow: 1;
            padding: 10px;
            border-radius: 20px;
            border: 1px solid #ddd;
            outline: none;
            margin-right: 10px;
            box-shadow: none;
            font-size: 14px;
        }
        #sendButton {
            padding: 8px 20px;
            border-radius: 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        #sendButton:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div id="app">
        <div id="chatHeader">Chatbot</div>
        <div id="chatOutput"></div>
        <form id="chatForm">
            @csrf
            <input type="text" id="userInput" placeholder="Type a message..." required>
            <button type="submit" id="sendButton">Send</button>
        </form>
    </div>

    <script>
        document.getElementById('chatForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const userInput = document.getElementById('userInput');
            const message = userInput.value.trim();

            if (!message) return;

            fetch('{{ route('chatgpt.handle') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('chatOutput').innerHTML += `
                    <p class="user-message"><strong>You:</strong> ${message}</p>
                    <p class="bot-message"><strong>Bot:</strong> ${data.response}</p>
                `;
                userInput.value = '';
                document.getElementById('chatOutput').scrollTop = document.getElementById('chatOutput').scrollHeight;
            })
            .catch(error => {
                document.getElementById('chatOutput').innerHTML += `<p>Error: ${error.message}</p>`;
            });
        });
    </script>
</body>
</html>
