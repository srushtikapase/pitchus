<?php
function sendPitchEmail($conn, $investor_id, $startupName, $idea, $amount, $equity) {
    // Get investor email
    $stmt = $conn->prepare("SELECT contact FROM investors WHERE id = ?");
    $stmt->bind_param("i", $investor_id);
    $stmt->execute();
    $stmt->bind_result($email);
    $stmt->fetch();
    $stmt->close();

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return; // skip if not a valid email
    }

    $subject = "New Pitch from $startupName";
    $message = "Hello,\n\nYou have received a new pitch from $startupName.\n\n".
               "Idea: $idea\nAmount Requested: ₹$amount\nEquity Offered: $equity%\n\n".
               "Please log in to view more.\n\nRegards,\nStartup Platform";
    $headers = "From: no-reply@startupplatform.com";

    mail($email, $subject, $message, $headers);
}
?>
