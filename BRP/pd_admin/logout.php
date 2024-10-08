<?php
// Start session
session_start();

// Destroy session and redirect to the login page
session_destroy();
header("Location: index.php");
exit();
?>
