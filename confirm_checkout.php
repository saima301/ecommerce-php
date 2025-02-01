<?php
include "db.php";
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$total_price = 0;
$order_items = [];

// Step 1: Calculate the total price and collect cart items
$sql = "SELECT p.id AS product_id, p.name, p.price, c.quantity 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $total_price += $row['price'] * $row['quantity'];
        $order_items[] = $row;
    }
} else {
    echo "<p>Your cart is empty!</p>";
    exit();
}

// Step 2: Insert the order into the `orders` table
$order_date = date('Y-m-d H:i:s');
$sql = "INSERT INTO orders (user_id, order_date, total_amount) 
        VALUES ('$user_id', '$order_date', '$total_price')";
if ($conn->query($sql) === TRUE) {
    $order_id = $conn->insert_id;  // Get the last inserted order ID
    
    // Step 3: Insert each product from the cart into the `order_items` table
    foreach ($order_items as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        $price = $item['price'];
        
        $sql_item = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                     VALUES ('$order_id', '$product_id', '$quantity', '$price')";
        $conn->query($sql_item);
    }

    // Step 4: Clear the user's cart
    $sql_clear_cart = "DELETE FROM cart WHERE user_id = '$user_id'";
    $conn->query($sql_clear_cart);

    // Step 5: Display order confirmation
    echo "<h1>Order Confirmed!</h1>";
    echo "<p>Your order has been successfully placed. Order ID: $order_id</p>";
    echo "<a href='dashboard.php'>Go to Dashboard</a>";
} else {
    echo "Error: " . $conn->error;
}
?>
