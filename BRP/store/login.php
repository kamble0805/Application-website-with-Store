<?php
session_start();
include 'db_connection.php'; // Ensure you have the correct path

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Example query to check credentials (without password hashing)
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?"; // Plain text password comparison
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Successful login
        $user = $result->fetch_assoc();
        $_SESSION['loggedin'] = true; // Set logged in status
        $_SESSION['username'] = $user['username']; // Store username for later use
        $_SESSION['role'] = $user['role']; // Store user role if needed
        header('Location: index.php'); // Redirect to home page
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: white;
            border: 1px solid #007bff; /* Blue border */
            border-radius: 10px; /* Rounded corners */
            padding: 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            max-width: 400px;
            width: 100%;
        }

        .form-label {
            color: #007bff; /* Label color */
        }

        .form-control {
            border-radius: 0.5rem; /* Rounded input fields */
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .login-heading {
            color: #007bff;
            margin-bottom: 1.5rem;
        }

        .alert {
            margin-top: 1rem;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2 class="login-heading">Login</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
        <p class="mt-3 text-center">Don't have an account? <a href="signup.php">Register</a></p>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
