<?php
// This file will be included at the top of all admin pages
?>
<style>
  /* Navigation Bar Styling */
  .admin-navbar {
    background-color: #2e7d32;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
  }

  .admin-navbar h1 {
    margin: 0;
    font-size: 22px;
  }

  .admin-nav-links {
    display: flex;
    gap: 20px;
  }

  .admin-nav-links a {
    color: white;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s;
  }

  .admin-nav-links a:hover {
    text-decoration: underline;
  }

  .admin-navbar .logout {
    background-color: #b71c1c;
    padding: 6px 10px;
    border-radius: 4px;
  }

  .admin-navbar .logout:hover {
    background-color: #9a0007;
  }
</style>

<div class="admin-navbar">
  
  <div class="admin-nav-links">
    
    <a href="index.php">Dashboard</a>
    <a href="add_product.php">Add Product</a>
    <a href="subscriptions.php">Subscriptions</a>
    <a href="orders.php">Orders</a>
    <a href="reports.php">Reports</a>
    <a href="logout.php" class="logout">Logout</a>
  </div>
</div>
