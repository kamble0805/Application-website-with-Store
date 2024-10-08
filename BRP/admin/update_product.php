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

// Fetch product details if product_id is provided
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    $query = "SELECT * FROM products WHERE id = '" . mysqli_real_escape_string($conn, $product_id) . "'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($conn));
    }

    $product = mysqli_fetch_assoc($result);
    if (!$product) {
        die("Product not found.");
    }
} else {
    die("Product ID is not provided.");
}

// Update product details if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    $update_query = "UPDATE products SET name='$name', description='$description', price='$price', category='$category' WHERE id='$product_id'";

    if (mysqli_query($conn, $update_query)) {
        header("Location: manage_products.php"); // Redirect back to manage products
        exit();
    } else {
        die("Database update failed: " . mysqli_error($conn));
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
    <title>Update Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Light background color */
        }
        .container {
            margin-top: 30px; /* Add margin to the container */
            padding: 20px;
            border-radius: 5px;
            background-color: #ffffff; /* White background for the form */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Subtle shadow effect */
        }
        h5 {
            font-weight: bold; /* Bold for headings */
            margin-bottom: 20px; /* Space below the heading */
        }
        .btn-primary, .btn-secondary {
            margin-right: 10px; /* Space between buttons */
        }
        .btn-outline-secondary {
            margin-top: 15px; /* Space above the back button */
        }
        label {
            font-weight: 600; /* Bold for labels */
        }
    </style>
</head>
<body>

<div class="container">
    <h5>Update Product</h5>

    <form action="" method="POST">
        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
        
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>
        
        <div class="mb-3">
            <label for="price" class="form-label">Price (in ₹)</label>
            <input type="number" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <input type="text" class="form-control" id="category" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required>
        </div>
        
        <button type="submit" name="update" class="btn btn-primary">Update Product</button>
        <a href="manage_products.php" class="btn btn-secondary">Cancel</a>
    </form>

    <!-- Back to Manage Products Button -->
    <div class="mt-3">
        <a href="manage_products.php" class="btn btn-outline-secondary">Back to Manage Products</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
