<?php
session_start();
include 'db.php';

if (isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id'] ?? null;
    $role    = trim($_POST['role'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $rating  = intval($_POST['rating'] ?? 0);
    $message = trim($_POST['feedback'] ?? '');

    if ($user_id && $role && $subject && $rating && $message) {
      
    $sql = "INSERT INTO feedback (user_id, role, subject, rating, message)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("❌ Prepare failed: " . $conn->error);
}


$stmt->bind_param("issis", $user_id, $role, $subject, $rating, $message);


    if ($stmt->execute()) {
        $success_msg = "✅ Thank you! Your feedback has been submitted successfully.";
    } else {
        $error_msg = "❌ Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    $error_msg = "❌ All fields are required.";
}

    $conn->close();
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Submit Feedback</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f6fc;
      margin: 0;
      padding: 0;
    }

    header {
      background-color: #283593;
      color: white;
      padding: 20px;
      text-align: center;
    }

    .container {
      max-width: 700px;
      margin: 40px auto;
      background-color: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0px 5px 15px rgba(0,0,0,0.1);
    }

    h2 {
      color: #283593;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }

    select,
    input[type="text"],
    textarea {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      margin-top: 5px;
    }

    textarea {
      resize: vertical;
      min-height: 100px;
    }

    button {
      margin-top: 25px;
      padding: 12px 20px;
      background-color: #3949ab;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
    }

    button:hover {
      background-color: #303f9f;
    }

    .msg {
      margin-top: 20px;
      font-size: 16px;
      font-weight: bold;
      text-align: center;
    }

    .success { color: green; }
    .error { color: red; }
  </style>
</head>
<body>

  <header>
    <h1>Feedback Form</h1>
    <p>We value your feedback to improve our system</p>
  </header>

  <div class="container">
    <form action="" method="post">
      <h2>Submit Your Feedback</h2>

      <label for="role">Your Role</label>
      <select id="role" name="role" required>
        <option value="">-- Select Role --</option>
        <option value="student">Student</option>
        <option value="faculty">Faculty</option>
        <option value="staff">Staff</option>
      </select>

      <label for="subject">Subject / Faculty Name</label>
      <input type="text" id="subject" name="subject" placeholder="e.g. Data Structures - Prof. Sharma" required>

      <label for="rating">Rating</label>
      <select id="rating" name="rating" required>
        <option value="">-- Select Rating --</option>
        <option value="5">⭐⭐⭐⭐⭐ - Excellent</option>
        <option value="4">⭐⭐⭐⭐ - Good</option>
        <option value="3">⭐⭐⭐ - Average</option>
        <option value="2">⭐⭐ - Poor</option>
        <option value="1">⭐ - Very Poor</option>
      </select>

      <label for="feedback">Your Feedback</label>
      <textarea id="feedback" name="feedback" placeholder="Write your feedback here..." required></textarea>

      <button type="submit" name="submit">Submit Feedback</button>

      
      <?php if (isset($success_msg)) echo "<div class='msg success'>{$success_msg}</div>"; ?>
      <?php if (isset($error_msg)) echo "<div class='msg error'>{$error_msg}</div>"; ?>
    </form>
  </div>

</body>
</html>

