<!-- add_announcement.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Add Announcement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f7;
            padding: 40px;
        }

        form {
            background: white;
            padding: 20px;
            width: 400px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }

        textarea {
            width: 100%;
            height: 100px;
            resize: none;
            padding: 10px;
            font-size: 16px;
        }

        button {
            background-color: #3949ab;
            color: white;
            padding: 10px 20px;
            border: none;
            margin-top: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .success {
            color: green;
            text-align: center;
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = trim($_POST["message"]);

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO announcements (message) VALUES (?)");
        $stmt->bind_param("s", $message);

        if ($stmt->execute()) {
            echo "<p class='success'>✅ Announcement added successfully!</p>";
        } else {
            echo "<p class='error'>❌ Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p class='error'>❌ Please enter a message.</p>";
    }
}
?>

<form method="POST">
    <h2>Add Announcement</h2>
    <textarea name="message" placeholder="Enter announcement message here..."></textarea><br>
    <button type="submit">Post Announcement</button>
</form>

</body>
</html>
