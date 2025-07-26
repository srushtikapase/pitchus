<?php
require 'config/db.php'; // if you want to log calls later

$api_key = "tqf5976xmjps";
$api_secret = "h7etarrpzs4gkrdm97anssn8uv83y7qbd8qdg97f4jmufw22sqw885h6bscths7e";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $caller_name = $_POST['name'];
    $investor_name = $_POST['investor_name'];

    // Generate UUIDv4 call ID
    $call_id = bin2hex(random_bytes(8));

    // Build JWT
    function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    $header = base64url_encode(json_encode(['alg' => 'HS256','typ' => 'JWT']));
    $payload = base64url_encode(json_encode(['user_id'=>'server_user','iat'=>time(),'exp'=>time()+3600]));
    $sig = base64url_encode(hash_hmac('sha256', "$header.$payload", $api_secret, true));
    $jwt = "$header.$payload.$sig";

    // Call Stream API to create a ringing call with members
    $url = "https://api.stream-io-api.com/video/v1/call/default/$call_id";
    $body = json_encode([
        "ring" => true,
        "data" => ["created_by_id" => "server_user"],
        "members" => [
            ["user_id" => "server_user"],
            ["user_id" => "investor_$call_id"]
        ]
    ]);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: $jwt",
            "Stream-Api-Key: $api_key"
        ],
        CURLOPT_RETURNTRANSFER => true
    ]);
    $res = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($res, true);
    if (empty($data['call'])) {
        echo json_encode(['error' => 'Failed to create meeting']);
        exit;
    }

    // Guest join URL (adjust as needed)
    $join_url = "https://app.stream.video/guest?call_type=default&call_id=$call_id&username=" . urlencode($caller_name);

    // Send email to investor
    $subject = "Video Call Request from $caller_name";
    $msg = "Hello $investor_name,\n\nYou've been invited to join a live video call by $caller_name.\nClick here to join:\n$join_url\n\n—Startup Platform";
    mail($email, $subject, $msg, "From: no-reply@startupplatform.com");

    echo json_encode(['url' => $join_url]);
    exit;
}
?>
