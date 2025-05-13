<?php
session_start();
require_once "db_connection.php";

// Redirect if not logged in
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch statistics
$stats = [
    "total_applications" => 0,
    "pending_applications" => 0,
    "accepted_applications" => 0,
    "rejected_applications" => 0,
];

// Use PDO to fetch data
$stmt = $conn->query("SELECT COUNT(*) AS total FROM applications");
$row = $stmt->fetch(PDO::FETCH_ASSOC); // Changed to PDO method
if ($row) {
    $stats["total_applications"] = $row["total"];
}

$stmt = $conn->query("SELECT COUNT(*) AS total FROM applications WHERE status = 'Pending'");
$row = $stmt->fetch(PDO::FETCH_ASSOC); // Changed to PDO method
if ($row) {
    $stats["pending_applications"] = $row["total"];
}

$stmt = $conn->query("SELECT COUNT(*) AS total FROM applications WHERE status = 'Accepted'");
$row = $stmt->fetch(PDO::FETCH_ASSOC); // Changed to PDO method
if ($row) {
    $stats["accepted_applications"] = $row["total"];
}

$stmt = $conn->query("SELECT COUNT(*) AS total FROM applications WHERE status = 'Rejected'");
$row = $stmt->fetch(PDO::FETCH_ASSOC); // Changed to PDO method
if ($row) {
    $stats["rejected_applications"] = $row["total"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin Panel</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">
                Welcome, Admin
            </span>
            <a href="admin_login.php" class="btn btn-light">Logout</a>
        </div>
    </div>
</nav>

<!-- Dashboard Content -->
<div class="container mt-5">
    <h2 class="mb-4">Admin Dashboard</h2>

    <div class="row g-4">
        <!-- Total Applications -->
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Applications</h5>
                    <p class="card-text"><?= $stats["total_applications"] ?></p>
                </div>
            </div>
        </div>

        <!-- Pending Applications -->
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Pending Applications</h5>
                    <p class="card-text"><?= $stats["pending_applications"] ?></p>
                </div>
            </div>
        </div>

        <!-- Accepted Applications -->
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Accepted Applications</h5>
                    <p class="card-text"><?= $stats["accepted_applications"] ?></p>
                </div>
            </div>
        </div>

        <!-- Rejected Applications -->
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Rejected Applications</h5>
                    <p class="card-text"><?= $stats["rejected_applications"] ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="mt-5">
        <h3>Quick Actions</h3>
        <div class="row g-4">
            <div class="col-md-4">
                <a href="manage_applications.php" class="btn btn-outline-primary w-100 py-3">Manage Applications</a>
            </div>
            <div class="col-md-4">
                <a href="check_payment_receipt.php" class="btn btn-outline-primary w-100 py-3">Check Payment Receipt</a>
            </div>
            <div class="col-md-4">
                <a href="manage_courses.php" class="btn btn-outline-secondary w-100 py-3">Manage Courses</a>
            </div>
            <div class="col-md-4">
                <a href="generate_reports.php" class="btn btn-outline-success w-100 py-3">Generate Reports</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
