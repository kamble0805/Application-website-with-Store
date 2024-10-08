<?php
session_start();

// Include database connection
require_once 'db_connection.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Fetch applications
$applicationsQuery = $conn->query("SELECT * FROM applications");
$applications = $applicationsQuery->fetch_all(MYSQLI_ASSOC);

// Handle application actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $appId = $_POST['app_id'];
        $action = $_POST['action'];
        
        if ($action === 'accept') {
            // Update application status to accepted
            $stmt = $conn->prepare("UPDATE applications SET status = 'accepted' WHERE id = ?");
            $stmt->bind_param("i", $appId);
            $stmt->execute();
            
            // Update user role to whitelisted or pd (or any logic you want to implement)
            // Assuming you have a logic to determine which user to update
            // For this example, I’m commenting it out because you might have a separate user table
            // $stmt = $conn->prepare("UPDATE users SET role = 'whitelisted' WHERE id = (SELECT user_id FROM applications WHERE id = ?)");
            // $stmt->bind_param("i", $appId);
            // $stmt->execute();
        } elseif ($action === 'reject') {
            // Update application status to rejected
            $stmt = $conn->prepare("UPDATE applications SET status = 'rejected' WHERE id = ?");
            $stmt->bind_param("i", $appId);
            $stmt->execute();
        }
        
        // Redirect to the same page to see updates
        header("Location: admin_panel.php");
        exit();
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Admin Panel</h1>
    
    <h2 class="mt-5">Application Management</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name IRL</th>
                <th>Age IRL</th>
                <th>Discord ID</th>
                <th>Character Name</th>
                <th>Character Age</th>
                <th>Backstory</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($applications as $app): ?>
                <tr>
                    <td><?php echo $app['id']; ?></td>
                    <td><?php echo $app['name_irl']; ?></td>
                    <td><?php echo $app['age_irl']; ?></td>
                    <td><?php echo $app['discord_id']; ?></td>
                    <td><?php echo $app['character_name']; ?></td>
                    <td><?php echo $app['character_age']; ?></td>
                    <td><?php echo substr($app['backstory'], 0, 50) . '...'; ?></td>
                    <td><?php echo $app['status']; ?></td>
                    <td>
                        <?php if ($app['status'] === 'pending'): ?>
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="app_id" value="<?php echo $app['id']; ?>">
                                <button type="submit" name="action" value="accept" class="btn btn-success btn-sm">Accept</button>
                                <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        <?php else: ?>
                            <span class="badge bg-secondary"><?php echo ucfirst($app['status']); ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
