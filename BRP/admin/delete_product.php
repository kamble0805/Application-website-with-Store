<?php
// Include the database connection
require 'db_connection.php';

// Start the session
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login page if not logged in or not an admin
    exit();
}

// Check if product_id is provided for deletion
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Prepare and execute the delete query
    $delete_query = "DELETE FROM products WHERE id = '" . mysqli_real_escape_string($conn, $product_id) . "'";

    if (mysqli_query($conn, $delete_query)) {
        // Redirect back to manage products page on success
        header("Location: manage_products.php?message=Product deleted successfully");
        exit();
    } else {
        die("Error deleting product: " . mysqli_error($conn));
    }
} else {
    // Redirect if no product_id was provided
    header("Location: manage_products.php?error=No product ID provided");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
