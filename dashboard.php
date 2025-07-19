<?php
session_start();
include("db.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$votes = $conn->query("SELECT COUNT(*) AS total_votes FROM votes")->fetch_assoc()['total_votes'];

$feedbacks = $conn->query("SELECT COUNT(*) AS total_feedbacks FROM feedback")->fetch_assoc()['total_feedbacks'];

$polls = $conn->query("SELECT COUNT(*) AS live_polls FROM polls WHERE status='active'")->fetch_assoc()['live_polls'];

$faculty_feedbacks = $conn->query("SELECT COUNT(*) AS faculty_feedbacks FROM feedback WHERE role='faculty'")->fetch_assoc()['faculty_feedbacks'];

$announcement = $conn->query("SELECT message FROM announcements ORDER BY created_at DESC LIMIT 1");
$latest_announcement = $announcement->num_rows > 0 ? $announcement->fetch_assoc()['message'] : "No announcements yet.";

$summary = "The average rating for Semester 2 subjects has increased by 12%. Faculty performance feedback is under review.";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f4f9;
        }

        header {
            background-color: #283593;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .sidebar {
            width: 220px;
            background-color: #3949ab;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 80px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 15px 20px;
            text-decoration: none;
            border-bottom: 1px solid #5c6bc0;
        }

        .sidebar a:hover {
            background-color: #303f9f;
        }

        .main-content {
            margin-left: 220px;
            padding: 30px;
        }

        .card {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            color: #283593;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .card .number {
            font-size: 28px;
            color: #111;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <header>
        <h1>University Voting & Feedback Dashboard</h1>
    </header>

    <div class="sidebar">
        <a href="#">🏠 Dashboard</a>
        <a href="register.php">📝 Sign Up/Register</a>
        <a href="login.php">🔐 Login</a>
        <a href="feedback.php">📝 Submit Feedback</a>
        <a href="vote.php">🗳️ Vote Now</a>
        <a href="result.php">📊 Results</a>
        <a href="profile.php">👤 Profile</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="main-content">
        <div class="stats-grid">
    <div class="card">
        <h3>Total Students Voted</h3>
        <div class="number"><?php echo $votes; ?></div>
    </div>

    <div class="card">
        <h3>Total Feedback Submitted</h3>
        <div class="number"><?php echo $feedbacks; ?></div>
    </div>

    <div class="card">
        <h3>Live Polls</h3>
        <div class="number"><?php echo $polls; ?></div>
    </div>

    <div class="card">
        <h3>Faculty Feedbacks</h3>
        <div class="number"><?php echo $faculty_feedbacks; ?></div>
    </div>
</div>

<div class="card">
    <h3>Latest Announcement</h3>
    <p>📢 <?php echo $latest_announcement; ?></p>
</div>

<div class="card">
    <h3>Recent Feedbacks Summary</h3>
    <p><?php echo $summary; ?></p>
</div>

    </div>

</body>
</html>
