<?php
session_start();
include __DIR__ . '/../config/db_connect.php';

// Handle registration form submission
if (isset($_POST['register'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('❌ Passwords do not match!');</script>";
    } else {
        // Check if email already exists
        $check_user = $conn->query("SELECT * FROM users WHERE email='$email'");
        if ($check_user && $check_user->num_rows > 0) {
            echo "<script>alert('⚠️ Email already registered. Please log in instead.'); window.location='login.php';</script>";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $sql = "INSERT INTO users (name, email, password_hash, role_id, eco_points_balance)
                    VALUES ('$name', '$email', '$hashed_password', 1, 0)";
            
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('✅ Registration successful! Please log in.'); window.location='login.php';</script>";
            } else {
                echo "<script>alert('❌ Error: " . $conn->error . "');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | DragonStone</title>
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

    .register-container {
      background: white;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      width: 400px;
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
  <div class="register-container">
    <h2>Create Account</h2>
    <form method="POST">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <button type="submit" name="register">Register</button>
    </form>
    <a href="login.php">Already have an account? Login here</a>
  </div>
</body>
</html>
