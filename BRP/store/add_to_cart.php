<?php
session_start();

// Check if the cart session exists, if not, create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Fetch product information based on ID
$product_id = $_POST['product_id'];
require 'db_connection.php';

$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Add product to cart session
if ($product) {
    // Add product to the cart session with a default quantity of 1
    $_SESSION['cart'][$product_id] = [
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => isset($_SESSION['cart'][$product_id]) ? $_SESSION['cart'][$product_id]['quantity'] + 1 : 1
    ];
}

// Redirect back to the store page
header('Location: store.php');
exit();
