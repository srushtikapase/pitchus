<?php
include 'config/db.php';

$name = $_POST['name'];
$description = $_POST['description'];
$download_link = $_POST['download_link'];

$stmt = $conn->prepare("INSERT INTO startup_documents (name, description, download_link) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $description, $download_link);
$stmt->execute();

header("Location: doc.php");
exit();
?>
