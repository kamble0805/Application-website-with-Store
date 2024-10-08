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

// Check if the ID is set in the POST request
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Update user role to a whitelisted role (e.g., 'whitelisted')
    $whitelisted_role = 'whitelisted'; // Define your whitelisted role
    $sql = "UPDATE users SET role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $whitelisted_role, $id);
    
    if ($stmt->execute()) {
        // Check how many rows were affected
        if ($stmt->affected_rows > 0) {
            // Redirect back to manage users with success message
            header("Location: manage_users.php?message=User role updated to whitelisted successfully.");
        } else {
            // If no rows were affected, user may not have existed
            header("Location: manage_users.php?message=No user found with that ID.");
        }
        exit();
    } else {
        // Log error for debugging
        error_log("SQL Error: " . $stmt->error);
        header("Location: manage_users.php?message=Error updating user role: " . $stmt->error);
        exit();
    }
} else {
    // If ID is not set
    header("Location: manage_users.php");
    exit();
}

// Close the connection
$stmt->close();
$conn->close();
?>
