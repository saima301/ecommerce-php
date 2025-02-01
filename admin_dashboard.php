<?php
include "db.php";
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}

echo "<h1>Admin Dashboard</h1>";
echo "<a href='add_product.php'>➕ Add New Product</a> | <a href='logout.php'>🚪 Logout</a>";
echo "<h2>All Products</h2>";

$result = $conn->query("SELECT * FROM products");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>
                <img src='{$row['image_path']}' alt='{$row['name']}' style='width:100px; height:100px;'><br>
                <h3>{$row['name']}</h3>
                <p>{$row['description']}</p>
                <p>₹{$row['price']}</p>
                <a href='edit_product.php?id={$row['id']}'>✏️ Edit</a> |
                <a href='delete_product.php?id={$row['id']}' onclick='return confirm(\"Are you sure?\");'>🗑 Delete</a>
              </div>";
    }
} else {
    echo "<p>No products found.</p>";
}

// Add section to view orders
echo "<h2>Orders</h2>";

$sql = "SELECT o.id, u.username, o.order_date, o.total_amount FROM orders o JOIN users u ON o.user_id = u.id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>
                Order ID: {$row['id']} | User: {$row['username']} | Date: {$row['order_date']} | Total: ₹{$row['total_amount']}
              </li>";
    }
    echo "</ul>";
} else {
    echo "<p>No orders found.</p>";
}
?>
