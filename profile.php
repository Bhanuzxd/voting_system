<?php
session_start();
include 'db.php';

// Step 1: Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<h3 style='color:red; text-align:center;'>⚠️ You must be logged in to view your profile.</h3>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Step 2: Fetch user details from database
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    echo "<h3 style='color:red; text-align:center;'>❌ User not found.</h3>";
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Profile</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f6fc;
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
      max-width: 600px;
      margin: 40px auto;
      background-color: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    h2 {
      color: #283593;
      margin-bottom: 25px;
      text-align: center;
    }

    .profile-pic {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      margin: 0 auto 20px;
      display: block;
      border: 3px solid #3949ab;
    }

    .info {
      margin-bottom: 15px;
    }

    .info label {
      font-weight: bold;
      color: #333;
    }

    .info p {
      margin: 5px 0 15px;
      color: #444;
    }

    .btn-edit {
      display: block;
      width: 100%;
      padding: 12px;
      background-color: #3949ab;
      color: white;
      border: none;
      font-size: 16px;
      border-radius: 8px;
      text-align: center;
      text-decoration: none;
    }
  </style>
</head>
<body>

<header>
  <h1>My Profile</h1>
</header>

<div class="container">
  <img src="https://via.placeholder.com/100" alt="Profile Photo" class="profile-pic">
  <h2>Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</h2>


  <div class="info">
    <label>Email:</label>
    <p><?php echo htmlspecialchars($user['email']); ?></p>
  </div>

  <div class="info">
    <label>Role:</label>
    <p><?php echo htmlspecialchars(ucfirst($user['role'])); ?></p>
  </div>

  <div class="info">
    <label>Department:</label>
    <p><?php echo htmlspecialchars($user['department']); ?></p>
  </div>
</div>

</body>
</html>
