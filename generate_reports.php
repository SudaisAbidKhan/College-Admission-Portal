<?php
session_start();
require_once "db_connection.php";

// Redirect if not logged in
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

$report_data = [];
$total_applications = 0;
$accepted = 0;
$rejected = 0;
$pending = 0;

// Fetch total application counts
$query = "SELECT status, COUNT(*) AS count FROM applications GROUP BY status";
$stmt = $conn->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $row) {
    $status = $row["status"];
    $count = $row["count"];
    $report_data[$status] = $count;

    if ($status === "Accepted") {
        $accepted = $count;
    } elseif ($status === "Rejected") {
        $rejected = $count;
    } elseif ($status === "Pending") {
        $pending = $count;
    }
    $total_applications += $count;
}

$stmt = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate Reports</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin Panel</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">Welcome, Admin</span>
            <a href="admin_login.php" class="btn btn-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="mb-4">Application Reports</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Status</th>
                <th>Count</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Accepted</td>
                <td><?= $accepted ?></td>
            </tr>
            <tr>
                <td>Rejected</td>
                <td><?= $rejected ?></td>
            </tr>
            <tr>
                <td>Pending</td>
                <td><?= $pending ?></td>
            </tr>
            <tr>
                <td><strong>Total Applications</strong></td>
                <td><strong><?= $total_applications ?></strong></td>
            </tr>
        </tbody>
    </table>

    <form method="post" action="export_data.php">
        <button type="submit" name="export_csv" class="btn btn-success">Export to CSV</button>
        <button type="submit" name="export_excel" class="btn btn-primary">Export to Excel</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
