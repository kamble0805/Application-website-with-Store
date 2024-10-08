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

// Initialize filter
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// Handle accept/reject actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $application_id = $_POST['application_id'];
    $action = $_POST['action']; // 'accept' or 'reject'

    if ($action === 'accept') {
        // First, fetch the Discord ID associated with the application
        $discordIdStmt = $conn->prepare("SELECT discord_id FROM applications WHERE id = ?");
        $discordIdStmt->bind_param("i", $application_id);
        $discordIdStmt->execute();
        $discordIdResult = $discordIdStmt->get_result();

        if ($discordIdResult->num_rows > 0) {
            $discord_id = $discordIdResult->fetch_assoc()['discord_id'];

            // Update the application status
            $stmt = $conn->prepare("UPDATE applications SET status = 'accepted' WHERE id = ?");
            $stmt->bind_param("i", $application_id);
            $stmt->execute();

            // Now update the user's role to 'whitelisted' using the Discord ID
            $updateRoleStmt = $conn->prepare("UPDATE users SET role = 'whitelisted' WHERE discord_id = ?");
            $updateRoleStmt->bind_param("s", $discord_id);
            $updateRoleStmt->execute();
            $updateRoleStmt->close();

            // Create a notification for the user
            $adminName = $_SESSION['username']; // The name of the admin who accepted the application
            $notificationMessage = "Your Whitelist application has been accepted by $adminName.";
            $notificationStmt = $conn->prepare("INSERT INTO notifications (discord_id, message) VALUES (?, ?)");
            $notificationStmt->bind_param("ss", $discord_id, $notificationMessage);
            $notificationStmt->execute();
            $notificationStmt->close();

            // Close the statement for updating applications
            $stmt->close();
        } else {
            echo "No application found with this ID."; // Handle case where no application is found
        }

        $discordIdStmt->close();

    } elseif ($action === 'reject') {
        // Handle rejection
        $stmt = $conn->prepare("UPDATE applications SET status = 'rejected' WHERE id = ?");
        $stmt->bind_param("i", $application_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Query to fetch applications with filtering
$applicationsQuery = $conn->prepare("SELECT * FROM applications" . ($status_filter ? " WHERE status = ?" : ""));
if ($status_filter) {
    $applicationsQuery->bind_param("s", $status_filter);
}
$applicationsQuery->execute();
$applications = $applicationsQuery->get_result()->fetch_all(MYSQLI_ASSOC);
$applicationsQuery->close(); // Close the query statement
$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Light background color */
            font-family: Arial, sans-serif; /* Font style */
        }
        .container {
            background-color: #ffffff; /* White background for container */
            border-radius: 8px; /* Rounded corners */
            padding: 20px; /* Padding inside the container */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
        h1 {
            color: #007bff; /* Blue color for heading */
        }
        .table th {
            background-color: #007bff; /* Blue header for table */
            color: white; /* White text color */
        }
        .table td {
            vertical-align: middle; /* Center align text */
        }
        .btn-primary {
            margin-top: 10px; /* Add margin for the button */
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1>Manage Applications</h1>
    
    <form method="GET" action="" class="mb-3">
        <label for="status" class="form-label">Filter by Status:</label>
        <select name="status" id="status" class="form-select" onchange="this.form.submit()">
            <option value="">All</option>
            <option value="accepted" <?php echo ($status_filter === 'accepted') ? 'selected' : ''; ?>>Accepted</option>
            <option value="rejected" <?php echo ($status_filter === 'rejected') ? 'selected' : ''; ?>>Rejected</option>
            <option value="pending" <?php echo ($status_filter === 'pending') ? 'selected' : ''; ?>>Pending</option>
        </select>
    </form>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name (IRL)</th>
                <th>Age (IRL)</th>
                <th>Discord ID</th>
                <th>Character Name</th>
                <th>Character Age</th>
                <th>Backstory</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($applications as $application): ?>
                <tr>
                    <td><?php echo $application['id']; ?></td>
                    <td><?php echo htmlspecialchars($application['name_irl']); ?></td>
                    <td><?php echo htmlspecialchars($application['age_irl']); ?></td>
                    <td><?php echo htmlspecialchars($application['discord_id']); ?></td>
                    <td><?php echo htmlspecialchars($application['character_name']); ?></td>
                    <td><?php echo htmlspecialchars($application['character_age']); ?></td>
                    <td><?php echo htmlspecialchars($application['backstory']); ?></td>
                    <td><?php echo htmlspecialchars($application['status']); ?></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="application_id" value="<?php echo $application['id']; ?>">
                            <button type="submit" name="action" value="accept" class="btn btn-success">Accept</button>
                            <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="admin_dashboard.php" class="btn btn-primary mb-3">Go Back to Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
