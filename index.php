<?php
// Start session (if needed for login feedback or user redirection)
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>College Admission Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">College Admission Portal</a>
        <div class="d-flex">
            <a class="btn btn-outline-light me-2" href="login.php">Login</a>
            <a class="btn btn-light" href="register.php">Register</a>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container mt-5">
    <div class="text-center">
        <h1>Welcome to the College Admission Portal</h1>
        <p class="lead">Apply online for your desired undergraduate program from the comfort of your home.</p>
        <p>Through this portal, students can register, submit applications, track status, and upload documents. 
        Administrators can manage courses and applications efficiently.</p>
        <a href="register.php" class="btn btn-primary btn-lg mt-3">Get Started</a>
    </div>
</div>

<!-- Footer -->
<footer class="bg-light text-center mt-5 py-3">
    <small>&copy; <?php echo date("Y"); ?> College Admission Portal. All rights reserved.</small>
</footer>

<!-- Bootstrap JS (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
