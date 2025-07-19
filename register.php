<?php
session_start();
include 'db.php';

$success_msg = "";
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $roll_no = trim($_POST['roll_no']);
    $role = $_POST['role'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($full_name) || empty($email) || empty($roll_no) || empty($role) || empty($password) || empty($confirm_password)) {
        $error_msg = "❌ Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "❌ Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error_msg = "❌ Passwords do not match.";
    } else {
        // Check if email or roll number already exists
        $check = $conn->prepare("SELECT * FROM users WHERE email = ? OR roll_no = ?");
        $check->bind_param("ss", $email, $roll_no);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $error_msg = "❌ Email or Roll Number already registered.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, roll_no, password, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $full_name, $email, $roll_no, $hashed_password, $role);

            if ($stmt->execute()) {
                $success_msg = "✅ Registration successful! You can now login.";
            } else {
                $error_msg = "❌ Something went wrong. Try again.";
            }

            $stmt->close();
        }

        $check->close();
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - University Voting System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #3949ab, #5c6bc0);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-box {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        .register-box h2 {
            text-align: center;
            color: #0d47a1;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #0d47a1;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0c3d91;
        }

        .msg {
            text-align: center;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .msg.error {
            color: red;
        }

        .msg.success {
            color: green;
        }

        .note {
            text-align: center;
            font-size: 14px;
            margin-top: 10px;
            color: #555;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Create Your Account</h2>

    <?php if (!empty($error_msg)) echo "<div class='msg error'>$error_msg</div>"; ?>
    <?php if (!empty($success_msg)) echo "<div class='msg success'>$success_msg</div>"; ?>

    <form action="register.php" method="post">
        <label for="full_name">Full Name</label>
        <input type="text" name="full_name" required>

        <label for="email">Email</label>
        <input type="email" name="email" required>

        <label for="roll_no">Roll Number</label>
        <input type="text" name="roll_no" required>

        <label for="role">Select Role</label>
        <select name="role" required>
            <option value="">-- Select Role --</option>
            <option value="student">Student</option>
            <option value="faculty">Faculty</option>
            <option value="admin">Admin</option>
        </select>

        <label for="password">Password</label>
        <input type="password" name="password" required>

        <label for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">Register</button>
    </form>

    <div class="note">
        Already have an account? <a href="login.php">Login here</a>.
    </div>
</div>

</body>
</html>
