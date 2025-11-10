<?php
session_start();
include __DIR__ . '/../../config/db_connect.php';

// If already logged in, go to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

// Handle login form submission
if (isset($_POST['login'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // You can change these to match your admin credentials
    $sql = "SELECT * FROM users WHERE email = '$email' AND role_id = 1"; // 1 = Admin role
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // For now, compare plain text or hashed password if you’ve hashed it
        if ($password === $user['password_hash'] || password_verify($password, $user['password_hash'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_name'] = $user['name'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Admin user not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login | DragonStone</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f0f4f0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .login-container {
      background: white;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    h2 {
      color: #2e7d32;
      margin-bottom: 20px;
    }

    input {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
    }

    button {
      background: #2e7d32;
      color: white;
      border: none;
      padding: 10px 0;
      width: 100%;
      border-radius: 6px;
      margin-top: 20px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background: #1b5e20;
    }

    .error {
      color: #b71c1c;
      margin-top: 10px;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <h2>Admin Login</h2>
    <form method="POST">
      <input type="email" name="email" placeholder="Admin Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="login">Login</button>
      <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    </form>
  </div>

</body>
</html>
