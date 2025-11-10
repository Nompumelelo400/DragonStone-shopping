<?php
session_start();
include __DIR__ . '/../config/db_connect.php';

// Handle login form submission
if (isset($_POST['login'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Fetch user by email
    $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password (use password_hash in real apps)
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['name'];
            echo "<script>alert('✅ Welcome back, {$user['name']}!'); window.location='index.php';</script>";
            exit;
        } else {
            echo "<script>alert('❌ Incorrect password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('❌ No user found with that email.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | DragonStone</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f8f9fa;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-container {
      background: white;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      width: 350px;
      text-align: center;
    }

    h2 {
      color: #2e7d32;
      margin-bottom: 20px;
    }

    input {
      width: 90%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
    }

    button {
      width: 95%;
      background: #2e7d32;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 10px;
    }

    button:hover {
      background: #1b5e20;
    }

    a {
      color: #2e7d32;
      text-decoration: none;
      font-size: 14px;
      display: block;
      margin-top: 15px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Login</h2>
    <form method="POST">
      <input type="email" name="email" placeholder="Enter your email" required>
      <input type="password" name="password" placeholder="Enter your password" required>
      <button type="submit" name="login">Login</button>
    </form>
    <a href="register.php">Don't have an account? Register here</a>
  </div>
</body>
</html>
