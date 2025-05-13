<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit();
}

$studentName = $_SESSION["student_name"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Admission Portal</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">
                Welcome, <?= htmlspecialchars($studentName) ?>
            </span>
            <a href="logout.php" class="btn btn-light">Logout</a>
        </div>
    </div>
</nav>

<!-- Dashboard Content -->
<div class="container mt-5">
    <h2 class="mb-4">Student Dashboard</h2>
    <div class="row g-4">

        <div class="col-md-6">
            <a href="submit_application.php" class="btn btn-outline-success w-100 py-3">Submit Application</a>
        </div>

        <div class="col-md-6">
            <a href="upload_documents.php" class="btn btn-outline-info w-100 py-3">Upload Documents</a>
        </div>
        
        <div class="col-md-6">
            <a href="upload_payment_receipt.php" class="btn btn-outline-info w-100 py-3">Upload Payment Receipt</a>
        </div>

        <div class="col-md-6">
            <a href="track_status.php" class="btn btn-outline-warning w-100 py-3">Track Application Status</a>
        </div>

    </div>
</div>

</body>
</html>
