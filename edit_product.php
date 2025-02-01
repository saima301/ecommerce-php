<?php
include "db.php";
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}

$product_id = $_GET['id']; // Get the product ID from the URL

// Fetch the product details from the database
$sql = "SELECT * FROM products WHERE id = '$product_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "Product not found.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the updated details from the form
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];

    // Handle Image Upload (optional - only if new image is provided)
    $target_file = $product['image_path']; // Default to the current image path
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is a valid image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            die("Error: File is not an image.");
        }

        // Move uploaded file to the uploads directory
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "Error uploading image.";
        }
    }

    // Update product in the database
    $sql = "UPDATE products SET name = '$name', description = '$description', price = '$price', image_path = '$target_file' WHERE id = '$product_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Product updated successfully.";
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<h1>Edit Product</h1>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" value="<?php echo $product['name']; ?>" required><br>
    <textarea name="description" required><?php echo $product['description']; ?></textarea><br>
    <input type="number" name="price" value="<?php echo $product['price']; ?>" required><br>
    <input type="file" name="image" accept="image/*"><br> <!-- Optional to update the image -->
    <button type="submit">Update Product</button>
</form>
