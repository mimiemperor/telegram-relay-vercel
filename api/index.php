<?php
// TELEGRAM RELAY FOR VERCEL HOSTING

// Set your bot token and chat ID here
$bot_token = "6382164078:AAGHaXrCDPPD4Tn8KRbDiOP-Fy7UZSi_XSU";
$chat_id = "6153907497";

// Verify the secret
$expected_secret = "LkT93!a6x#BzP00";
$received_secret = $_POST['secret'] ?? '';

if ($_SERVER["REQUEST_METHOD"] !== "POST" || $received_secret !== $expected_secret) {
    http_response_code(403);
    echo "Forbidden";
    exit;
}

// Get the message text
$message = $_POST['text'] ?? '';

if (!$message) {
    http_response_code(400);
    echo "Missing text.";
    exit;
}

// Send the message via Telegram Bot API
$send_url = "https://api.telegram.org/bot$bot_token/sendMessage";
$data = [
    'chat_id' => $chat_id,
    'text' => $message,
    'parse_mode' => 'HTML'
];

// Send request
$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
        'timeout' => 10
    ]
];
$context  = stream_context_create($options);
$result = file_get_contents($send_url, false, $context);

if ($result === FALSE) {
    http_response_code(500);
    echo "Telegram send failed.";
    exit;
}

echo "Message sent successfully.";
?>
