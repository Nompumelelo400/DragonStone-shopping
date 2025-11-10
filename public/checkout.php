<?php
session_start();
include '../config/db_connect.php';

// Redirect if cart is empty
if (empty($_SESSION['cart'])) {
  header("Location: cart.php");
  exit;
}

// Simulate logged-in user (for now)
$user_id = 1; // Replace with actual user session later

// Handle form submission
if (isset($_POST['confirm_checkout'])) {
    echo "<pre>Checkout button clicked!</pre>";
    $total = 0;

    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Insert order with default 'Pending' payment and shipping status
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount) VALUES (?, ?)");
    $stmt->bind_param("id", $user_id, $total);
    $stmt->execute();

    // Clear cart
    $_SESSION['cart'] = [];

    // Simulate successful payment update
    $order_id = $conn->insert_id;
    $conn->query("UPDATE orders SET payment_status='Paid', shipping_status='Pending' WHERE order_id=$order_id");

    // Redirect to success page
    header("Location: payment.php?order_id=$order_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout | DragonStone</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f8f5;
      margin: 0;
      color: #222;
    }

    nav {
      background: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 5%;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }

    .logo {
      font-weight: 700;
      font-size: 24px;
      color: #2e7d32;
    }

    .container {
      width: 70%;
      margin: 40px auto;
      background: white;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      padding: 30px;
    }

    h1 {
      color: #2e7d32;
      text-align: center;
      margin-bottom: 20px;
    }

    .total {
      font-size: 18px;
      font-weight: bold;
      color: #1b5e20;
      text-align: right;
      margin-top: 20px;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    input, textarea {
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 16px;
    }

    button {
      background: #2e7d32;
      color: white;
      border: none;
      padding: 12px 25px;
      border-radius: 6px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background: #1b5e20;
    }

    footer {
      background: #2e7d32;
      color: white;
      text-align: center;
      padding: 25px;
      margin-top: 60px;
    }
  </style>
</head>
<body>

<nav>
  <div class="logo">🌱 DragonStone</div>
</nav>

<div class="container">
  <h1>Checkout</h1>

  <form method="POST">
    <p>Thank you for shopping sustainably with us!</p>

    <div class="total">
      Total: R 
      <?php
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
          $total += $item['price'] * $item['quantity'];
        }
        echo number_format($total, 2);
      ?>
    </div>

    <button type="submit" name="confirm_checkout">Confirm & Pay</button>
  </form>
</div>

<footer>
  <p>&copy; 2025 DragonStone | Sustainable Living for Everyone 🌿</p>
</footer>

</body>
</html>
