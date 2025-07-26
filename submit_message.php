<?php
include 'config/db.php';

$sender = $_POST['sender'];
$message = $_POST['message'];

$stmt = $conn->prepare("INSERT INTO chat_messages (sender_name, message) VALUES (?, ?)");
$stmt->bind_param("ss", $sender, $message);
$stmt->execute();
?>
