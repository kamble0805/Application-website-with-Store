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

// Fetch all character entries from the pd_application table
$sql = "SELECT * FROM pd_application";
$result = $conn->query($sql);

// Handle error in case of failure
if (!$result) {
    die("Error executing query: " . $conn->error);
}

// Handle form submission for accepting or rejecting applications
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $id = $_POST['id'];          // Application ID
    $discord_id = $_POST['discord_id']; // Discord ID associated with the user
    $action = $_POST['action'];

    if ($action === 'accept') {
        // Update the application status to accepted
        $stmt = $conn->prepare("UPDATE pd_application SET status = 'accepted' WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            // Also update user role to 'pd' in the users table using discord_id
            $stmt->close(); // Close the previous statement
            $stmt = $conn->prepare("UPDATE users SET role = 'pd' WHERE discord_id = ?");
            $stmt->bind_param("s", $discord_id);
            $stmt->execute();
            $stmt->close();

            // Send a notification that the application has been accepted
            $notification_message = "Your application has been accepted in the PD department. Congratulations!";
            $stmt = $conn->prepare("INSERT INTO notifications (discord_id, message) VALUES (?, ?)");
            $stmt->bind_param("ss", $discord_id, $notification_message);
            $stmt->execute();
            $stmt->close();
        }
    } elseif ($action === 'reject') {
        // Update the application status to rejected
        $stmt = $conn->prepare("UPDATE pd_application SET status = 'rejected' WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    // Redirect back to the manage applications page
    header("Location: manage_application.php");
    exit();
}
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
    <h1>Manage Applications</h1>
    
    <?php if ($result->num_rows > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name (IRL)</th>
                    <th>Age (IRL)</th>
                    <th>Discord ID</th>
                    <th>Character Name</th>
                    <th>Character Age</th>
                    <th>Backstory</th>
                    <th>Escalation</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name_irl']); ?></td>
                        <td><?php echo htmlspecialchars($row['age_irl']); ?></td>
                        <td><?php echo htmlspecialchars($row['discord_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['character_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['character_age']); ?></td>
                        <td><?php echo htmlspecialchars($row['backstory']); ?></td>
                        <td><?php echo htmlspecialchars($row['escalation']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="discord_id" value="<?php echo $row['discord_id']; ?>"> <!-- Hidden discord_id -->
                                <button type="submit" name="action" value="accept" class="btn btn-success btn-sm">Accept</button>
                                <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No characters found.</p>
    <?php endif; ?>
</div>

<footer>
    <div class="container">
        <p>&copy; 2024 Community Hub. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
