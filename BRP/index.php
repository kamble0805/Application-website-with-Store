<?php
session_start(); // Start the session

// Check if user is logged in
$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Hub - Account Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles for the card */
        .custom-card {
            background-color: #f8f9fa; /* Light background color */
            border: 1px solid #007bff; /* Border color */
            border-radius: 0.5rem; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        .custom-card-title {
            color: #007bff; /* Title color */
            font-weight: bold; /* Bold title */
        }

        .custom-card-text {
            color: #555; /* Instruction text color */
        }

/* Custom styles for the What We Offer section */
.offer-section {
    margin-top: 40px; /* Space above the section */
    padding: 20px; /* Padding inside the section */
    background-color: #f1f1f1; /* Light grey background */
    border-radius: 0.5rem; /* Rounded corners */
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Softer shadow */
}

.offer-section h5 {
    font-size: 1.5rem; /* Title font size */
    margin-bottom: 20px; /* Space below the title */
    color: #007bff; /* Title color */
    text-align: center; /* Center align title */
}

.offer-section .accordion {
    margin-bottom: 20px; /* Add space between each accordion */
}

.offer-section .accordion-item {
    margin-bottom: 15px; /* Space between accordion items */
    border-radius: 0.5rem; /* Rounded borders for each accordion item */
    overflow: hidden; /* Ensures the borders are rounded correctly */
    border: 1px solid #007bff; /* Add border color */
}

.offer-section .accordion-header {
    background-color: #007bff; /* Bootstrap primary color */
    color: #ffffff; /* White text */
}

.offer-section .accordion-button {
    background-color: transparent; /* Transparent background */
    color: #007bff; /* Bootstrap primary color */
    border-radius: 0.5rem; /* Rounded corners for buttons */
    font-weight: bold; /* Bold text to make headings more visible */
}

.offer-section .accordion-button:not(.collapsed) {
    background-color: #0069d9; /* Darker blue when expanded */
    color: #ffffff; /* White text when expanded */
    border-color: #0069d9; /* Darker border when expanded */
}

/* Fix to ensure collapsed accordion button heading is visible */
.offer-section .accordion-button.collapsed {
    color: #007bff; /* Primary color for collapsed button text */
    background-color: #ffffff; /* White background for collapsed buttons */
}

.offer-section .accordion-button:hover {
    background-color: rgba(0, 123, 255, 0.1); /* Light blue on hover */
}

.offer-section .accordion-body {
    background-color: #ffffff; /* White background for body */
    padding: 15px; /* Padding inside the accordion body */
    border-radius: 0.25rem; /* Rounded corners */
    color: #333; /* Darker text color */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

/* Responsive design adjustments */
@media (max-width: 768px) {
    .offer-section h5 {
        font-size: 1.3rem; /* Adjust title size on smaller screens */
    }
}

    </style>
</head>
<body>

    <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">
            <h1>Bharat Roleplay</h1>
        </a>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $isLoggedIn ? 'apply.php' : 'login.php'; ?>">Apply</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="rules.php">Rules</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="store/store.php">Store</a> <!-- New Store link -->
                </li>
                
                <!-- Departmental Application Tab -->
                <?php if ($isLoggedIn && isset($_SESSION['role']) && $_SESSION['role'] !== 'user'): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="departmentalDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Departmental Application
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="departmentalDropdown">
                        <li><a class="dropdown-item" href="pd_apply.php">PD Apply</a></li>
                        <li><a class="dropdown-item" href="ems_apply.php">EMS Apply</a></li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <!-- Welcome message and Logout/Login button -->
        <span class="navbar-text me-2">
            <?php if ($isLoggedIn): ?>
                <a href="account_details.php" class="text-decoration-none text-dark">
                    Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
                </a>
                <a href="logout.php" class="btn btn-outline-danger">Logout</a> <!-- Logout Button -->
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-success">Login</a> <!-- Login Button -->
            <?php endif; ?>
        </span>
    </div>
</nav>



    <!-- Main Section -->
    <div class="container text-center mt-5">
        <h1>Welcome to the Bharat Roleplay</h1>
        <p>Join our vibrant community and start your roleplaying journey today.</p>
        <a href="https://discord.gg/PV5UxKA2Wm" class="btn btn-primary btn-lg">Join Now</a>
    </div>

  <!-- Our Story Card -->
<div class="container mt-5">
    <div class="card custom-card">
        <div class="card-body">
            <div class="row">
                <!-- Video Section (left side) -->
                <div class="col-md-6">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/BLO02PIwFBw?si=GD3FZp3uRQVdDW67" allowfullscreen></iframe>
                    </div>
                </div>
                <!-- Story Section (right side) -->
                <div class="col-md-6">
                    <h5 class="custom-card-title">Our Story</h5>
                    <p class="custom-card-text">
                        Bharat Roleplay started as a small group of passionate roleplayers, seeking a platform to express creativity and engage in immersive storytelling. Over time, we grew into a vibrant community driven by collaboration, creativity, and shared experiences. Join us and become part of our ever-evolving story, where every voice matters and every journey is unique.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>



    <!-- Instruction Card -->
    <div class="container mt-5">
        <div class="card custom-card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div class="text-start">
                    <h5 class="custom-card-title">How to Apply</h5>
                    <p class="custom-card-text">Follow these simple steps to apply:</p>
                    <ol class="text-start">
                        <li>Create an account by clicking on the "Join Now" button.</li>
                        <li>Fill in the application form with your details.</li>
                        <li>Submit your application for review.</li>
                    </ol>
                </div>
                <div>
                    <!-- Check login status and redirect accordingly -->
                    <a href="<?php echo $isLoggedIn ? 'apply.php' : 'login.php'; ?>" class="btn btn-primary">
                        Apply Now
                    </a>
                </div>
            </div>
        </div>
    </div>

   <!-- What We Offer Section -->
<div class="container offer-section">
    <div class="card custom-card">
        <div class="card-body">
            <h5 class="custom-card-title">What We Offer</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="accordion" id="offerAccordionLeft">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Community Driven
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#offerAccordionLeft">
                                <div class="accordion-body">
                                    Our community is built on collaboration and support, ensuring everyone has a voice.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Quality Roleplay
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#offerAccordionLeft">
                                <div class="accordion-body">
                                    We prioritize high-quality roleplay experiences, promoting immersive storytelling and character development.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Balanced Economy
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#offerAccordionLeft">
                                <div class="accordion-body">
                                    Our economy is designed to ensure fairness and balance, allowing for rewarding gameplay.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="accordion" id="offerAccordionRight">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Active Development
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#offerAccordionRight">
                                <div class="accordion-body">
                                    We are continuously improving and expanding our platform to enhance your experience.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFive">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    Reliable Staff
                                </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#offerAccordionRight">
                                <div class="accordion-body">
                                    Our dedicated staff is here to assist you, ensuring a smooth and enjoyable experience.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingSix">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                    Unique Clothing
                                </button>
                            </h2>
                            <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#offerAccordionRight">
                                <div class="accordion-body">
                                    Express your individuality with our unique clothing options tailored for your characters.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer Section -->
<footer class="bg-light text-center py-3">
    <div class="container">
        <p class="text-muted">© 2024 Bharat Roleplay. All Rights Reserved.</p>
        <a href="privacy.php" class="text-decoration-none">Privacy Policy</a> |
        <a href="terms.php" class="text-decoration-none">Terms of Service</a>
    </div>
</footer>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
