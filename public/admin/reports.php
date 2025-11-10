<?php
session_start();
include __DIR__ . '/../../config/db_connect.php';

// Load Dompdf globally for all reports
require __DIR__ . '/../../vendor/autoload.php';
use Dompdf\Dompdf;

// Only allow logged-in admins
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Handle CSV Export
if (isset($_POST['export_csv'])) {
    $filename = "dragonstone_sales_report_" . date('Y-m-d') . ".csv";
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=$filename");

    $output = fopen("php://output", "w");
    fputcsv($output, ['Order ID', 'Customer Name', 'Total Amount (R)', 'Payment Status', 'Shipping Status', 'Order Date']);

    $result = $conn->query("
        SELECT o.order_id, u.name AS customer_name, o.total_amount, o.payment_status, o.shipping_status, o.order_date
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.user_id
        ORDER BY o.order_date DESC
    ");

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
}

// Handle PDF Export
if (isset($_POST['export_pdf'])) {
    $pdf = new Dompdf();

    $html = "
        <h2>DragonStone Sales Report</h2>
        <table border='1' cellspacing='0' cellpadding='5' width='100%'>
            <thead style='background-color:#2e7d32;color:white;'>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Total (R)</th>
                    <th>Payment</th>
                    <th>Shipping</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
    ";

    $result = $conn->query("
        SELECT o.order_id, u.name AS customer_name, o.total_amount, o.payment_status, o.shipping_status, o.order_date
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.user_id
        ORDER BY o.order_date DESC
    ");

    while ($row = $result->fetch_assoc()) {
        $html .= "
        <tr>
            <td>{$row['order_id']}</td>
            <td>{$row['customer_name']}</td>
            <td>R {$row['total_amount']}</td>
            <td>{$row['payment_status']}</td>
            <td>{$row['shipping_status']}</td>
            <td>{$row['order_date']}</td>
        </tr>";
    }

    $html .= "</tbody></table>";

    $pdf->loadHtml($html);
    $pdf->setPaper('A4', 'landscape');
    $pdf->render();
    $pdf->stream("dragonstone_sales_report.pdf", ["Attachment" => 1]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reports | DragonStone Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f9faf9;
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
      width: 90%;
      max-width: 800px;
      margin: 40px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      text-align: center;
    }

    h2 {
      color: #2e7d32;
      margin-bottom: 20px;
    }

    form {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 15px;
    }

    button {
      background: #2e7d32;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background: #1b5e20;
    }

    .back {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      color: #2e7d32;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <?php include 'admin_navbar.php'; ?>

  <div class="container">
    <h2>Generate Sales & Order Reports</h2>
    <p>Select a format below to export your data:</p>
    <form method="POST">
      <button type="submit" name="export_csv">📄 Export as CSV</button>
      <button type="submit" name="export_pdf">📘 Export as PDF</button>
    </form>

    <a href="index.php" class="back">← Back to Dashboard</a>
  </div>
</body>
</html>
