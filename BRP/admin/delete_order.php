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

// Check if the order ID is set
if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Prepare the delete query
    $delete_query = "DELETE FROM orders WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $order_id);

    // Execute the delete query
    if ($stmt->execute()) {
        // Redirect back to manage orders page after successful deletion
        header("Location: manage_orders.php"); // Redirect without a message
        exit();
    } else {
        // If there's an error, display it
        die("Error deleting order: " . $conn->error);
    }

} else {
    die("Order ID not provided.");
}

// Close the database connection
mysqli_close($conn);
?>
