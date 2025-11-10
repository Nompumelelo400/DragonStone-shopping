<?php
include '../config/db_connect.php';
session_start();

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$order = null;

if ($order_id > 0) {
    $result = $conn->query("SELECT * FROM orders WHERE order_id = $order_id");
    $order = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Successful | DragonStone</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f8f5;
      text-align: center;
      padding: 80px 20px;
      color: #2e7d32;
    }
    .btn {
      display: inline-block;
      background: #2e7d32;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
      margin-top: 20px;
    }
    .btn:hover {
      background: #1b5e20;
    }
    .summary {
      margin-top: 20px;
      background: white;
      display: inline-block;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

  <h1>✅ Order Placed Successfully!</h1>
  <p>Thank you for your purchase, and for supporting sustainable living!</p>

  <?php if ($order): ?>
  <div class="summary">
    <p><strong>Order ID:</strong> <?php echo $order['order_id']; ?></p>
    <p><strong>Total Paid:</strong> R <?php echo number_format($order['total_amount'], 2); ?></p>
    <p><strong>Payment Status:</strong> <?php echo $order['payment_status']; ?></p>
    <p><strong>Shipping Status:</strong> <?php echo $order['shipping_status']; ?></p>
  </div>
  <?php endif; ?>

  <a href="index.php" class="btn">Return to Home</a>

</body>
</html>
