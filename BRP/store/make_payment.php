<?php
session_start();

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Replace with your actual login page
    exit();
}

// Check if there are items in the checkout session
if (!isset($_SESSION['checkout_order']) || empty($_SESSION['checkout_order'])) {
    header("Location: checkout.php"); // Redirect to checkout if there are no items
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Payment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            background: url('background.jpg') no-repeat center center fixed; /* Replace with your image URL */
            background-size: cover; /* Cover the entire background */
            color: black; /* Text color */
        }
        nav {
            background-color: rgba(0, 0, 0, 0.8); /* Semi-transparent navbar */
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: #fff !important; /* Navbar text color */
        }
        .container {
            margin-top: 20px; /* Top margin for container */
        }
        h5 {
            font-weight: bold; /* Bold for headings */
            margin-bottom: 20px; /* Space below heading */
            color: white; /* Change heading color to white for visibility */
        }
        h6 {
            margin-top: 20px; /* Space above subheadings */
            color: black; /* Change subheading color for visibility */
        }
        .payment-option {
            border: 1px solid rgba(255, 255, 255, 0.3); /* Light border */
            border-radius: 8px; /* Rounded corners */
            padding: 15px; /* Padding inside the box */
            background-color: rgba(255, 255, 255, 0.1); /* Slightly transparent background */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Soft shadow */
            transition: transform 0.2s; /* Animation effect */
        }
        .payment-option:hover {
            transform: scale(1.05); /* Scale effect on hover */
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

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <button class="navbar-brand btn btn-link" onclick="window.location.href='store.php'" style="text-decoration: none;">
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
    <h5>Choose Payment Method</h5>
    <div class="row">
        <div class="col-md-6 payment-option">
            <h6>Card Payment</h6>
            <form action="payment_form.php" method="POST"> <!-- Change to payment_form.php -->
                <input type="hidden" name="payment_method" value="card">
                <button type="submit" class="btn btn-primary">Pay with Credit Card</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
