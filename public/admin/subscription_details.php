<?php
session_start();
include __DIR__ . '/../../config/db_connect.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Get product ID from the query string
if (!isset($_GET['product_id'])) {
    die("Product ID not specified.");
}

$product_id = intval($_GET['product_id']);

// Fetch product details
$product_query = $conn->query("
    SELECT p.name, c.category_name 
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.category_id
    WHERE p.product_id = $product_id
");

if ($product_query->num_rows == 0) {
    die("Product not found.");
}
$product = $product_query->fetch_assoc();

// Fetch all subscribers for this product
$query = "
    SELECT 
        u.name AS customer_name,
        u.email,
        s.frequency,
        s.next_billing_date
    FROM subscriptions s
    JOIN users u ON s.user_id = u.user_id
    WHERE s.product_id = $product_id
    ORDER BY s.next_billing_date ASC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Subscription Details | DragonStone Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      padding: 20px;
    }

    header {
      background-color: #2e7d32;
      color: white;
      text-align: center;
      padding: 15px 0;
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
      text-align: center;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: left;
    }

    th {
      background-color: #2e7d32;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f1f8e9;
    }

    a.back {
      display: inline-block;
      margin-top: 15px;
      text-decoration: none;
      color: #2e7d32;
      font-weight: 600;
    }

    a.back:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <header>
    <h1>DragonStone Admin</h1>
  </header>

  <?php include 'admin_navbar.php'; ?>

  <div class="container">
    <h2>Subscribers for <?php echo htmlspecialchars($product['name']); ?></h2>
    <p style="text-align:center;">Category: <?php echo htmlspecialchars($product['category_name']); ?></p>

    <table>
      <tr>
        <th>Customer Name</th>
        <th>Email</th>
        <th>Frequency</th>
        <th>Next Billing Date</th>
      </tr>

      <?php
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>{$row['customer_name']}</td>
                      <td>{$row['email']}</td>
                      <td>{$row['frequency']}</td>
                      <td>{$row['next_billing_date']}</td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='4' style='text-align:center;'>No subscribers for this product.</td></tr>";
      }
      ?>
    </table>

    <a href='subscriptions.php' class='back'>← Back to Subscription Summary</a>
  </div>

</body>
</html>
