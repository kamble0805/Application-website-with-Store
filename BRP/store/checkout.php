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

// Initialize the cart session variable if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Fetch products for the cart display
$cartProducts = [];
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', $_SESSION['cart']);
    $query = "SELECT id, name, description, price FROM products WHERE id IN ($ids)";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        die("Database query failed: " . mysqli_error($conn));
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $cartProducts[] = $row;
    }
}

// Handle the checkout process
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    // Store cart details in the session to process in the payment page
    $_SESSION['checkout_order'] = $_SESSION['cart'];
    
    // Redirect to payment page
    header("Location: make_payment.php");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            background: url('background.jpg') no-repeat center center fixed; /* Replace with your image URL */
            background-size: cover; /* Cover the entire background */
            color: black; /* Text color */
        }
        .navbar {
            background-color: rgba(0, 0, 0, 0.8); /* Semi-transparent navbar */
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: #fff !important; /* Navbar text color */
        }
        .container {
            margin-top: 20px; /* Top margin for container */
        }
        .card {
            margin-bottom: 20px; /* Space between cards */
            border: 1px solid rgba(255, 255, 255, 0.3); /* Light border */
            border-radius: 8px; /* Rounded corners */
            transition: transform 0.2s; /* Smooth transition */
            background-color: rgba(255, 255, 255, 0.1); /* Slightly transparent background */
        }
        .card:hover {
            transform: scale(1.02); /* Scale effect on hover */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); /* Shadow effect on hover */
        }
        .card-title {
            font-size: 1.25rem; /* Title font size */
            font-weight: bold; /* Bold title */
        }
        .card-text {
            font-size: 1rem; /* Regular text size */
            margin: 0; /* Remove default margin */
        }
        .btn-success {
            background-color: #28a745; /* Green button */
            border-color: #28a745; /* Match border color */
        }
        .btn-success:hover {
            background-color: #218838; /* Darker green on hover */
            border-color: #1e7e34; /* Darker border on hover */
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <button class="navbar-brand btn btn-link" onclick="window.location.href='store.php'" style="text-decoration: none; color: #fff;">
            Store
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="nav-link">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h5>Your Shopping Cart</h5>
    <div class="row">
        <?php if (empty($cartProducts)): ?>
            <div class="col-12">
                <p>Your cart is empty.</p>
            </div>
        <?php else: ?>
            <?php foreach ($cartProducts as $product): ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                            <p class="card-text">Price: ₹<?php echo htmlspecialchars($product['price']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <!-- Checkout button placed below the products -->
    <?php if (!empty($cartProducts)): ?>
        <div class="row">
            <div class="col-12 text-center">
                <form action="" method="POST">
                    <button type="submit" name="checkout" class="btn btn-success">Checkout</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
