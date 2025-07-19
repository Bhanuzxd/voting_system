<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voter_name = trim($_POST['voter_name'] ?? '');
    $selected_candidate = trim($_POST['candidate'] ?? '');

    if (empty($voter_name) || empty($selected_candidate)) {
        echo "<h3 style='color:red;'>❌ Please enter your name and select a candidate.</h3>";
    } else {
        // Get voter user_id
        $stmt_user = $conn->prepare("SELECT user_id FROM users WHERE full_name = ?");
        $stmt_user->bind_param("s", $voter_name);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();

        if ($result_user->num_rows === 1) {
            $user_id = $result_user->fetch_assoc()['user_id'];

            // Check if already voted
            $check_vote = $conn->prepare("SELECT 1 FROM votes WHERE voter_id = ?");
            $check_vote->bind_param("i", $user_id);
            $check_vote->execute();
            $already_voted = $check_vote->get_result()->num_rows > 0;

            if ($already_voted) {
                echo "<h3 style='color:red;'>❌ You have already voted. Only one vote allowed.</h3>";
            } else {
                // Get candidate_id from candidates table
                $stmt_cand = $conn->prepare("SELECT candidate_id FROM candidates WHERE full_name = ?");
                $stmt_cand->bind_param("s", $selected_candidate);
                $stmt_cand->execute();
                $result_cand = $stmt_cand->get_result();

                if ($result_cand->num_rows === 1) {
                    $candidate_id = $result_cand->fetch_assoc()['candidate_id'];

                    // Insert vote
                    $stmt_vote = $conn->prepare("INSERT INTO votes (voter_id, candidate_id) VALUES (?, ?)");
                    $stmt_vote->bind_param("ii", $user_id, $candidate_id);
                    if ($stmt_vote->execute()) {
                        echo "<h3 style='color:green;'>✅ Vote submitted for <b>$selected_candidate</b>. Thank you!</h3>";
                    } else {
                        echo "<h3 style='color:red;'>❌ Error submitting vote.</h3>";
                    }
                    $stmt_vote->close();
                } else {
                    echo "<h3 style='color:red;'>❌ Candidate not found.</h3>";
                }

                $stmt_cand->close();
            }

            $check_vote->close();
        } else {
            echo "<h3 style='color:red;'>❌ Voter not found. Enter registered full name.</h3>";
        }

        $stmt_user->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Vote Now</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f6fc;
      margin: 0;
    }
    .container {
      max-width: 600px;
      margin: 40px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
    }
    header {
      background: #283593;
      color: white;
      padding: 20px;
      text-align: center;
    }
    h2 {
      color: #283593;
    }
    .candidate {
      margin: 15px 0;
      background: #e8eaf6;
      padding: 12px;
      border-radius: 8px;
    }
    button {
      background-color: #3949ab;
      color: white;
      padding: 12px;
      width: 100%;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 20px;
    }
    button:hover {
      background-color: #303f9f;
    }
  </style>
</head>
<body>

<header>
  <h1>Cast Your Vote</h1>
  <p>Choose your preferred candidate</p>
</header>

<div class="container">
  <form method="post">
    <label for="voter_name"><b>Your Full Name</b></label><br>
    <input type="text" id="voter_name" name="voter_name" required style="width:100%; padding:10px; margin:10px 0;"><br>

    <h2>Select Candidate</h2>

    <?php
    include 'db.php';
    $candidates = $conn->query("SELECT full_name FROM candidates");
    if ($candidates->num_rows > 0) {
        while ($row = $candidates->fetch_assoc()) {
            $name = htmlspecialchars($row['full_name']);
            echo "<div class='candidate'>
                    <input type='radio' name='candidate' value='$name' required> 
                    <label>$name</label>
                  </div>";
        }
    } else {
        echo "<p>No candidates found in system.</p>";
    }
    $conn->close();
    ?>

    <button type="submit">Submit Vote</button>
  </form>
</div>

</body>
</html>
