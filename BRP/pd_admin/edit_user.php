<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // Redirect to login if not logged in
    exit();
}

// Include database connection
require_once 'db_connection.php';

// Check if the ID is set in the URL
if (!isset($_GET['id'])) {
    header("Location: manage_users.php"); // Redirect to the manage users page if no ID is set
    exit();
}

$id = $_GET['id'];
$message = "";

// Fetch user details based on the provided ID
$sql = "SELECT id, username, email, role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // Redirect if no user is found with the given ID
    header("Location: manage_users.php");
    exit();
}

// Handle form submission for updating the user role only
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];

    // Update the user's role in the database
    $sql = "UPDATE users SET role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $role, $id);

    if ($stmt->execute()) {
        $message = "Role updated successfully!";
        // Refresh user role after update
        $user['role'] = $role;
    } else {
        $message = "Error updating role.";
    }
}

// Close the connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Role</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f1f1;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #343a40;
        }
        footer {
            margin-top: 50px;
            text-align: center;
            color: #6c757d;
            padding: 15px 0;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit User Role</h1>
    
    <?php if (!empty($message)) : ?>
        <div class="alert alert-info">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
                <option value="pd" <?php if ($user['role'] == 'pd') echo 'selected'; ?>>PD</option>
                <option value="p_department" <?php if ($user['role'] == 'p_department') echo 'selected'; ?>>P Department</option>
                <!-- Admin role has been removed -->
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Role</button>
        <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<footer>
    <div class="container">
        <p>&copy; 2024 Community Hub. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
