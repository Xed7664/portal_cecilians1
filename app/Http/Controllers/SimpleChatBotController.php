<?php
// SimpleChatBotController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SimpleChatBotController extends Controller
{
    public function handle(Request $request)
    {
        $userMessage = $request->input('message');
        $botResponse = $this->getBotResponse($userMessage);

        // Return JSON response
        return response()->json(['response' => $botResponse]);
    }

    private function getBotResponse($message)
    {
        $message = strtolower($message);

        // Define simple responses based on keywords
        if (strpos($message, 'hello') !== false) {
            return 'Hello! How can I help you today?';
        } elseif (strpos($message, 'help') !== false) {
            return 'Sure! What do you need help with?';
        } elseif (strpos($message, 'goodbye') !== false) {
            return 'Goodbye! Have a great day!';
        } else {
            return "I'm sorry, I didn't understand that. Could you please rephrase?";
        }
    }
}
