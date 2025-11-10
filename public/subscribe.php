<?php
session_start();
include __DIR__ . '/../config/db_connect.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to subscribe.'); window.location='login.php';</script>";
    exit;
}

if (isset($_POST['subscribe'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    $frequency = $conn->real_escape_string($_POST['frequency']);

    // Check if already subscribed
    $check = $conn->query("SELECT * FROM subscriptions WHERE user_id=$user_id AND product_id=$product_id");
    if ($check->num_rows > 0) {
        echo "<script>alert('You are already subscribed to this product.'); window.location='subscriptions.php';</script>";
        exit;
    }

    // Calculate next billing date
    $interval = ($frequency == 'Weekly') ? 7 : 30;
    $next_billing_date = date('Y-m-d', strtotime("+$interval days"));

    $sql = "INSERT INTO subscriptions (user_id, product_id, frequency, next_billing_date)
            VALUES ($user_id, $product_id, '$frequency', '$next_billing_date')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('✅ Subscription created successfully!'); window.location='subscriptions.php';</script>";
    } else {
        echo "<script>alert('❌ Error: " . $conn->error . "');</script>";
    }
}
?>
