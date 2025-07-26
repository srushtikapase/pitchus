<?php
include 'config/db.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pitch Startup Idea</title>
</head>
<body>
    <h2>Pitch Your Startup Idea</h2>
    <form action="submit_startup.php" method="POST" enctype="multipart/form-data">
        <label>Your Name / Startup Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Startup Idea Description:</label><br>
        <textarea name="idea" required></textarea><br><br>

        <label>Pitch Amount (₹):</label><br>
        <input type="number" name="pitch_amount" step="0.01" required><br><br>

        <label>Equity Offered (%):</label><br>
        <input type="number" name="equity" step="0.1" required><br><br>

        <label>Product Image:</label><br>
        <input type="file" name="product_photo" accept="image/*" required><br><br>

        <label>Attach Documentation (PDF):</label><br>
        <input type="file" name="documentation" accept="application/pdf" required><br><br>

        <!-- Add this field to the form -->
        <label>Startup Wallet Address (for funding):</label><br>
        <input type="text" name="wallet_address" required placeholder="e.g., 0xabc123..."><br><br>


        <label>Select Investor to Pitch:</label><br>
        <select name="investor_id" required>
            <option value="">-- Select Investor --</option>
            <?php
            $result = $conn->query("SELECT id, name FROM investors");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
            ?>
        </select><br><br>

        <input type="submit" value="Pitch">
    </form>
</body>
</html>
