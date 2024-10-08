<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // Redirect to login if not logged in
    exit();
}

// Include database connection
require_once 'db_connection.php'; // Ensure the path is correct

// Initialize user counts
$total_ems_users = 0;
$total_e_department_users = 0;
$total_admin_users = 0;

// Query to count users by role
$roles = ['ems', 'e_department', 'admin'];

foreach ($roles as $role) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE role = ?");
    $stmt->bind_param("s", $role);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();

    // Assign the count to the correct variable based on the role
    if ($role === 'ems') {
        $total_ems_users = $count;
    } elseif ($role === 'e_department') {
        $total_e_department_users = $count;
    } elseif ($role === 'admin') {
        $total_admin_users = $count;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMS Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f1f1;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            color: white;
        }

        .navbar .btn {
            color: white;
            border-color: white;
        }

        .navbar .btn:hover {
            background-color: #dc3545;
            color: white;
        }

        .container {
            margin-top: 60px;
        }

        h1 {
            color: #343a40;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .card {
            border: 2px solid black;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-10px);
        }

        .card-title {
            color: #007bff;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .card-text {
            font-size: 2rem;
            color: #495057;
            font-weight: bold;
        }

        footer {
            margin-top: 50px;
            text-align: center;
            color: #6c757d;
            padding: 15px 0;
        }

        footer p {
            margin: 0;
        }

        /* Sliding Menu Styles */
        #sideMenu {
            position: fixed;
            width: 250px;
            height: 100%;
            background-color: #343a40;
            left: -250px;
            top: 0;
            transition: 0.3s;
            z-index: 1000;
            padding-top: 60px;
        }

        #sideMenu a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 1.2rem;
            color: white;
            display: block;
        }

        #sideMenu a:hover {
            background-color: #007bff;
        }

        #menuToggle {
            font-size: 2rem;
            color: white;
            cursor: pointer;
        }

        #menuClose {
            font-size: 2rem;
            color: white;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 15px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <span id="menuToggle" class="navbar-toggler-icon">&#9776;</span>
        <a class="navbar-brand" href="#">Community Hub</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <!-- Other navigation items -->
            </ul>
            <form class="d-flex">
                <a href="logout.php" class="btn btn-outline-danger">Logout</a>
            </form>
        </div>
    </div>
</nav>

<!-- Sliding Menu -->
<div id="sideMenu">
    <span id="menuClose">&times;</span>
    <a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a>
    <a href="manage_application.php"><i class="fas fa-cogs"></i> Manage Application</a>
</div>

<div class="container">
    <h1 class="text-center">EMS Dashboard</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total EMS Users</h5>
                    <p class="card-text"><?php echo $total_ems_users; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total E Department Users</h5>
                    <p class="card-text"><?php echo $total_e_department_users; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Admin Users</h5>
                    <p class="card-text"><?php echo $total_admin_users; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<footer>
    <div class="container">
        <p>&copy; 2024 Community Hub. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

<script>
    // Show side menu
    document.getElementById("menuToggle").addEventListener("click", function() {
        document.getElementById("sideMenu").style.left = "0";
    });

    // Close side menu
    document.getElementById("menuClose").addEventListener("click", function() {
        document.getElementById("sideMenu").style.left = "-250px";
    });
</script>

</body>
</html>
