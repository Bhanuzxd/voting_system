<?php
session_start();
include 'db.php';

$login_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    $sql = "SELECT user_id, full_name, email, password, role FROM users 
            WHERE (email = ? OR roll_no = ?) AND role = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $username, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];

            header("Location: dashboard.php");
            exit;
        } else {
            $login_error = "❌ Incorrect password.";
        }
    } else {
        $login_error = "❌ No user found with this username and role.";
    }

    $stmt->close();
    $conn->close();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - University Voting System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #3949ab, #5c6bc0);
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        .login-box h2 {
            text-align: center;
            color: #283593;
            margin-bottom: 30px;
        }

        .login-box label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .login-box select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .login-box button {
            width: 100%;
            padding: 12px;
            background-color: #3949ab;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .login-box button:hover {
            background-color: #303f9f;
        }

        .login-box .note {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            color: #666;
        }

        .error-msg {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <h2>Login to Your Account</h2>
        <?php if (!empty($login_error)) : ?>
            <div class="error-msg"><?= $login_error ?></div>
        <?php endif; ?>
        <form action="login.php" method="post">
            <label for="username">Username or Roll No</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="role">Select Role</label>
            <select id="role" name="role" required>
                <option value="">-- Select Role --</option>
                <option value="student">Student</option>
                <option value="faculty">Faculty</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit">Login</button>

            <div class="note">
                Don't have an account? <a href="register.php">Sign Up</a>
            </div>
        </form>
    </div>

</body>
</html>

