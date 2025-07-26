<!DOCTYPE html>
<html>
<head>
    <title>List as Investor</title>
</head>
<body>
    <h2>List Yourself as Investor</h2>
    <form action="submit_investor.php" method="POST" enctype="multipart/form-data">
        <label>Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Company:</label><br>
        <input type="text" name="company" required><br><br>

        <label>Short Description:</label><br>
        <textarea name="description" required></textarea><br><br>

        <label>Contact Details (Email or Phone):</label><br>
        <input type="text" name="contact" required><br><br>

        <label>Investor Photo:</label><br>
        <input type="file" name="photo" accept="image/*" required><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
