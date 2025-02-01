<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch cart details
$sql = "SELECT cart.quantity, products.name, products.price, products.image_path FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = '$user_id'";
$result = $conn->query($sql);

?>

<h1>Your Shopping Cart</h1>

<div>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_amount = 0;
            if ($result->num_rows > 0) {
                while ($cart_item = $result->fetch_assoc()) {
                    $total = $cart_item['price'] * $cart_item['quantity'];
                    $total_amount += $total;
                    echo "<tr>";
                    echo "<td><img src='" . $cart_item['image_path'] . "' alt='" . $cart_item['name'] . "' style='width: 50px; height: 50px;'> " . $cart_item['name'] . "</td>";
                    echo "<td>₹" . $cart_item['price'] . "</td>";
                    echo "<td>" . $cart_item['quantity'] . "</td>";
                    echo "<td>₹" . $total . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Your cart is empty.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <h3>Total: ₹<?php echo $total_amount; ?></h3>
    <a href="checkout.php">Proceed to Checkout</a>
</div>
