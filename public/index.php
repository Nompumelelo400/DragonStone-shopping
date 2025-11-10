<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DragonStone | Sustainable Living</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    /* GENERAL STYLING */
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #f5f8f5;
      color: #222;
    }

    a { text-decoration: none; color: inherit; }

    /* NAVBAR */
    nav {
      background: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 40px;
      background: #fff
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      
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
    }

    .nav-links a:hover {
      color: #2e7d32;
    }

    /* HERO SECTION */
    .hero {
      background: url('images/background.jpeg') center/cover no-repeat;
      color: white;
      text-align: center;
      padding: 120px 20px;
      position: relative;
    }

    .hero::after {
      content: '';
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.4);
      z-index: 1;
    }

    .hero-content {
      position: relative;
      z-index: 2;
      max-width: 800px;
      margin: auto;
    }

    .hero h1 {
      font-size: 48px;
      line-height: 1.2;
      margin-bottom: 15px;
    }

    .hero p {
      font-size: 18px;
      margin-bottom: 25px;
    }

    .btn {
      background: #2e7d32;
      color: white;
      padding: 12px 25px;
      border-radius: 6px;
      margin: 5px;
      display: inline-block;
      font-weight: 600;
      transition: background 0.3s ease;
    }

    .btn:hover {
      background: #1b5e20;
    }

    /* FEATURED PRODUCTS */
    .section-title {
      text-align: center;
      margin: 60px 0 30px;
      color: #2e7d32;
      font-size: 28px;
      font-weight: 600;
    }

    .product-grid {
      width: 90%;
      margin: auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 25px;
    }

    .product-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      overflow: hidden;
      text-align: center;
      transition: transform 0.2s;
    }

    .product-card:hover {
      transform: translateY(-5px);
    }

    .product-card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
    }

    .product-card h3 {
      color: #2e7d32;
      font-size: 18px;
      margin: 10px 0 5px;
    }

    .price {
      font-weight: bold;
      color: #1b5e20;
      margin-bottom: 10px;
    }

    /* FOOTER */
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
    <div class="logo">DragonStone</div>
    <div class="nav-links">
      <a href="./index.php">Home</a>
      <a href="./shop.php">Shop</a>
      <a href="./community.php">Community</a>
      <a href="./about.php">About</a>
      <a href="./contact.php">Contact</a>
      <a href="logout.php">Logout</a>
    </div>
  </nav>

  <section class="hero">
    <div class="hero-content">
      <h1>Live Sustainably, <br>Shop Consciously</h1>
      <p>Eco-friendly, stylish, and affordable home essentials for mindful living.</p>
      <a href="#" class="btn">Shop Collections</a>
      <a href="#" class="btn" style="background:#81c784;">Learn More</a>
    </div>
  </section>

  <h2 class="section-title">Featured Products</h2>
  <div class="product-grid">
    
    <!-- PHP Product Loop -->
    <?php
    // File: public/index.php
    // Purpose: This is the homepage displaying Dragonstone product highlights.
    // Author: Nompumelelo 


    // Include database connection
    include __DIR__ . '/../config/db_connect.php';

    // Fetch limited products for homepage display
    $sql = "SELECT product_id, name, description, price, image_url FROM products LIMIT 6";
    $result = $conn->query($sql);

    // Check if there are any products returned.
    if ($result->num_rows > 0) {
      // Loop through and display each product.
      while($row = $result->fetch_assoc()) {
        echo "
        <div class='product-card'>
          <a href='product.php?id=".$row['product_id']."'>
            <img src='".$row['image_url']."' alt='".$row['name']."'>
          </a>
            <h3>".$row['name']."</h3>
            <p>".$row['description']."</p>
            <p class='price'>R ".$row['price']."</p>
        </div>
        ";
      }
    } else {
      // If no products found, display a message.
      echo '<p style="text-align:center;">No products found.</p>';
    }
    ?>
  </div>

  <footer>
    <p>&copy; 2025 DragonStone. Sustainable Living for Everyone 🌿</p>
  </footer>

</body>
</html>
