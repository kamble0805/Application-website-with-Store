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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store</title>
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
        .sidebar {
            background-color: rgba(0, 0, 0, 0.0); /* Darker sidebar */
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
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
        .category-card {
            background-color: rgba(255, 255, 255, 0.2); /* Slightly more opaque for category cards */
            border: 1px solid rgba(255, 255, 255, 0.4);
            transition: transform 0.2s;
        }
        .category-card:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
        }
        .info-card {
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <button class="navbar-brand btn btn-link" onclick="window.location.href='../index.php'" style="text-decoration: none; color: #fff;">
            Home
        </button>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/store/cart.php"><i class="bi bi-cart-fill"></i> Cart</a>
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
    <div class="row">
        <div class="col-md-4 sidebar">
            <h5>Select a Category</h5>
            <div class="card category-card">
                <div class="card-body">
                    <button class="btn btn-primary btn-category" data-bs-toggle="modal" data-bs-target="#categoryModal">
                        Click Here to Select Category
                    </button>
                </div>
            </div>
            <div class="card contact-us">
                <div class="card-body">
                    <h6>Contact Us</h6>
                    <p>If you run into any issues whilst attempting to use our store, please contact us through Discord.</p>
                    <a href="https://discord.gg/your_discord_link" target="_blank" class="btn btn-info">Open a Ticket</a>
                </div>
            </div>
            <div class="card top-customers">
                <div class="card-body">
                    <h6>Top Customers of All Time</h6>
                    <ul>
                        <li>Customer 1</li>
                        <li>Customer 2</li>
                        <li>Customer 3</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <h5>Featured Packages</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="card featured-package">
                        <div class="card-body">
                            <h5 class="card-title">Gold Priority</h5>
                            <p class="card-text">Description of Gold Priority package.</p>
                            <p class="card-text">Price: ₹999</p>
                            <button class="btn btn-primary">Add to Cart</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card featured-package">
                        <div class="card-body">
                            <h5 class="card-title">Platinum Membership</h5>
                            <p class="card-text">Description of Platinum Membership.</p>
                            <p class="card-text">Price: ₹1499</p>
                            <button class="btn btn-primary">Add to Cart</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info mt-4 info-card">
                <h5>About the Store</h5>
                <p>Welcome to the official Soulcity by EchoRP Store.</p>
                <p>To begin shopping, please click the "Select a category" button on the sidebar. By supporting Soulcity by EchoRP, you allow us to maintain server costs, provide a better experience, and upgrade our infrastructure.</p>
                <p>For more information regarding Soulcity by EchoRP, please head to our <a href="your_support_link" target="_blank">Support</a>.</p>
                <p>Need any questions answered before checkout? Waited more than 20 minutes but your package still has not arrived? Ask the community/staff on Discord, or for payment support, submit a support ticket in our Discord.</p>
                <p>It could take between 1-20 minutes for your purchase to be credited in-game. If you are still not credited after this time period, please open a support ticket in our Discord with proof of purchase and we will look into your issue.</p>
                <a href="https://discord.gg/your_discord_link" target="_blank" class="btn btn-info">Join Discord Server</a>
            </div>
        </div>
    </div>
</div>

<!-- Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Select a Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <button class="btn btn-secondary w-100" onclick="window.location.href='memberships.php'">Memberships</button>
                <button class="btn btn-secondary w-100" onclick="window.location.href='priorities.php'">Priorities</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
