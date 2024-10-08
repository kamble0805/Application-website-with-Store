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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input data
    $name_irl = htmlspecialchars(trim($_POST['name_irl']));
    $age_irl = intval($_POST['age_irl']);
    $discord_id = htmlspecialchars(trim($_POST['discord_id']));
    $character_name = htmlspecialchars(trim($_POST['character_name']));
    $character_age = intval($_POST['character_age']);
    $backstory = htmlspecialchars(trim($_POST['backstory']));
    $reason_to_join = htmlspecialchars(trim($_POST['reason_to_join']));

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO ems_application (name_irl, age_irl, discord_id, character_name, character_age, backstory, reason_to_join) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssss", $name_irl, $age_irl, $discord_id, $character_name, $character_age, $backstory, $reason_to_join);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Application submitted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error submitting application: " . $stmt->error . "</div>";
    }

    // Close statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMS Application Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e9ecef; /* Light gray background */
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 600px; /* Limit max width for form */
            border: 1px solid #dee2e6; /* Add a light border */
        }
        h1 {
            color: #343a40; /* Dark text */
            margin-bottom: 20px; /* Space below header */
        }
        .form-label {
            font-weight: bold; /* Bold labels */
        }
        .btn-primary {
            background-color: #007bff; /* Primary button color */
            border-color: #007bff; /* Border color */
            transition: background-color 0.3s; /* Smooth transition */
        }
        .btn-primary:hover {
            background-color: #0056b3; /* Darker blue on hover */
            border-color: #0056b3; /* Darker border on hover */
        }
        .form-control {
            border-radius: 5px; /* Rounded corners */
            transition: border-color 0.3s; /* Smooth transition */
        }
        .form-control:focus {
            border-color: #007bff; /* Blue border on focus */
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Shadow effect */
        }
        footer {
            margin-top: 50px;
            text-align: center;
            color: #6c757d; /* Lighter text */
            padding: 15px 0;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>EMS Application Form</h1>
    
    <form action="" method="POST">
        <div class="mb-3">
            <label for="name_irl" class="form-label">Your Name (IRL)</label>
            <input type="text" class="form-control" id="name_irl" name="name_irl" required>
        </div>

        <div class="mb-3">
            <label for="age_irl" class="form-label">Your Age (IRL)</label>
            <input type="number" class="form-control" id="age_irl" name="age_irl" required>
        </div>

        <div class="mb-3">
            <label for="discord_id" class="form-label">Your Discord ID</label>
            <input type="text" class="form-control" id="discord_id" name="discord_id" required>
        </div>

        <div class="mb-3">
            <label for="character_name" class="form-label">Character Name</label>
            <input type="text" class="form-control" id="character_name" name="character_name" required>
        </div>

        <div class="mb-3">
            <label for="character_age" class="form-label">Character Age</label>
            <input type="number" class="form-control" id="character_age" name="character_age" required>
        </div>

        <div class="mb-3">
            <label for="backstory" class="form-label">Backstory</label>
            <textarea class="form-control" id="backstory" name="backstory" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="reason_to_join" class="form-label">Reason to Join</label>
            <textarea class="form-control" id="reason_to_join" name="reason_to_join" rows="3" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit Application</button>
    </form>
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
