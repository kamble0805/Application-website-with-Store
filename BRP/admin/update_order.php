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

    // Fetch the current order details
    $query = "SELECT * FROM orders WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    // Check if the order exists
    if (!$order) {
        die("Order not found.");
    }
} else {
    die("Order ID not specified.");
}

// Handle the update request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $new_status = $_POST['status'];

    // Update the order status
    $update_query = "UPDATE orders SET status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("si", $new_status, $order_id);

    if ($update_stmt->execute()) {
        // Redirect back to manage orders page after updating
        header("Location: manage_order.php");
        exit();
    } else {
        die("Error updating order: " . $conn->error);
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
    <title>Update Order</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Light background color */
        }
        nav {
            background-color: #007bff; /* Bootstrap primary color */
        }
        .navbar-brand {
            color: #fff !important; /* Navbar brand text color */
        }
        .navbar-nav .nav-link {
            color: #fff !important; /* Navbar link color */
        }
        .container {
            margin-top: 20px; /* Add margin to the container */
        }
        h5 {
            font-weight: bold; /* Bold for headings */
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <button class="navbar-brand btn btn-link" onclick="window.location.href='admin_dashboard.php'" style="text-decoration: none;">
            Admin Dashboard
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
    <h5>Update Order - ID: <?php echo htmlspecialchars($order['id']); ?></h5>
    <form action="" method="POST">
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="Pending" <?php echo ($order['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="Completed" <?php echo ($order['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                <option value="Canceled" <?php echo ($order['status'] == 'Canceled') ? 'selected' : ''; ?>>Canceled</option>
                <option value="In Progress" <?php echo ($order['status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
            </select>
        </div>
        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
        <button type="submit" name="update" class="btn btn-primary">Update Order</button>
        <a href="manage_orders.php" class="btn btn-secondary">Cancel</a> <!-- Cancel button -->
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
