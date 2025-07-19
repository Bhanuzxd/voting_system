<?php
include("db.php");
session_start();

// Fetch active polls
$sql = "SELECT poll_id, title, description, created_at FROM polls WHERE status = 'active' ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Active Polls</title>
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
      max-width: 900px;
      margin: 40px auto;
      padding: 20px;
    }

    .poll-card {
      background: white;
      padding: 20px;
      margin-bottom: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .poll-card h3 {
      color: #3949ab;
    }

    .poll-card p {
      color: #555;
    }

    .poll-card small {
      color: #999;
    }

    .poll-card a {
      display: inline-block;
      margin-top: 10px;
      background-color: #3949ab;
      color: white;
      padding: 10px 18px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 15px;
    }

    .poll-card a:hover {
      background-color: #303f9f;
    }

    .no-polls {
      text-align: center;
      font-size: 18px;
      color: #666;
      margin-top: 50px;
    }
  </style>
</head>
<body>

  <header>
    <h1>Available Polls</h1>
    <p>Participate in ongoing university polls below</p>
  </header>

  <div class="container">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($poll = $result->fetch_assoc()): ?>
        <div class="poll-card">
          <h3><?= htmlspecialchars($poll['title']) ?></h3>
          <p><?= nl2br(htmlspecialchars($poll['description'])) ?></p>
          <small>Created on: <?= date("d M Y", strtotime($poll['created_at'])) ?></small><br>
          <a href="poll_details.php?poll_id=<?= $poll['poll_id'] ?>">Vote Now</a>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="no-polls">
        📭 No active polls available right now.
      </div>
    <?php endif; ?>
  </div>

</body>
</html>
