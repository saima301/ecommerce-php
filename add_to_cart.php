<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$product_id = $_GET['id'];

// Check if the product is already in the cart
$sql = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // If the product is already in the cart, update the quantity
    $row = $result->fetch_assoc();
    $new_quantity = $row['quantity'] + 1;
    $update_sql = "UPDATE cart SET quantity = '$new_quantity' WHERE user_id = '$user_id' AND product_id = '$product_id'";
    $conn->query($update_sql);
} else {
    // If the product is not in the cart, add it
    $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', 1)";
    $conn->query($sql);
}

// Redirect back to the dashboard
header("Location: dashboard.php");
exit();
?>
