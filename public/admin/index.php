<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

include __DIR__ . '/../../config/db_connect.php';

// Fetch all products
$product_result = $conn->query("SELECT * FROM products");

// Fetch recent orders
$order_result = $conn->query("SELECT * FROM orders ORDER BY order_date DESC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | DragonStone</title>
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
      padding: 15px 30px;
      text-align: center;
    }

    .container {
      width: 90%;
      max-width: 1200px;
      margin: 30px auto;
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    h2 {
      border-bottom: 2px solid #2e7d32;
      padding-bottom: 8px;
      color: #2e7d32;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    th, td {
      padding: 10px;
      border: 1px solid #ddd;
      text-align: left;
    }

    th {
      background-color: #2e7d32;
      color: white;
    }

    a.button {
      display: inline-block;
      padding: 8px 12px;
      background: #2e7d32;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-size: 14px;
    }

    a.button:hover {
      background: #1b5e20;
    }

    .actions a {
      margin-right: 8px;
    }

    .summary-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;
  margin: 30px auto;
  width: 90%;
  max-width: 1200px;
}

.summary-card {
  background-color: #ffffff;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  padding: 25px;
  text-align: center;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.summary-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.summary-card h3 {
  color: #2e7d32;
  margin-bottom: 10px;
  font-size: 18px;
}

.summary-card p {
  font-size: 22px;
  font-weight: 600;
  color: #333;
}

.chart-container {
  width: 90%;
  max-width: 900px;
  margin: 40px auto;
  background: #ffffff;
  padding: 25px;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.chart-container h2 {
  text-align: center;
  color: #2e7d32;
  margin-bottom: 20px;
}

</style>
</head>
<body>
  <header>
    <h1>DragonStone Admin Dashboard</h1>
  </header>

  <?php include 'admin_navbar.php'; ?>

  <?php
// Dashboard summary counts
$total_products = $conn->query("SELECT COUNT(*) AS count FROM products")->fetch_assoc()['count'];
$total_orders = $conn->query("SELECT COUNT(*) AS count FROM orders")->fetch_assoc()['count'];
$total_customers = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$total_revenue_result = $conn->query("SELECT SUM(total_amount) AS total FROM orders WHERE payment_status='Paid'");
$total_revenue = $total_revenue_result->fetch_assoc()['total'] ?? 0;
?>

<!-- Dashboard Summary Section -->
<div class="summary-container">
  <div class="summary-card">
    <h3>Total Products</h3>
    <p><?php echo $total_products; ?></p>
  </div>

  <div class="summary-card">
    <h3>Total Orders</h3>
    <p><?php echo $total_orders; ?></p>
  </div>

  <div class="summary-card">
    <h3>Total Customers</h3>
    <p><?php echo $total_customers; ?></p>
  </div>

  <div class="summary-card">
    <h3>Total Revenue (R)</h3>
    <p><?php echo number_format($total_revenue, 2); ?></p>
  </div>
</div>

<!-- Sales Analytics Chart -->
<div class="chart-container">
  <h2>Sales Analytics</h2>
  <canvas id="salesChart"></canvas>
</div>

<?php
// Generate monthly revenue data for the chart
$monthly_sales_query = $conn->query("
    SELECT DATE_FORMAT(order_date, '%M %Y') AS month, 
           SUM(total_amount) AS total
    FROM orders
    WHERE payment_status = 'Paid'
    GROUP BY DATE_FORMAT(order_date, '%Y-%m')
    ORDER BY order_date ASC
");

$months = [];
$totals = [];

while ($row = $monthly_sales_query->fetch_assoc()) {
    $months[] = $row['month'];
    $totals[] = $row['total'];
}
?>

  <div class="container">
    <h2>Product Management</h2>
    <a href="add_product.php" class="button">+ Add New Product</a>
    <table>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Category</th>
        <th>Actions</th>
      </tr>
      <?php while($product = $product_result->fetch_assoc()) { ?>
      <tr>
        <td><?php echo $product['product_id']; ?></td>
        <td><?php echo $product['name']; ?></td>
        <td>R <?php echo $product['price']; ?></td>
        <td><?php echo $product['category'] ?? 'N/A'; ?></td>
        <td class="actions">
        <a href="edit_product.php?id=<?php echo $product['product_id']; ?>" class="button">Edit</a>
        <a href="delete_product.php?id=<?php echo $product['product_id']; ?>" 
        class="button" style="background:#b71c1c;" 
        onclick="return confirm('Are you sure you want to delete this product?');">
        Delete
        </a>
        </td>
      </tr>
      <?php } ?>
    </table>
  </div>

  <div class="container">
    <h2>Recent Orders</h2>
    <table>
      <tr>
        <th>Order ID</th>
        <th>User ID</th>
        <th>Total Amount</th>
        <th>Status</th>
        <th>Order Date</th>
      </tr>
      <?php while($order = $order_result->fetch_assoc()) { ?>
      <tr>
        <td><?php echo $order['order_id']; ?></td>
        <td><?php echo $order['user_id']; ?></td>
        <td>R <?php echo $order['total_amount']; ?></td>
        <td><?php echo $order['payment_status']; ?></td>
        <td><?php echo $order['order_date']; ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const ctx = document.getElementById('salesChart').getContext('2d');
  const salesChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: <?php echo json_encode($months); ?>,
      datasets: [{
        label: 'Monthly Revenue (R)',
        data: <?php echo json_encode($totals); ?>,
        borderColor: '#2e7d32',
        backgroundColor: 'rgba(46, 125, 50, 0.2)',
        fill: true,
        borderWidth: 2,
        tension: 0.3,
        pointRadius: 5,
        pointHoverRadius: 7
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: true,
          labels: { color: '#333' }
        },
        title: {
          display: true,
          text: 'Monthly Revenue Overview',
          color: '#2e7d32',
          font: { size: 18 }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { color: '#555' }
        },
        x: {
          ticks: { color: '#555' }
        }
      }
    }
  });
</script>

</body>
</html>
