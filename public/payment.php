<?php
session_start();
include '../config/db_connect.php';

if (!isset($_GET['order_id'])) {
    header("Location: cart.php");
    exit;
}

$order_id = intval($_GET['order_id']);

// Fetch order details
$result = $conn->query("SELECT * FROM orders WHERE order_id = $order_id");
$order = $result->fetch_assoc();

if (!$order) {
    die("Order not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Secure Payment | DragonStone</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f8f5;
      margin: 0;
      color: #222;
      text-align: center;
      padding: 60px 20px;
    }

    .container {
      width: 90%;
      max-width: 500px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    h1 {
      color: #2e7d32;
    }

    input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
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
      width: 100%;
      margin-top: 10px;
    }

    button:hover {
      background: #1b5e20;
    }

    .total {
      font-size: 18px;
      font-weight: bold;
      color: #1b5e20;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

<div class="container">
  <h1>Secure Payment</h1>
  <p>Please enter your payment details below.</p>
  <div class="total">Total: R <?php echo number_format($order['total_amount'], 2); ?></div>

  <form action="payment_process.php" method="POST">
    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
    <input type="text" name="card_name" placeholder="Cardholder Name" required>
    <input type="text" name="card_number" placeholder="Card Number" maxlength="16" required>
    <input type="text" name="expiry" placeholder="MM/YY" maxlength="5" required>
    <input type="text" name="cvv" placeholder="CVV" maxlength="3" required>
    <button type="submit" name="pay_now">Pay Now</button>
  </form>
</div>

</body>
</html>
