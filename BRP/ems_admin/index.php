<?php
// Start session
session_start();

// Include database connection
require_once 'db_connection.php'; // Ensure the path is correct

// Initialize variables
$username = '';
$password = '';
$error_message = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the SQL statement to check user credentials
    $stmt = $conn->prepare("SELECT role FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password); // "ss" means two strings
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // User exists, now get the role
        $stmt->bind_result($role);
        $stmt->fetch();

        // Check if the user has the required role
        if ($role === 'e_department' || $role === 'admin') {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            header("Location: ems_dashboard.php"); // Redirect to a dashboard or home page
            exit();
        } else {
            $error_message = "Access denied: insufficient permissions.";
        }
    } else {
        $error_message = "Invalid username or password.";
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
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Light background color */
            font-family: Arial, sans-serif; /* Font style */
        }
        .container {
            margin-top: 100px; /* Space from top */
        }
        .card {
            border: 2px solid black; /* Black border */
            border-radius: 15px; /* More rounded corners */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Shadow effect */
        }
        .card-title {
            color: #007bff; /* Blue color for title */
        }
        .form-label {
            font-weight: bold; /* Bold labels */
        }
        .btn-primary {
            background-color: #007bff; /* Button color */
            border: none; /* No border */
            border-radius: 5px; /* Rounded corners for button */
        }
        .alert {
            margin-top: 20px; /* Space above alert messages */
        }
        footer {
            margin-top: 30px; /* Space above footer */
            text-align: center; /* Center align footer text */
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h3 class="text-center mb-4">Login</h3>

                <!-- Display error message -->
                <?php if ($error_message): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
