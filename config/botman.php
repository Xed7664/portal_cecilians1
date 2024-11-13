<?php

return [
    'conversation_cache_time' => 30,  // Cache time for conversations, in minutes
    'user_cache_time' => 30,  // Cache time for user info, in minutes

    'web' => [
        'matchingData' => [
            'driver' => 'web',  // Ensures BotMan uses the WebDriver for web interactions
        ],
    ],
];
