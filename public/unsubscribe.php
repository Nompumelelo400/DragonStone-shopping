<?php
session_start();
include __DIR__ . '/../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM subscriptions WHERE subscription_id=$id");
    echo "<script>alert('Subscription cancelled successfully.'); window.location='subscriptions.php';</script>";
}
?>
