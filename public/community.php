<?php
session_start();
include __DIR__ . '/../config/db_connect.php';

// Handle new post (only if user is logged in)
if (isset($_POST['submit_post'])) {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Please log in to post in the community.'); window.location='login.php';</script>";
        exit;
    }

    // Get user ID and form data safely
    $user_id = $_SESSION['user_id'];
    $title = isset($_POST['title']) ? $conn->real_escape_string($_POST['title']) : '';
    $content = isset($_POST['post_content']) ? $conn->real_escape_string($_POST['post_content']) : '';

    // Prevent empty submissions
    if (!empty($title) && !empty($content)) {
        $conn->query("INSERT INTO community_posts (user_id, title, content, created_at)
                      VALUES ($user_id, '$title', '$content', NOW())");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Community | DragonStone</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f8f9fa;
      margin: 0;
      padding: 0;
    }

    header {
      background-color: #2e7d32;
      color: white;
      text-align: center;
      padding: 15px 0;
      font-size: 1.2rem;
    }

    .container {
      width: 90%;
      max-width: 900px;
      margin: 40px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    h2 {
      color: #2e7d32;
      margin-bottom: 15px;
      text-align: center;
    }

    form {
      margin-bottom: 25px;
      text-align: center;
    }

    input[type="text"],
    textarea {
      width: 100%;
      border: 1px solid #ccc;
      border-radius: 6px;
      padding: 10px;
      font-size: 15px;
      margin-top: 10px;
      resize: none;
      box-sizing: border-box;
    }

    input[type="text"]:focus,
    textarea:focus {
      border-color: #2e7d32;
      outline: none;
      box-shadow: 0 0 3px rgba(46, 125, 50, 0.3);
    }

    button {
      background: #2e7d32;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
      margin-top: 10px;
    }

    button:hover {
      background: #1b5e20;
    }

    .post {
      background: #f1f8e9;
      border: 1px solid #c8e6c9;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 15px;
    }

    .post h4 {
      margin: 0;
      color: #2e7d32;
    }

    .post h5 {
      margin: 5px 0;
      color: #388e3c;
      font-size: 17px;
    }

    .post p {
      margin: 5px 0 0;
      color: #333;
    }

    .login-prompt {
      text-align: center;
      color: #555;
      background: #f1f1f1;
      padding: 15px;
      border-radius: 6px;
      margin-bottom: 20px;
    }

    .login-prompt a {
      color: #2e7d32;
      font-weight: 600;
      text-decoration: none;
    }

    .login-prompt a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <header>
    <h1>DragonStone Community</h1>
  </header>

  <div class="container">
    <h2>Share Your Sustainability Tips 🌱</h2>

    <?php if (isset($_SESSION['user_id'])): ?>
      <!-- Form for logged-in users -->
      <form method="POST" action="">
        <input type="text" name="title" placeholder="Post Title" required><br>
        <textarea name="post_content" placeholder="Share your eco-friendly ideas..." required></textarea><br>
        <button type="submit" name="submit_post">Post</button>
      </form>
    <?php else: ?>
      <!-- Message for guests -->
      <div class="login-prompt">
        Please <a href="login.php">log in</a> or <a href="register.php">register</a> to share your sustainability tips.
      </div>
    <?php endif; ?>

    <h2>Community Posts</h2>

    <?php
    $result = $conn->query("
      SELECT p.title, p.content, p.created_at, u.name AS user_name
      FROM community_posts p
      JOIN users u ON p.user_id = u.user_id
      ORDER BY p.created_at DESC
    ");

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "
            <div class='post'>
              <h4>{$row['user_name']}</h4>
              <h5>{$row['title']}</h5>
              <p>{$row['content']}</p>
              <small>Posted on {$row['created_at']}</small>
            </div>";
        }
    } else {
        echo "<p style='text-align:center; color:#777;'>No community posts yet. Be the first to share!</p>";
    }
    ?>
  </div>
</body>
</html>
