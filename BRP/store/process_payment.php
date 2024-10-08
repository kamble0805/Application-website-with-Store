<?php
// Include the database connection
require 'db_connection.php';

// Start the session
session_start();

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Replace with your actual login page
    exit();
}

// Check if checkout order exists
if (!isset($_SESSION['checkout_order']) || empty($_SESSION['checkout_order'])) {
    header("Location: checkout.php");
    exit();
}

// Initialize total amount
$orderTotal = 0;

// Process the payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_method'])) {
    $username = $_SESSION['username'];
    $paymentMethod = $_POST['payment_method'];

    // Insert orders into the database
    $orderIds = []; // Store order IDs for updating later
    foreach ($_SESSION['checkout_order'] as $packageId) {
        // Fetch product details
        $packageQuery = "SELECT name, price, description FROM products WHERE id = ?";
        $stmt = mysqli_prepare($conn, $packageQuery);
        mysqli_stmt_bind_param($stmt, "i", $packageId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $product = mysqli_fetch_assoc($result);
            $orderTotal += $product['price']; // Calculate total amount

            // Insert order details into orders table with initial status as 'pending'
            $insertOrderQuery = "INSERT INTO orders (username, product_id, product_description, price, status) VALUES (?, ?, ?, ?, 'pending')";
            $insertStmt = mysqli_prepare($conn, $insertOrderQuery);
            mysqli_stmt_bind_param($insertStmt, "siis", $username, $packageId, $product['description'], $product['price']);
            mysqli_stmt_execute($insertStmt);
            
            // Store the last inserted order ID to update later
            $orderIds[] = mysqli_insert_id($conn); // Get the last inserted ID
        }
    }

    // Clear the cart and checkout session after processing
    $_SESSION['cart'] = [];
    unset($_SESSION['checkout_order']);

    // Simulate payment processing logic (You would integrate your payment gateway here)
    if ($paymentMethod == 'card') {
        // Simulate card payment processing
        $paymentStatus = "success"; // Assume payment successful
    } else {
        // Handle invalid payment method
        $paymentStatus = "failure";
    }

    // If payment was successful, update the order status to 'completed'
    if ($paymentStatus === "success") {
        foreach ($orderIds as $orderId) {
            $updateStatusQuery = "UPDATE orders SET status = 'completed' WHERE id = ?";
            $updateStmt = mysqli_prepare($conn, $updateStatusQuery);
            mysqli_stmt_bind_param($updateStmt, "i", $orderId);
            mysqli_stmt_execute($updateStmt);
        }

        // Redirect to order confirmation page
        header("Location: order_confirmation.php?total=" . urlencode($orderTotal));
    } else {
        // Handle payment failure
        echo "<h3>Payment failed. Please try again.</h3>";
        echo '<a href="checkout.php" class="btn btn-danger">Back to Checkout</a>';
    }
    
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
