<?php
include 'config/db.php';

$name = $_POST['name'];
$company = $_POST['company'];
$description = $_POST['description'];
$contact = $_POST['contact'];

$photo = $_FILES['photo']['name'];
$target = "assets/uploads/investors/" . basename($photo);
move_uploaded_file($_FILES['photo']['tmp_name'], $target);

$stmt = $conn->prepare("INSERT INTO investors (name, company, description, contact, photo) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $company, $description, $contact, $photo);
$stmt->execute();

header("Location: index.php");
exit();
?>
