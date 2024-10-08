<?php
// Start session
session_start();

// Include database connection
require_once 'db_connection.php'; // Ensure the path is correct

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values
    $name_irl = $_POST['name_irl'];
    $age_irl = $_POST['age_irl'];
    $discord_id = $_POST['discord_id'];
    $character_name = $_POST['character_name'];
    $character_age = $_POST['character_age'];
    $backstory = $_POST['backstory'];

    // Prepare and bind the statement
    $stmt = $conn->prepare("INSERT INTO applications (name_irl, age_irl, discord_id, character_name, character_age, backstory) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissss", $name_irl, $age_irl, $discord_id, $character_name, $character_age, $backstory);

    // Execute statement
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Application submitted successfully!";
        header("Location: index.php"); // Redirect to the homepage
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php"><h1 class="navbar-brand">Bharat Roleplay</h1></a>
            <a href="index.php" class="btn btn-outline-primary">Home</a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Whitelisting Form</h3>

                        <!-- Display success or error message -->
                        <?php if (isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Application Form -->
                        <form method="POST" action="">
                            <!-- IRL Information Section -->
                            <h5>IRL Information</h5>
                            <div class="mb-3">
                                <label for="name_irl" class="form-label">Name (IRL)</label>
                                <input type="text" class="form-control" id="name_irl" name="name_irl" required>
                            </div>
                            <div class="mb-3">
                                <label for="age_irl" class="form-label">Age (IRL)</label>
                                <input type="number" class="form-control" id="age_irl" name="age_irl" required>
                            </div>
                            <div class="mb-3">
                                <label for="discord_id" class="form-label">Discord ID</label>
                                <input type="text" class="form-control" id="discord_id" name="discord_id" required>
                            </div>

                            <!-- Character Information Section -->
                            <h5>Character Information</h5>
                            <div class="mb-3">
                                <label for="character_name" class="form-label">Character Name</label>
                                <input type="text" class="form-control" id="character_name" name="character_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="character_age" class="form-label">Character Age</label>
                                <input type="number" class="form-control" id="character_age" name="character_age" required>
                            </div>
                            <div class="mb-3">
                                <label for="backstory" class="form-label">Character Backstory (min 50 words)</label>
                                <textarea class="form-control" id="backstory" name="backstory" rows="4" minlength="50" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Submit Application</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center mt-5">
        <div class="container">
            <p>&copy; 2024 Community Hub. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
