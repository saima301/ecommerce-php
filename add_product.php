<?php
include "db.php";
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];

    // Handle Image Upload
    $target_dir = "uploads/";  // Folder where images will be stored
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is a valid image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        die("Error: File is not an image.");
    }

    // Move uploaded file to the uploads directory
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Insert product into database
        $sql = "INSERT INTO products (name, description, price, image_path) 
                VALUES ('$name', '$description', '$price', '$target_file')";
        if ($conn->query($sql) === TRUE) {
            echo "Product added successfully.";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Error uploading image.";
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Product Name" required><br>
    <textarea name="description" placeholder="Description"></textarea><br>
    <input type="number" name="price" placeholder="Price" required><br>
    <input type="file" name="image" accept="image/*" required><br>
    <button type="submit">Add Product</button>
</form>
