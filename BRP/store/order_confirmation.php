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

// Get the order total from the query string
$orderTotal = isset($_GET['total']) ? $_GET['total'] : 0;

// Retrieve the purchased products from the orders table for the logged-in user
$username = $_SESSION['username'];
$query = "SELECT product_id, product_description, price FROM orders WHERE username = ?";

// Prepare and execute the statement
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check for results
$purchasedProducts = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $purchasedProducts[] = $row;
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        html, body {
            margin: 0; /* Remove default margin */
            padding: 0; /* Remove default padding */
            height: 100%; /* Ensure the body takes up the full height */
        }
        
        body {
            background-image: url('background.jpg'); /* Change to your background image path */
            background-size: cover; /* Cover the entire background */
            background-position: center; /* Center the background image */
            background-repeat: no-repeat; /* Prevent the background from repeating */
            color: #fff; /* Set text color to white */
        }
        
        .container {
            background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent black background for contrast */
            border-radius: 10px; /* Rounded corners */
            padding: 20px; /* Reduced padding */
            margin-top: 50px; /* Space above the container */
            max-width: 600px; /* Set a maximum width for the container */
            margin-left: auto; /* Center the container horizontally */
            margin-right: auto; /* Center the container horizontally */
        }

        h5, h6 {
            margin-bottom: 20px; /* Space below headings */
        }

        .list-group-item {
            background-color: rgba(255, 255, 255, 0.1); /* Light background for list items */
            border: 1px solid rgba(255, 255, 255, 0.2); /* Border with slight transparency */
            color: #fff; /* Set text color to white */
            transition: background-color 0.3s, transform 0.3s; /* Smooth transition for hover effects */
        }

        .list-group-item:hover {
            background-color: rgba(255, 255, 255, 0.2); /* Highlight background on hover */
            transform: scale(1.02); /* Slightly increase size on hover */
        }

        .product-price {
            font-weight: bold; /* Make price bold */
            color: #ffcc00; /* Change price color */
        }

        .btn-primary {
            background-color: #007bff; /* Bootstrap primary button color */
            border-color: #007bff; /* Match border color */
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Darker blue on hover */
            border-color: #0056b3; /* Darker border on hover */
        }
    </style>
</head>
<body>

<div class="container">
    <h5>Order Confirmation</h5>
    <p>Thank you for your order! Your order has been placed successfully.</p>
    <p>Your total amount is: ₹<?php echo htmlspecialchars($orderTotal); ?></p>
    
    <h6>Purchased Products:</h6>
    <ul class="list-group">
        <?php if (!empty($purchasedProducts)): ?>
            <?php foreach ($purchasedProducts as $product): ?>
                <li class="list-group-item">
                    Product ID: <?php echo htmlspecialchars($product['product_id']); ?> - 
                    Description: <?php echo htmlspecialchars($product['product_description']); ?> - 
                    <span class="product-price">Price: ₹<?php echo htmlspecialchars($product['price']); ?></span>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li class="list-group-item">No products found.</li>
        <?php endif; ?>
    </ul>
    
    <a href="store.php" class="btn btn-primary">Continue Shopping</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
