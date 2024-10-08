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

// Initialize role filter
$roleFilter = isset($_POST['role_filter']) ? $_POST['role_filter'] : '';

// Query to fetch users with optional role filter
$query = "SELECT * FROM users";
if ($roleFilter) {
    $query .= " WHERE role = ?";
}

$stmt = $conn->prepare($query);
if ($roleFilter) {
    $stmt->bind_param("s", $roleFilter);
}
$stmt->execute();
$usersQuery = $stmt->get_result();
$users = $usersQuery->fetch_all(MYSQLI_ASSOC);

// Handle role change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id']) && isset($_POST['new_role'])) {
    $userId = $_POST['user_id'];
    $newRole = $_POST['new_role'];

    // Update user role
    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $newRole, $userId);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_users.php"); // Refresh to show updates
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 20px;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            color: #343a40;
            font-weight: 600;
            margin-bottom: 20px;
        }
        h4 {
            color: #6c757d;
            margin-bottom: 20px;
        }
        .form-select, .btn {
            border-radius: 5px;
        }
        .btn {
            background-color: #007bff;
            color: white;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .table {
            border-radius: 5px;
            overflow: hidden;
        }
        .table th, .table td {
            text-align: center;
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }
        .table-striped tbody tr:hover {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Manage Users</h1>
    <h4>Hello, <?php echo $_SESSION['username']; ?>!</h4>

    <!-- Filter Form -->
    <form method="POST" class="mt-4">
        <div class="row">
            <div class="col-md-4">
                <select name="role_filter" class="form-select" onchange="this.form.submit()">
                    <option value="">All Roles</option>
                    <option value="user" <?php if ($roleFilter === 'user') echo 'selected'; ?>>User</option>
                    <option value="admin" <?php if ($roleFilter === 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="p_department" <?php if ($roleFilter === 'p_department') echo 'selected'; ?>>P Department</option>
                    <option value="e_department" <?php if ($roleFilter === 'e_department') echo 'selected'; ?>>E Department</option>
                </select>
            </div>
        </div>
    </form>

    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Change Role</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['role']; ?></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <select name="new_role" class="form-select" required>
                                <option value="">Select Role</option>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                                <option value="p_department">P Department</option>
                                <option value="e_department">E Department</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm mt-2">Change Role</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Back to Dashboard Button -->
    <a href="admin_dashboard.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
