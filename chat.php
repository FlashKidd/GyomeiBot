<?php

function callChatGPT($apiKey, $prompt) {
    $url = 'https://api.openai.com/v1/chat/completions';

    $data = [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => 'You are a helpful assistant.'],
            ['role' => 'user', 'content' => $prompt]
        ],
        'max_tokens' => 150,
        'temperature' => 0.7
    ];

    $headers = [
        'Content-Type: application/json',
        'Authorization: ' . 'Bearer ' . $apiKey
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    } else {
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $responseData = json_decode($response, true);
        
        if ($httpcode == 429) {
            echo "HTTP Error 429: Quota exceeded. Please check your plan and billing details.\n";
            // Optionally, implement a delay and retry logic here
            // sleep(60); // Wait for 60 seconds before retrying
            return null;
        } elseif ($httpcode >= 400) {
            echo "HTTP Error: " . $httpcode . "\n";
            echo "Response: " . $response . "\n";
        } else {
            if (isset($responseData['choices'][0]['message']['content'])) {
                return trim($responseData['choices'][0]['message']['content']);
            } else {
                echo "Error: Invalid response format\n";
                echo "Response: " . $response . "\n";
            }
        }
    }

    curl_close($ch);
    return null;
}

// Replace YOUR_API_KEY with your actual OpenAI API key
$apiKey = 'xx';
$prompt = "Hello, how are you?";

// Simple rate-limiting
$rateLimit = 60 / 3; // 3 requests per minute
$lastRequestTime = 0;

$response = null;
while ($response === null) {
    $currentTime = time();
    if (($currentTime - $lastRequestTime) < $rateLimit) {
        sleep($rateLimit - ($currentTime - $lastRequestTime));
    }
    $response = callChatGPT($apiKey, $prompt);
    $lastRequestTime = time();
}

if ($response) {
    echo "ChatGPT response: " . $response;
} else {
    echo "Failed to get a response from ChatGPT.";
}

?>
