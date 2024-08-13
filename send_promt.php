<?php
// Retrieve 'text' from the GET request
$text = isset($_GET['prompt']) ? $_GET['prompt'] : 'Explain how AI works';


$googleApiKey = isset($_GET['key']) ? $_GET['key'] : '';

if (empty($text) || empty($googleApiKey)) {
    echo json_encode([
        'error' => 'Both "text" and "key" parameters are required.'
    ]);
    exit;
}

// Prepare the data to send in the POST request
$postData = json_encode([
    'contents' => [
        [
            'parts' => [
                ['text' => $text]
            ]
        ]
    ]
]);

// Initialize curl
$ch = curl_init();

// Set curl options
curl_setopt($ch, CURLOPT_URL, "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=" . urlencode($googleApiKey));
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

// Execute curl and get the response
$response = curl_exec($ch);

// Check for errors
if ($response === false) {
    echo json_encode([
        'error' => 'Curl error: ' . curl_error($ch)
    ]);
} else {
    // Decode the response
    $responseDecoded = json_decode($response, true);
    
    // Send the response back in JSON format
    echo json_encode($responseDecoded);
}

// Close curl
curl_close($ch);
?>