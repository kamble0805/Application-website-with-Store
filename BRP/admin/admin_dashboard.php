<?php
// Start session
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php"); // Redirect to login if not an admin
    exit();
}

// Include database connection
require_once 'db_connection.php'; // Ensure the path is correct

// Query to fetch users
$usersQuery = $conn->query("SELECT * FROM users");
$users = $usersQuery->fetch_all(MYSQLI_ASSOC);

// Get total users and admins
$totalUsers = count($users);
$totalAdmins = count(array_filter($users, function($user) {
    return $user['role'] === 'admin';
}));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: -250px;
            height: 100%;
            width: 250px;
            background: #343a40;
            color: white;
            transition: left 0.3s;
            z-index: 1000;
            padding-top: 20px;
        }
        .sidebar.active {
            left: 0;
        }
        .sidebar a {
            color: #ffffff;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            transition: background 0.3s;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .menu-toggle, .menu-close {
            cursor: pointer;
            font-size: 1.5rem;
            color: #007bff;
        }
        .container {
            margin-left: 15px; /* Avoid content being hidden behind sidebar */
        }
        h1, h4 {
            color: #343a40;
        }
        .stats {
    background: #ffffff; /* Background color for stats */
    padding: 20px; /* Padding around the stats box */
    border-radius: 8px; /* Rounded corners */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Light shadow */
    margin-top: 20px; /* Spacing above the stats box */
    transition: transform 0.3s; /* Animation for hover effect */
}
.stats:hover {
    transform: scale(1.02); /* Slightly enlarge when hovered */
}
.stat {
    padding: 15px; /* Padding within each stat box */
    margin: 10px 0; /* Margin between stat boxes */
    border: 2px solid #007bff; /* Blue border for the stat box */
    border-radius: 5px; /* Rounded corners for stat boxes */
    background: #f1f3f5; /* Light background for stat boxes */
    font-size: 1.2rem; /* Font size for stat text */
    color: #343a40; /* Text color */
    transition: background 0.3s; /* Animation for background change */
}
.stat:hover {
    background: #e9ecef; /* Change background on hover */
}

    </style>
</head>
<body>

<div class="sidebar" id="sidebar">
    <h2 class="text-center">Admin Menu</h2>
    <span class="menu-close" onclick="toggleSidebar()"><i class="fas fa-times"></i></span> <!-- Close Button -->
    <ul class="list-unstyled">
        <li><a href="admin_dashboard.php" class="text-white">Dashboard</a></li>
        <li><a href="manage_users.php" class="text-white">Manage Users</a></li>
        <li><a href="manage_application.php" class="text-white">Manage Applications</a></li>
        <li><a href="add_product.php" class="text-white">Add Products</a></li>
        <li><a href="manage_products.php" class="text-white">Manage Products</a></li>
        <li><a href="manage_orders.php" class="text-white">Manage Orders</a></li>
        <li><a href="logout.php" class="text-white">Logout</a></li>
    </ul>
</div>

<div class="container mt-5">
    <span class="menu-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></span> <!-- Menu Icon Above Welcome -->
    <h1>Welcome to Admin Dashboard</h1>
    <h4>Hello, <?php echo $_SESSION['username']; ?>!</h4>

    <h2 class="mt-4">Statistics</h2>
    <div class="stats">
        <div class="stat">Total Users: <strong><?php echo $totalUsers; ?></strong></div>
        <div class="stat">Total Admins: <strong><?php echo $totalAdmins; ?></strong></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('active');
    }
</script>
</body>
</html>
