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

// Get selected category for filtering
$selected_category = isset($_GET['category']) ? $_GET['category'] : '';

// Fetch categories from the database
$category_query = "SELECT DISTINCT category FROM products"; // Assuming 'category' is a column in the products table
$category_result = mysqli_query($conn, $category_query);

if (!$category_result) {
    die("Database query failed: " . mysqli_error($conn));
}

// Fetch products from the database with optional filtering
$product_query = "SELECT * FROM products";
if ($selected_category) {
    $product_query .= " WHERE category = '" . mysqli_real_escape_string($conn, $selected_category) . "'"; // Prevent SQL injection
}
$result = mysqli_query($conn, $product_query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
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
    <h5>Manage Products</h5>

    <!-- Button to go back to admin dashboard -->
    <button class="btn btn-secondary mb-3" onclick="window.location.href='admin_dashboard.php'">
        Back to Admin Dashboard
    </button>

    <!-- Filter by Category -->
    <form action="" method="GET" class="mb-3">
        <label for="category" class="form-label">Filter by Category:</label>
        <select name="category" id="category" class="form-select" onchange="this.form.submit()">
            <option value="">All Categories</option>
            <?php while ($category_row = mysqli_fetch_assoc($category_result)): ?>
                <option value="<?php echo htmlspecialchars($category_row['category']); ?>" <?php echo ($selected_category == $category_row['category']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category_row['category']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Category</th> <!-- New Category Column -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr>
                    <td colspan="6" class="text-center">No products found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['id']); ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                        <td>₹<?php echo htmlspecialchars($product['price']); ?></td>
                        <td><?php echo htmlspecialchars($product['category']); ?></td> <!-- Display Category -->
                        <td>
                            <form action="update_product.php" method="POST" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="btn btn-warning btn-sm">Update</button>
                            </form>
                            <form action="delete_product.php" method="POST" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
