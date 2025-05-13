<?php
session_start();
require_once "db_connection.php";

// Redirect if not logged in as admin
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch student receipt info
$stmt = $conn->query("SELECT id, name, email, receipt_path FROM students WHERE receipt_path IS NOT NULL ORDER BY id DESC");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Check Payment Receipts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Uploaded Payment Receipts</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Email</th>
                <th>Receipt</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= htmlspecialchars($student['name']) ?></td>
                    <td><?= htmlspecialchars($student['email']) ?></td>
                    <td>
                        <a href="<?= htmlspecialchars($student['receipt_path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                            View Receipt
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
