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

// Fetch product details
$product_query = $conn->query("SELECT * FROM products WHERE product_id = $product_id");

if ($product_query->num_rows == 0) {
    die("Product not found.");
}

$product = $product_query->fetch_assoc();

// Handle update submission
if (isset($_POST['update_product'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $category = $conn->real_escape_string($_POST['category']);
    $image_url = $conn->real_escape_string($_POST['image_url']);

    $update_sql = "UPDATE products 
                   SET name='$name', description='$description', price=$price, category='$category', image_url='$image_url' 
                   WHERE product_id=$product_id";

    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('✅ Product updated successfully!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('❌ Error updating product: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product | DragonStone Admin</title>
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
      max-width: 600px;
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

    form {
      display: flex;
      flex-direction: column;
    }

    label {
      margin-top: 10px;
      font-weight: 600;
      color: #333;
    }

    input, textarea {
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
    }

    button {
      background: #2e7d32;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 6px;
      margin-top: 20px;
      cursor: pointer;
      font-size: 16px;
      font-weight: 600;
      transition: background 0.3s ease;
    }

    button:hover {
      background: #1b5e20;
    }

    a.back {
      display: inline-block;
      margin-top: 15px;
      text-decoration: none;
      color: #2e7d32;
      font-weight: 500;
      text-align: center;
    }
  </style>
</head>
<body>

  <header>
    <h1>DragonStone Admin</h1>
  </header>

  <?php include 'admin_navbar.php'; ?>

  <div class="container">
    <h2>Edit Product</h2>
    <form method="POST">
      <label>Product Name</label>
      <input type="text" name="name" value="<?php echo $product['name']; ?>" required>

      <label>Description</label>
      <textarea name="description" rows="4" required><?php echo $product['description']; ?></textarea>

      <label>Price (R)</label>
      <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>

      <label>Category</label>
      <input type="text" name="category" value="<?php echo $product['category']; ?>" required>

      <label>Image URL (JPEG/PNG)</label>
      <input type="text" name="image_url" value="<?php echo $product['image_url']; ?>" required>

      <button type="submit" name="update_product">Update Product</button>
    </form>

    <a href="index.php" class="back">← Back to Dashboard</a>
  </div>

</body>
</html>
