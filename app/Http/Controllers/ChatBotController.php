<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = $request->input('message');
        $client = new Client();
        
        // Retrieve the Wit.ai access token from the .env file
        $witAccessToken = env('WIT_AI_ACCESS_TOKEN');
        
        // Call the Wit.ai API
        $response = $client->post('https://api.wit.ai/message', [
            'headers' => [
                'Authorization' => 'Bearer ' . $witAccessToken,
                'Content-Type'  => 'application/json',
            ],
            'query' => [
                'q' => $message,
            ]
        ]);

        // Decode the response
        $responseBody = json_decode($response->getBody()->getContents(), true);

        // Extract the intent or response data
        $intent = $responseBody['intents'][0]['name'] ?? null;

        // Define responses based on the detected intent
        switch ($intent) {
            case 'check_grades':
                $botResponse = 'You can check your grades in the grades section of your portal.';
                break;
            case 'enrollment_status':
                $botResponse = 'Your enrollment status is visible on your dashboard under the enrollment tab.';
                break;
                default:
                $botResponse = 'I’m here to help! How can I assist you today?';
                break;
            
        }

        return response()->json([
            'message' => $botResponse,
        ]);
    }
}
