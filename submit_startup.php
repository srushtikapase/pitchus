<?php
include 'config/db.php';

$name = $_POST['name'];
$idea = $_POST['idea'];
$pitch_amount = $_POST['pitch_amount'];
$equity = $_POST['equity'];
$investor_id = $_POST['investor_id'];
$wallet_address = $_POST['wallet_address'];

$product_photo = $_FILES['product_photo']['name'];
$doc_file = $_FILES['documentation']['name'];

$product_target = "assets/uploads/startups/" . basename($product_photo);
$doc_target = "assets/uploads/startups/" . basename($doc_file);

move_uploaded_file($_FILES['product_photo']['tmp_name'], $product_target);
move_uploaded_file($_FILES['documentation']['tmp_name'], $doc_target);

$stmt = $conn->prepare("INSERT INTO startups (name, idea, pitch_amount, equity_percentage, product_photo, documentation, investor_id, wallet_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssddssis", $name, $idea, $pitch_amount, $equity, $product_photo, $doc_file, $investor_id, $wallet_address);
$stmt->execute();

require 'send_email.php';
sendPitchEmail($conn, $investor_id, $name, $idea, $pitch_amount, $equity);

header("Location: index.php");
exit();
?>