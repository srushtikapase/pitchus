<?php
include 'config/db.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Investors</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <h2>Available Investors</h2>
    <a href="index.php">← Back to Home</a>
    <br><br>

<?php
$result = $conn->query("SELECT * FROM investors");
while ($row = $result->fetch_assoc()) {
    echo "<div style='border:1px solid #ccc; padding:15px; margin-bottom:10px;'>";
    echo "<img src='assets/uploads/investors/" . $row['photo'] . "' height='100'><br>";
    echo "<strong>Name:</strong> " . htmlspecialchars($row['name']) . "<br>";
    echo "<strong>Company:</strong> " . htmlspecialchars($row['company']) . "<br>";
    echo "<strong>Description:</strong> " . htmlspecialchars($row['description']) . "<br>";
    echo "<strong>Contact:</strong> " . htmlspecialchars($row['contact']) . "<br>";
    echo "<button onclick=\"createMeeting('{$row['contact']}', '{$row['name']}')\">📞 Call Investor</button>";
    echo "</div>";
}
?>

<script>
async function createMeeting(email, investorName) {
    const userName = prompt("Enter your name to start the call:");
    if (!userName) return;

    const response = await fetch("create_meeting.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `email=${encodeURIComponent(email)}&name=${encodeURIComponent(userName)}&investor_name=${encodeURIComponent(investorName)}`
    });
    const data = await response.json();
    if (data && data.url) {
        window.open(data.url, '_blank');
    } else {
        alert("Failed to create meeting");
    }
}
</script>
</body>
</html>