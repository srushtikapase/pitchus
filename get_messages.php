<?php
include 'config/db.php';

$result = $conn->query("SELECT sender_name, message FROM chat_messages ORDER BY created_at ASC");

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

header('Content-Type: application/json');
echo json_encode($messages);
?>
