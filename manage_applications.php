<?php
session_start();
require_once "db_connection.php";

// Redirect if not logged in
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

$error = "";
$success = "";

// Handle Accept, Reject, or Hold action
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["action"]) && isset($_POST["application_id"])) {
        $application_id = $_POST["application_id"];
        $action = $_POST["action"];

        $stmt = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
        $stmt->bindParam(1, $action, PDO::PARAM_STR);
        $stmt->bindParam(2, $application_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $success = "Application status updated to $action.";
        } else {
            $error = "Failed to update the application status.";
        }

        $stmt->closeCursor();  // Close the cursor to free up memory
    }
}

// Filter Applications by Status
$status_filter = $_GET["status"] ?? "All"; // Default to "All" if no filter
$query = "SELECT applications.*, documents.photo, documents.marksheet, documents.certificate 
          FROM applications 
          LEFT JOIN documents ON applications.student_id = documents.student_id";


if ($status_filter !== "All") {
    $query .= " WHERE status = :status";
}

// Prepare statement based on filter
$stmt = $conn->prepare($query);
if ($status_filter !== "All") {
    $stmt->bindParam(':status', $status_filter, PDO::PARAM_STR);
}
$stmt->execute();

// Fetch all results using fetchAll()
$applications_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();  // Close the cursor to free up memory
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Applications</title>
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

<!-- Manage Applications -->
<div class="container mt-5">
    <h2 class="mb-4">Manage Applications</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <!-- Filter Applications -->
    <div class="mb-4">
        <form method="get" action="manage_applications.php">
            <div class="row">
                <div class="col">
                    <select class="form-select" name="status" onchange="this.form.submit()">
                        <option value="All" <?= $status_filter === "All" ? "selected" : "" ?>>All</option>
                        <option value="Pending" <?= $status_filter === "Pending" ? "selected" : "" ?>>Pending</option>
                        <option value="Accepted" <?= $status_filter === "Accepted" ? "selected" : "" ?>>Accepted</option>
                        <option value="Rejected" <?= $status_filter === "Rejected" ? "selected" : "" ?>>Rejected</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Applications Table -->
    <table class="table table-bordered">
        <thead>
    <tr>
        <th>Student ID</th>
        <th>Course ID</th>
        <th>School</th>
        <th>Marks</th>
        <th>Passing Year</th>
        <th>Status</th>
        <th>Document</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($applications_result as $application): ?>
        <tr>
            <td><?= htmlspecialchars($application["student_id"] ?? "") ?></td>
            <td><?= htmlspecialchars($application["course_id"] ?? "") ?></td>
            <td><?= htmlspecialchars($application["school"] ?? "") ?></td>
            <td><?= htmlspecialchars($application["marks"] ?? "") ?></td>
            <td><?= htmlspecialchars($application["passing_year"] ?? "") ?></td>
            <td><?= htmlspecialchars($application["status"] ?? "") ?></td>
            <td>
    <?php if (!empty($application["photo"])): ?>
        <a href="uploads/<?= htmlspecialchars($application["photo"]) ?>" target="_blank" class="btn btn-sm btn-outline-primary mb-1">Photo</a><br>
    <?php endif; ?>
    <?php if (!empty($application["marksheet"])): ?>
        <a href="uploads/<?= htmlspecialchars($application["marksheet"]) ?>" target="_blank" class="btn btn-sm btn-outline-success mb-1">Marksheet</a><br>
    <?php endif; ?>
    <?php if (!empty($application["certificate"])): ?>
        <a href="uploads/<?= htmlspecialchars($application["certificate"]) ?>" target="_blank" class="btn btn-sm btn-outline-info">Certificate</a>
    <?php endif; ?>
    <?php if (empty($application["photo"]) && empty($application["marksheet"]) && empty($application["certificate"])): ?>
        <span class="text-muted">No documents</span>
    <?php endif; ?>
</td>

            <td>
                <!-- Accept, Reject, Hold Actions -->
                <form method="post" action="manage_applications.php" class="d-inline">
                    <input type="hidden" name="application_id" value="<?= $application["id"] ?>">
                    <button type="submit" class="btn btn-success btn-sm" name="action" value="Accepted">Accept</button>
                </form>
                <form method="post" action="manage_applications.php" class="d-inline">
                    <input type="hidden" name="application_id" value="<?= $application["id"] ?>">
                    <button type="submit" class="btn btn-danger btn-sm" name="action" value="Rejected">Reject</button>
                </form>
                <form method="post" action="manage_applications.php" class="d-inline">
                    <input type="hidden" name="application_id" value="<?= $application["id"] ?>">
                    <button type="submit" class="btn btn-warning btn-sm" name="action" value="Pending">Hold</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

    </table>
</div>

</body>
</html>
