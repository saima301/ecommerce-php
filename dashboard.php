<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch the number of items in the cart
$sql = "SELECT SUM(quantity) AS cart_count FROM cart WHERE user_id = '$user_id'";
$result = $conn->query($sql);
$cart_count = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $cart_count = $row['cart_count'];
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<h1>Welcome to your Dashboard</h1>

<!-- Cart Summary with link to Cart page -->
<div>
    <p>Items in Cart: <?php echo $cart_count; ?></p>
    <a href="cart.php">Go to Cart</a>
</div>

<!-- Display products with images and Add to Cart -->
<div class="products">
    <?php
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($product = $result->fetch_assoc()) {
            echo "<div class='product'>";
            echo "<img src='" . $product['image_path'] . "' alt='" . $product['name'] . "' style='width: 100px; height: 100px;'>";
            echo "<h3>" . $product['name'] . "</h3>";
            echo "<p>" . $product['description'] . "</p>";
            echo "<p>Price: ₹" . $product['price'] . "</p>";
            echo "<a href='add_to_cart.php?id=" . $product['id'] . "'>Add to Cart</a>";
            echo "</div>";
        }
    }
    ?>
</div>

<!-- Logout Button -->
<div>
    <a href="?logout=true">Logout</a>
</div>
