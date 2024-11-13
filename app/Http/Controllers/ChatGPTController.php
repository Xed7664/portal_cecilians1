<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ChatGPTController extends Controller
{
    public function handle(Request $request)
    {
        $userMessage = $request->input('message');

        // Call OpenAI API with the user message
        $responseMessage = $this->getChatGPTResponse($userMessage);

        // Return JSON response
        return response()->json(['response' => $responseMessage]);
    }

    private function getChatGPTResponse($message)
    {
        $client = new Client();
        $apiKey = env('OPENAI_API_KEY');

        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo', // or 'gpt-4' for more advanced models
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant for college students.'],
                    ['role' => 'user', 'content' => $message],
                ],
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        // Return the response content
        return $data['choices'][0]['message']['content'] ?? "I'm sorry, I couldn't process that.";
    }
}
