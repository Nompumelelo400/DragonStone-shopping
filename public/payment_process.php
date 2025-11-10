<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../config/db_connect.php';

if (isset($_POST['pay_now'])) {
    $order_id = intval($_POST['order_id']);

    // Simulate payment validation (fake delay)
    sleep(2);

    // Simulate random payment result
    $status = (rand(1, 10) > 2) ? 'Paid' : 'Failed'; // 80% success rate

    // Update payment status in database
    $conn->query("UPDATE orders SET payment_status='$status' WHERE order_id=$order_id");

    if ($status == 'Paid') {
        $redirect_url = "success.php?order_id=$order_id";
    } else {
        $redirect_url = "payment.php?order_id=$order_id&failed=true";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Processing Payment | DragonStone</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f8f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      color: #2e7d32;
      flex-direction: column;
      text-align: center;
    }

    .spinner {
      border: 6px solid #c8e6c9;
      border-top: 6px solid #2e7d32;
      border-radius: 50%;
      width: 70px;
      height: 70px;
      animation: spin 1s linear infinite;
      margin-bottom: 20px;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .checkmark {
      display: none;
      font-size: 60px;
      color: #2e7d32;
      animation: pop 0.4s ease-in-out;
    }

    @keyframes pop {
      0% { transform: scale(0.5); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }

    .failed {
      color: red;
      font-size: 60px;
      display: none;
      animation: pop 0.4s ease-in-out;
    }

    h2 {
      font-weight: 600;
    }

    p {
      color: #555;
      margin-top: 10px;
    }

    .btn {
      display: inline-block;
      background: #2e7d32;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
      margin-top: 25px;
    }

    .btn:hover {
      background: #1b5e20;
    }

    .center-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  width: 100%;
  flex-direction: column;
  text-align: center;
}

.state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.spinner {
  border: 6px solid #c8e6c9;
  border-top: 6px solid #2e7d32;
  border-radius: 50%;
  width: 80px;
  height: 80px;
  animation: spin 1s linear infinite;
  margin-bottom: 25px;
}

  </style>
</head>
<body>

  <div class="center-wrapper">
    <div id="loading" class="state">
      <div class="spinner"></div>
      <h2>Processing your payment...</h2>
      <p>Please wait while we confirm your transaction.</p>
    </div>

    <div id="success" class="state" style="display:none;">
      <div class="checkmark">✅</div>
      <h2>Payment Successful!</h2>
      <p>Redirecting you to your order summary...</p>
    </div>

    <div id="failed" class="state" style="display:none;">
      <div class="failed">❌</div>
      <h2>Payment Failed</h2>
      <p>Something went wrong with your transaction.</p>
      <a href="payment.php?order_id=<?php echo $order_id; ?>" class="btn">Try Again</a>
    </div>
  </div>

  <script>
    const status = "<?php echo $status; ?>";
    const redirectUrl = "<?php echo $redirect_url; ?>";

    if (status === "Paid") {
      setTimeout(() => {
        document.getElementById("loading").style.display = "none";
        document.getElementById("success").style.display = "block";
      }, 2000);

      setTimeout(() => {
        window.location.href = redirectUrl;
      }, 5000);

    } else {
      setTimeout(() => {
        document.getElementById("loading").style.display = "none";
        document.getElementById("failed").style.display = "block";
      }, 2000);
    }
  </script>

</body>

