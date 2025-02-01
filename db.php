<?php
$host = "localhost";
$user = "root"; // Default user for XAMPP
$pass = ""; // Leave empty for XAMPP
$dbname = "ecommerce_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
