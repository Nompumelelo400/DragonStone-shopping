<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

include __DIR__ . '/../../config/db_connect.php';

// Check if product ID is provided
if (!isset($_GET['id'])) {
    die("Product ID not specified.");
}

$product_id = intval($_GET['id']);

// Confirm product exists before deleting
$check = $conn->query("SELECT * FROM products WHERE product_id = $product_id");

if ($check->num_rows == 0) {
    die("Product not found.");
}

// Delete product
$delete_sql = "DELETE FROM products WHERE product_id = $product_id";

if ($conn->query($delete_sql) === TRUE) {
    echo "<script>alert('🗑️ Product deleted successfully!'); window.location='index.php';</script>";
} else {
    echo "<script>alert('❌ Error deleting product: " . $conn->error . "'); window.location='index.php';</script>";
}
?>
