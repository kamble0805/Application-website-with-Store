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

// Retrieve the payment method from the previous form
$paymentMethod = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';

// Ensure payment method is valid
if ($paymentMethod !== 'card' && $paymentMethod !== 'upi') {
    header("Location: make_payment.php"); // Redirect to payment options if invalid
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
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
        /* Transparent navbar styles */
        .navbar {
            background-color: transparent !important; /* Completely transparent background */
            width: 100%; /* Ensure navbar covers the full width */
        }
        .navbar-nav .nav-link {
            color: white; /* White text for nav links */
        }
        .container {
            background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent black background for contrast */
            border-radius: 10px; /* Rounded corners */
            padding: 20px; /* Reduced padding */
            margin-top: 50px; /* Space above the container */
            max-width: 400px; /* Set a maximum width for the card */
            margin-left: auto; /* Center the card horizontally */
            margin-right: auto; /* Center the card horizontally */
        }
        h5 {
            margin-bottom: 20px; /* Space below heading */
        }
        .form-label {
            color: #ffffff; /* White label color */
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
    <script>
        // JavaScript function to validate card details
        function validateCardForm() {
            const cardNumber = document.getElementById("cardNumber").value;
            const expiryDate = document.getElementById("expiryDate").value;
            const cvv = document.getElementById("cvv").value;

            const cardNumberRegex = /^\d{12}$/; // 12-digit card number
            const expiryDateRegex = /^(0[1-9]|1[0-2])\/\d{2}$/; // mm/yy format
            const cvvRegex = /^\d{3}$/; // 3-digit CVV

            if (!cardNumberRegex.test(cardNumber)) {
                alert("Card number must be 12 digits.");
                return false;
            }
            if (!expiryDateRegex.test(expiryDate)) {
                alert("Expiry date must be in mm/yy format.");
                return false;
            }
            if (!cvvRegex.test(cvv)) {
                alert("CVV must be 3 digits.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid"> <!-- Changed to container-fluid to make it full width -->
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
    <h5>Enter Your Card Details</h5>
    <form action="process_payment.php" method="POST" onsubmit="return validateCardForm();">
        <input type="hidden" name="payment_method" value="<?php echo htmlspecialchars($paymentMethod); ?>">
        
        <div class="mb-3">
            <label for="cardNumber" class="form-label">Card Number</label>
            <input type="text" class="form-control" id="cardNumber" name="card_number" required>
        </div>
        
        <div class="mb-3">
            <label for="expiryDate" class="form-label">Expiry Date (mm/yy)</label>
            <input type="text" class="form-control" id="expiryDate" name="expiry_date" placeholder="MM/YY" required>
        </div>
        
        <div class="mb-3">
            <label for="cvv" class="form-label">CVV</label>
            <input type="text" class="form-control" id="cvv" name="cvv" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Submit Payment</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
