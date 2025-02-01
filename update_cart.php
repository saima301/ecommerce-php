<?php
include "db.php";
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$product_id = $_GET["id"];
$new_quantity = $_GET["quantity"];

// Update quantity in the cart
$sql = "UPDATE cart SET quantity = '$new_quantity' WHERE user_id = '$user_id' AND product_id = '$product_id'";

if ($conn->query($sql) === TRUE) {
    header("Location: cart.php");
} else {
    echo "Error: " . $conn->error;
}
?>
