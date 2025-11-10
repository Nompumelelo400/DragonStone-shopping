<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

include __DIR__ . '/../../config/db_connect.php';

// Handle order status update
if (isset($_POST['update_order'])) {
    $order_id = intval($_POST['order_id']);
    $payment_status = $conn->real_escape_string($_POST['payment_status']);
    $shipping_status = $conn->real_escape_string($_POST['shipping_status']);

    $update_sql = "UPDATE orders 
                   SET payment_status='$payment_status', shipping_status='$shipping_status'
                   WHERE order_id=$order_id";

    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('✅ Order updated successfully!'); window.location='orders.php';</script>";
    } else {
        echo "<script>alert('❌ Error updating order: " . $conn->error . "');</script>";
    }
}

// Fetch all orders
$order_result = $conn->query("
    SELECT orders.*, users.name AS customer_name 
    FROM orders
    LEFT JOIN users ON orders.user_id = users.user_id
    ORDER BY orders.order_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Orders | DragonStone Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f9faf9;
      margin: 0;
      padding: 0;
    }

    header {
      background: #2e7d32;
      color: white;
      padding: 15px 0;
      text-align: center;
    }

    .container {
      width: 95%;
      max-width: 1200px;
      margin: 30px auto;
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    h2 {
      color: #2e7d32;
      border-bottom: 2px solid #2e7d32;
      padding-bottom: 8px;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
      font-size: 14px;
    }

    th {
      background-color: #2e7d32;
      color: white;
    }

    form {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    select {
      padding: 5px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    button {
      background: #2e7d32;
      color: white;
      border: none;
      padding: 6px 10px;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background: #1b5e20;
    }

    .back {
      display: inline-block;
      margin-top: 15px;
      text-decoration: none;
      color: #2e7d32;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <header>
    <h1>DragonStone Admin – Manage Orders</h1>
  </header>

  <?php include 'admin_navbar.php'; ?>

  <div class="container">
    <h2>Customer Orders</h2>
    <table>
      <tr>
        <th>Order ID</th>
        <th>Customer</th>
        <th>Total (R)</th>
        <th>Payment Status</th>
        <th>Shipping Status</th>
        <th>Order Date</th>
        <th>Actions</th>
      </tr>
      <?php while($order = $order_result->fetch_assoc()) { ?>
      <tr>
        <td><?php echo $order['order_id']; ?></td>
        <td><?php echo $order['customer_name'] ?? 'Guest'; ?></td>
        <td><?php echo $order['total_amount']; ?></td>
        <td><?php echo $order['payment_status']; ?></td>
        <td><?php echo $order['shipping_status']; ?></td>
        <td><?php echo $order['order_date']; ?></td>
        <td>
          <form method="POST">
            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">

            <select name="payment_status">
              <option <?php if($order['payment_status']=='Pending') echo 'selected'; ?>>Pending</option>
              <option <?php if($order['payment_status']=='Paid') echo 'selected'; ?>>Paid</option>
              <option <?php if($order['payment_status']=='Failed') echo 'selected'; ?>>Failed</option>
            </select>

            <select name="shipping_status">
              <option <?php if($order['shipping_status']=='Pending') echo 'selected'; ?>>Pending</option>
              <option <?php if($order['shipping_status']=='Shipped') echo 'selected'; ?>>Shipped</option>
              <option <?php if($order['shipping_status']=='Delivered') echo 'selected'; ?>>Delivered</option>
              <option <?php if($order['shipping_status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
            </select>

            <button type="submit" name="update_order">Update</button>
          </form>
        </td>
      </tr>
      <?php } ?>
    </table>

    <a href="index.php" class="back">← Back to Dashboard</a>
  </div>
</body>
</html>
