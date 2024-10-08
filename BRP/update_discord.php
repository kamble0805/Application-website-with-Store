<?php
session_start();
include 'db_connection.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Fetch logged-in user's username
$username = $_SESSION['username'];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $discord_id = $_POST['discord_id'];

    // Validate the Discord ID (basic validation)
    if (empty($discord_id)) {
        echo "Discord ID cannot be empty.";
        exit();
    }

    // Update Discord ID in the database
    $sql_update = "UPDATE users SET discord_id = ? WHERE username = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ss", $discord_id, $username);

    if ($stmt_update->execute()) {
        // Redirect back to account details with a success message
        header('Location: account_details.php?update=success');
    } else {
        echo "Error updating Discord ID: " . $stmt_update->error;
    }
    $stmt_update->close();
}
$conn->close();
?>
