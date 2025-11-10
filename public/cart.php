<?php
// File: public/cart.php
// Purpose: Displays all items added to the shopping cart
// Author: Nompumelelo

session_start();

if (isset($_POST['product_id'])) {
    echo "<pre>Product ID received: " . $_POST['product_id'] . "</pre>";
}

include '../config/db_connect.php';

// Add item to cart
if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    // Fetch product from DB
    $result = $conn->query("SELECT * FROM products WHERE product_id = $product_id");
    $product = $result->fetch_assoc();

    if ($product) {
        $item = [
            'id' => $product['product_id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image_url'],
            'quantity' => 1
        ];

        // Initialize session cart
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // If product already exists, increase quantity
        $found = false;
        foreach ($_SESSION['cart'] as &$cartItem) {
            if ($cartItem['id'] == $item['id']) {
                $cartItem['quantity']++;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = $item;
        }

        header("Location: cart.php");
        exit;
    }
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $remove_id) {
            unset($_SESSION['cart'][$key]);
        }
    }
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Cart | DragonStone</title>
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
      width: 80%;
      margin: 40px auto;
      background: white;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      padding: 20px;
    }

    h1 {
      color: #2e7d32;
      text-align: center;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      text-align: center;
      padding: 12px;
      border-bottom: 1px solid #ddd;
    }

    th {
      background: #e8f5e9;
      color: #2e7d32;
    }

    img {
      width: 70px;
      border-radius: 8px;
    }

    .btn {
      background: #2e7d32;
      color: white;
      padding: 8px 15px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 500;
    }

    .btn:hover {
      background: #1b5e20;
    }

    .total {
      text-align: right;
      margin-top: 20px;
      font-size: 18px;
      font-weight: bold;
      color: #2e7d32;
    }

    footer {
      background: #2e7d32;
      color: white;
      text-align: center;
      padding: 25px;
      margin-top: 60px;
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
  <h1>Your Cart</h1>
  <?php
  if (!empty($_SESSION['cart'])) {
      echo "<table>
              <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Remove</th>
              </tr>";
      $total = 0;
      foreach ($_SESSION['cart'] as $item) {
          $subtotal = $item['price'] * $item['quantity'];
          $total += $subtotal;
          echo "
            <tr>
              <td><img src='".$item['image']."' alt='".$item['name']."'></td>
              <td>".$item['name']."</td>
              <td>R ".$item['price']."</td>
              <td>".$item['quantity']."</td>
              <td><a href='cart.php?remove=".$item['id']."' class='btn'>Remove</a></td>
            </tr>
          ";
      }
      echo "</table>
            <div class='total'>Total: R ".number_format($total, 2)."</div>
            <div style='text-align:right; margin-top:20px;'>
              <a href='checkout.php' class='btn'>Proceed to Checkout</a>
            </div>";
  } else {
      echo "<p style='text-align:center;'>Your cart is empty.</p>";
  }
  ?>
</div>

<footer>
  <p>&copy; 2025 DragonStone | Sustainable Living for Everyone 🌿</p>
</footer>

</body>
</html>
