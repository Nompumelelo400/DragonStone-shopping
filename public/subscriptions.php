<?php
session_start();
include __DIR__ . '/../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("
    SELECT s.subscription_id, p.name, p.price, s.frequency, s.next_billing_date
    FROM subscriptions s
    JOIN products p ON s.product_id = p.product_id
    WHERE s.user_id = $user_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Subscriptions | DragonStone</title>
  <style>
    body {font-family:'Poppins',sans-serif;background:#f8f9fa;margin:0;padding:20px;}
    table {width:100%;border-collapse:collapse;margin-top:20px;}
    th,td {border:1px solid #ccc;padding:10px;text-align:left;}
    th {background:#2e7d32;color:white;}
    a.cancel {color:red;text-decoration:none;font-weight:600;}
  </style>
</head>
<body>
  <h2>My Active Subscriptions</h2>
  <table>
    <tr>
      <th>Product</th>
      <th>Price (R)</th>
      <th>Frequency</th>
      <th>Next Billing Date</th>
      <th>Action</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['name']}</td>
                <td>R {$row['price']}</td>
                <td>{$row['frequency']}</td>
                <td>{$row['next_billing_date']}</td>
                <td><a class='cancel' href='unsubscribe.php?id={$row['subscription_id']}'>Cancel</a></td>
              </tr>";
        }
    } else {
        echo "<tr><td colspan='5' style='text-align:center;'>No active subscriptions found.</td></tr>";
    }
    ?>
  </table>
</body>
</html>
