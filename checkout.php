<?php
include "db.php";
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
echo "<h1>Checkout</h1>";

$sql = "SELECT p.id AS product_id, p.name, p.price, c.quantity 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = '$user_id'";
$result = $conn->query($sql);

$total_price = 0;

if ($result->num_rows > 0) {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        $total_price += $row['price'] * $row['quantity'];
        echo "<li>
                {$row['name']} - ₹{$row['price']} x {$row['quantity']}
              </li>";
    }
    echo "</ul>";
    echo "<h3>Total: ₹{$total_price}</h3>";
    echo "<a href='confirm_checkout.php'>Confirm Order</a>";
} else {
    echo "<p>Your cart is empty!</p>";
}
?>
