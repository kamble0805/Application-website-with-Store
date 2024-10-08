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

if (isset($_POST['product_id'])) {
    // Check if there is already an item in the cart
    if (isset($_SESSION['checkout_order']) && !empty($_SESSION['checkout_order'])) {
        echo "You can only purchase one item at a time.";
        exit();
    }

    // If the cart is empty, add the product
    $_SESSION['checkout_order'] = $_POST['product_id'];
    // Redirect or display success message
    header("Location: checkout.php");
    exit();
}

// Fetch membership packages from the database
$query = "SELECT id, name, description, price FROM products WHERE category = 'memberships'"; // Adjust this query as necessary
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

$membershipPackages = [];
while ($row = mysqli_fetch_assoc($result)) {
    $membershipPackages[] = $row;
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memberships</title>
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
            margin-top: 20px; /* Space above the button */
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
                    <a class="nav-link" href="cart.php"><i class="bi bi-cart-fill"></i> Cart</a>
                </li>
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
    <h5>Membership Packages</h5>
    <div class="row">
        <?php if (empty($membershipPackages)): ?>
            <div class="col-12">
                <p>No membership packages available.</p>
            </div>
        <?php else: ?>
            <?php foreach ($membershipPackages as $package): ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($package['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($package['description']); ?></p>
                            <p class="card-text">Price: ₹<?php echo htmlspecialchars($package['price']); ?></p>
                            <form action="cart.php" method="POST">
                                <input type="hidden" name="package_id" value="<?php echo $package['id']; ?>">
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Button to redirect to priorities.php -->
    <div class="btn-container">
        <a href="priorities.php" class="btn btn-secondary">View Priority Packages</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
