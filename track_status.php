<?php
session_start();
require_once "db_connection.php";

// Redirect if not logged in
if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION["student_id"];

// Use PDO prepared statements and bind parameters correctly
$stmt = $conn->prepare("
    SELECT a.reference_no, a.status, a.created_at, c.name AS course_name
    FROM applications a
    JOIN courses c ON a.course_id = c.id
    WHERE a.student_id = :student_id
    ORDER BY a.created_at DESC
    LIMIT 1
");

// Bind the student_id parameter
$stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);

// Execute the statement
$stmt->execute();

// Fetch the result
$application = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor(); // Close the cursor to free up resources
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Application Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Application Status</h2>

    <?php if ($application): ?>
        <table class="table table-bordered">
            <tr>
                <th>Reference No</th>
                <td><?= htmlspecialchars($application['reference_no']) ?></td>
            </tr>
            <tr>
                <th>Course</th>
                <td><?= htmlspecialchars($application['course_name']) ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <?php
                        $status = htmlspecialchars($application['status']);
                        $badgeClass = match ($status) {
                            'Accepted' => 'success',
                            'Rejected' => 'danger',
                            'On Hold'  => 'warning',
                            default    => 'secondary'
                        };
                    ?>
                    <span class="badge bg-<?= $badgeClass ?>"><?= $status ?></span>
                </td>
            </tr>
            <tr>
                <th>Submitted On</th>
                <td><?= htmlspecialchars($application['created_at']) ?></td>
            </tr>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No application found. Please submit an application first.</div>
    <?php endif; ?>
</div>

</body>
</html>
