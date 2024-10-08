<?php
// Start session
session_start();

// Check if the user is logged in and is not a 'user'
if (!isset($_SESSION['username']) || $_SESSION['role'] === 'user') {
    header("Location: login.php"); // Redirect to login if not allowed
    exit();
}

// Include database connection
require_once 'db_connection.php'; // Ensure the path is correct

// Initialize variables for form data
$name_irl = '';
$age_irl = '';
$discord_id = '';
$character_name = '';
$character_age = '';
$backstory = '';
$escalation = ''; // New variable for escalation and de-escalation explanation
$message = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name_irl = $_POST['name_irl'];
    $age_irl = $_POST['age_irl'];
    $discord_id = $_POST['discord_id'];
    $character_name = $_POST['character_name'];
    $character_age = $_POST['character_age'];
    $backstory = $_POST['backstory'];
    $escalation = $_POST['escalation']; // Capture escalation and de-escalation explanation

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO pd_application (name_irl, age_irl, discord_id, character_name, character_age, backstory, escalation) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssss", $name_irl, $age_irl, $discord_id, $character_name, $character_age, $backstory, $escalation);

    // Execute and check for success
    if ($stmt->execute()) {
        $message = "Application submitted successfully!";
    } else {
        $message = "Error submitting application: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
    $conn->close(); // Close the database connection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f4f8; /* Light background color */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Modern font style */
            color: #333; /* Dark text color for better readability */
        }
        .container {
            background-color: #ffffff; /* White background for container */
            border: 2px solid black; /* Black border */
            border-radius: 10px; /* Rounded corners */
            padding: 30px; /* Increased padding */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); /* Subtle shadow */
            max-width: 600px; /* Limit the width of the container */
            margin: auto; /* Center the container */
        }
        h1 {
            color: #007bff; /* Blue color for heading */
            text-align: center; /* Center the heading */
            margin-bottom: 20px; /* Space below heading */
        }
        .btn-primary {
            background-color: #007bff; /* Primary button color */
            border: none; /* No border */
            transition: background-color 0.3s; /* Smooth transition for hover */
        }
        .btn-primary:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }
        .form-label {
            font-weight: bold; /* Bold labels */
            color: #555; /* Darker label color */
        }
        .alert {
            margin-top: 20px; /* Add margin for alert messages */
        }
        textarea.form-control,
        input.form-control {
            border: 1px solid black; /* Black border */
            border-radius: 5px; /* Rounded corners */
        }
        textarea.form-control:focus,
        input.form-control:focus {
            border-color: #007bff; /* Change border color on focus */
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Add glow effect */
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1>SASP Application Form</h1>
    
    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="name_irl" class="form-label">Name (IRL)</label>
            <input type="text" class="form-control" name="name_irl" id="name_irl" required>
        </div>
        <div class="mb-3">
            <label for="age_irl" class="form-label">Age (IRL)</label>
            <input type="number" class="form-control" name="age_irl" id="age_irl" required>
        </div>
        <div class="mb-3">
            <label for="discord_id" class="form-label">Discord ID</label>
            <input type="text" class="form-control" name="discord_id" id="discord_id" required>
        </div>
        <div class="mb-3">
            <label for="character_name" class="form-label">Character Name</label>
            <input type="text" class="form-control" name="character_name" id="character_name" required>
        </div>
        <div class="mb-3">
            <label for="character_age" class="form-label">Character Age</label>
            <input type="number" class="form-control" name="character_age" id="character_age" required>
        </div>
        <div class="mb-3">
            <label for="backstory" class="form-label">Backstory</label>
            <textarea class="form-control" name="backstory" id="backstory" rows="5" required></textarea>
        </div>
        <div class="mb-3">
            <label for="escalation" class="form-label">Explain Escalation and De-escalation</label>
            <textarea class="form-control" name="escalation" id="escalation" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Application</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
