<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us | DragonStone</title>
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
    }

    .container {
      width: 80%;
      margin: 40px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    input, textarea {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    button {
      background: #2e7d32;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    button:hover {
      background: #1b5e20;
    }
  </style>
</head>
<body>
  <header>
    <h1>Contact DragonStone</h1>
  </header>

  <div class="container">
    <h2>We’d love to hear from you 🌿</h2>
    <form method="POST" action="">
      <input type="text" name="name" placeholder="Your Name" required>
      <input type="email" name="email" placeholder="Your Email" required>
      <textarea name="message" placeholder="Your Message..." rows="5" required></textarea>
      <button type="submit" name="send_message">Send Message</button>
    </form>

    <?php
    if (isset($_POST['send_message'])) {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $message = htmlspecialchars($_POST['message']);
        echo "<p style='color:green;'>Thank you, $name. Your message has been received. We’ll get back to you soon!</p>";
    }
    ?>
  </div>
</body>
</html>
