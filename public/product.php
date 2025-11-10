<?php
// FIle: public/product.php
// Purpose: Display individual product details
// Author: Nompumelelo

include '../config/db_connect.php';

// Get product ID from URL (e.g., product.php?id=1)
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $sql = "SELECT * FROM products WHERE product_id = $product_id";
    $result = $conn->query($sql);
    $product = $result->fetch_assoc();
} else {
    echo "Product not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $product['name']; ?> | DragonStone</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f8f5;
      margin: 0;
      color: #222;
    }

    nav {
      background: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 5%;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }

    .logo {
      font-weight: 700;
      font-size: 24px;
      color: #2e7d32;
    }

    .nav-links a {
      margin: 0 15px;
      font-weight: 500;
      color: #333;
      text-decoration: none;
    }

    .nav-links a:hover {
      color: #2e7d32;
    }

    .container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      align-items: flex-start;
      gap: 40px;
      padding: 50px 5%;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      margin: 40px auto;
      max-width: 1100px;
    }

    .product-image {
      flex: 1;
      min-width: 320px;
    }

    .product-image img {
      width: 100%;
      border-radius: 12px;
    }

    .product-info {
      flex: 1;
      min-width: 300px;
    }

    .product-info h1 {
      color: #2e7d32;
      font-size: 28px;
      margin-bottom: 10px;
    }

    .price {
      font-size: 22px;
      font-weight: bold;
      color: #1b5e20;
      margin-bottom: 15px;
    }

    .desc {
      margin-bottom: 25px;
      line-height: 1.6;
      color: #555;
    }

    .btn {
      background: #2e7d32;
      color: white;
      border: none;
      padding: 12px 25px;
      border-radius: 6px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .btn:hover {
      background: #1b5e20;
    }

    footer {
      background: #2e7d32;
      color: white;
      text-align: center;
      padding: 25px;
      margin-top: 60px;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<nav>
  <div class="logo">🌱 DragonStone</div>
  <div class="nav-links">
    <a href="index.php">Home</a>
    <a href="#">Shop</a>
    <a href="#">Community</a>
    <a href="#">About</a>
    <a href="#">Contact</a>
  </div>
</nav>

<div class="container">
  <div class="product-image">
    <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
  </div>

  <div class="product-info">
    <h1><?php echo $product['name']; ?></h1>
    <p class="price">R <?php echo $product['price']; ?></p>
    <p class="desc"><?php echo $product['description']; ?></p>
    
    <!-- Add to Cart button -->
    <form action="cart.php" method="POST">
      <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
      <button type="submit" class="btn">Add to Cart</button>
    </form>
    <form method="POST" action="subscribe.php">
      <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
      <label for="frequency">Subscription:</label>
      <select name="frequency" required>
        <option value="Weekly">Weekly</option>
        <option value="Monthly">Monthly</option>
      </select>
    <button type="submit" name="subscribe">Subscribe</button>
    </form>
  </div>
</div>

<footer>
  <p>&copy; 2025 DragonStone | Sustainable Living for Everyone 🌿</p>
</footer>

</body>
</html>
