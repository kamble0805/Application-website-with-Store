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

// Handle adding items to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['package_id'])) {
    $packageId = intval($_POST['package_id']); // Ensure the package ID is an integer
    if (!in_array($packageId, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $packageId; // Add the package ID to the cart
    }
}

// Handle item deletion from the cart
if (isset($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']); // Ensure the delete ID is an integer
    // Remove the item from the cart
    $_SESSION['cart'] = array_diff($_SESSION['cart'], [$deleteId]);
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
            background-size: cover;
            color: black; /* Change text color for visibility */
        }
        .navbar {
            background-color: rgba(0, 0, 0, 0.8); 
        }
        .navbar .nav-link {
            color: #fff; /* Navbar link color */
        }
        .container {
            margin-top: 20px;
        }
        .card {
            margin-bottom: 15px;
            border: 1px solid rgba(255, 255, 255, 0.3); /* Light border */
            border-radius: 8px;
            transition: transform 0.2s;
            background-color: rgba(255, 255, 255, 0.1); /* Slightly transparent background */
        }
        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        .btn-container {
            margin-top: 20px;
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
                <div class="btn-container">
                    <a href="priorities.php" class="btn btn-primary">View Priority Packages</a>
                    <a href="memberships.php" class="btn btn-secondary">View Membership Packages</a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($cartProducts as $product): ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                            <p class="card-text">Price: ₹<?php echo htmlspecialchars($product['price']); ?></p>
                            <a href="?delete_id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-danger">Remove</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="col-12 btn-container">
                <form action="checkout.php" method="POST">
                    <button type="submit" class="btn btn-success">Checkout</button>
                </form>
                <div class="btn-container">
                    <a href="priorities.php" class="btn btn-primary">View Priority Packages</a>
                    <a href="memberships.php" class="btn btn-secondary">View Membership Packages</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
