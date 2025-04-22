<?php
header('Content-Type: application/json');

// Sanitize input
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['suggestion' => 'Invalid JSON input.']);
    exit;
}

$reason = filter_var($data['reason'] ?? '', FILTER_SANITIZE_STRING);
$details = filter_var($data['details'] ?? '', FILTER_SANITIZE_STRING);
$frequency = filter_var($data['frequency'] ?? '', FILTER_SANITIZE_STRING);
$goal = filter_var($data['goal'] ?? '', FILTER_SANITIZE_STRING);

// Build the prompt
$prompt = "User missed the gym for the following reasons:\n";
$prompt .= "Reason: $reason\n";
$prompt .= "Details: $details\n";
$prompt .= "Workout Frequency: $frequency\n";
$prompt .= "Fitness Goal: $goal\n\n";
$prompt .= "Please provide AI-powered motivational and practical suggestions, including changes to the workout plan, habit building tips, and ways to stay consistent.";

// OpenRouter API call
$apiKey = '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';

$payload = [
    'model' => 'openai/gpt-3.5-turbo', // ✅ Updated to ChatGPT 3.5 model
    'messages' => [
        [
            'role' => 'user',
            'content' => $prompt
        ]
    ],
    'temperature' => 0.7,
    'max_tokens' => 500
];

$ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $apiKey,
    'Content-Type: application/json',
    // Optional headers
    // 'HTTP-Referer: https://yourdomain.com',
    // 'X-Title: AI Gym Helper'
]);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

// Handle cURL errors
if ($error) {
    echo json_encode(['suggestion' => "Error connecting to AI service: $error"]);
    exit;
}

// Handle invalid JSON response
$responseData = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['suggestion' => "Invalid response from AI: " . json_last_error_msg()]);
    exit;
}

// Check for valid AI response
if (isset($responseData['choices'][0]['message']['content'])) {
    $aiResponse = $responseData['choices'][0]['message']['content'];
    echo json_encode(['suggestion' => $aiResponse]);
} else {
    echo json_encode(['suggestion' => "AI could not generate a suggestion. Please try again later."]);
}
?>
