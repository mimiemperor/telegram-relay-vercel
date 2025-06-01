<?php
header('Content-Type: application/json');

$expectedSecret = "LkT93!a6x#BzP00"; // Match this with your PHP form

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Only POST allowed"]);
    exit;
}

// Read raw JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['secret']) || $data['secret'] !== $expectedSecret) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Invalid secret"]);
    exit;
}

if (!isset($data['text'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing message text"]);
    exit;
}

$botToken = "8102487994:AAEfna9GzXgOteqB2H3dT00zdCagEs03r3U";
$chatId = "6721680994";
$text = $data['text'];

$telegramUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";

$ch = curl_init($telegramUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'chat_id' => $chatId,
    'text' => $text
]);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo json_encode([
    "status" => $http_code == 200 ? "ok" : "failed",
    "http_code" => $http_code,
    "telegram_response" => $response
]);
?>
