<?php
include 'db.php';

// Step 1: Get vote count per candidate
$sql = "SELECT c.full_name, COUNT(v.vote_id) AS vote_count
        FROM candidates c
        LEFT JOIN votes v ON c.candidate_id = v.candidate_id
        GROUP BY c.candidate_id";
$result = $conn->query($sql);

// Step 2: Collect results
$candidates = [];
$total_votes = 0;

while ($row = $result->fetch_assoc()) {
    $row['vote_count'] = (int)$row['vote_count'];
    $candidates[] = $row;
    $total_votes += $row['vote_count'];
}

// Colors to rotate
$colors = ['cand-a', 'cand-b', 'cand-c', 'cand-d', 'cand-e'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Live Voting Results</title>
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
      text-align: center;
    }

    .result {
      margin-bottom: 25px;
    }

    .label {
      font-weight: bold;
      margin-bottom: 8px;
    }

    .bar {
      height: 30px;
      background-color: #e0e0e0;
      border-radius: 20px;
      overflow: hidden;
    }

    .fill {
      height: 100%;
      line-height: 30px;
      color: white;
      text-align: right;
      padding-right: 15px;
      border-radius: 20px;
      font-weight: bold;
    }

    .cand-a { background-color: #3949ab; }
    .cand-b { background-color: #43a047; }
    .cand-c { background-color: #fb8c00; }
    .cand-d { background-color: #d81b60; }
    .cand-e { background-color: #00838f; }

    .count {
      font-size: 14px;
      color: #444;
      margin-top: 5px;
    }

    footer {
      text-align: center;
      color: #999;
      font-size: 14px;
      margin-top: 30px;
    }
  </style>
</head>
<body>

  <header>
    <h1>Live Voting Results</h1>
    <p>Real-time update of current vote counts</p>
  </header>

  <div class="container">
    <h2>Student Council Election</h2>

    <?php
    if ($total_votes == 0) {
        echo "<p style='text-align:center;'>No votes have been cast yet.</p>";
    } else {
        foreach ($candidates as $index => $cand) {
            $name = htmlspecialchars($cand['full_name']);
            $votes = $cand['vote_count'];
            $percent = round(($votes / $total_votes) * 100);
            $bar_class = $colors[$index % count($colors)];

            echo "
            <div class='result'>
              <div class='label'>Candidate – $name</div>
              <div class='bar'>
                <div class='fill $bar_class' style='width: $percent%;'>$percent%</div>
              </div>
              <div class='count'>Votes: $votes</div>
            </div>";
        }
    }
    ?>
  </div>

  <footer>
    &copy; 2025 University Voting System | Results update live
  </footer>

</body>
</html>
