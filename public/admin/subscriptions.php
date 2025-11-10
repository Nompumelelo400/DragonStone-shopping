<?php
session_start();
include __DIR__ . '/../../config/db_connect.php';

// Only logged-in admins can access
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Query for subscription statistics grouped by product and frequency
$query = "
SELECT 
    p.product_id,
    p.name AS product_name,
    c.category_name,
    s.frequency,
    COUNT(s.subscription_id) AS total_subscribers,
    MAX(s.next_billing_date) AS next_billing_date
FROM subscriptions s
JOIN products p ON s.product_id = p.product_id
LEFT JOIN categories c ON p.category_id = c.category_id
GROUP BY p.product_id, p.name, c.category_name, s.frequency
ORDER BY total_subscribers DESC
";

$result = $conn->query($query);

if (!$result) {
    die("SQL Error: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Subscriptions | DragonStone Admin</title>
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
      max-width: 1000px;
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
    <h2>Subscription Analytics</h2>
    <p style="text-align:center;">Overview of all active product subscriptions by frequency and category.</p>

    <table>
      <tr>
        <th>Product Name</th>
        <th>Category</th>
        <th>Frequency</th>
        <th>Total Subscribers</th>
        <th>Next Billing Date</th>
      </tr>

      <?php
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<tr>
        <td>{$row['product_name']}</td>
        <td>{$row['category_name']}</td>
        <td>{$row['frequency']}</td>
        <td>{$row['total_subscribers']}</td>
        <td>{$row['next_billing_date']}</td>
        <td><a href='subscription_details.php?product_id=" . urlencode($row['product_id']) . "'>View Details</a></td>
      </tr>";

          }
      } else {
          echo "<tr><td colspan='5' style='text-align:center;'>No active subscriptions found.</td></tr>";
      }
      ?>
    </table>

    <a href='index.php' class='back'>← Back to Dashboard</a>
  </div>

</body>
</html>
