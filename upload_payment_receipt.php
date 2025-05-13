<?php
session_start();
require_once "db_connection.php";

// Redirect if not logged in
if (!isset($_SESSION["student_id"])) {
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION["student_id"];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["receipt"])) {
    $file = $_FILES["receipt"];
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
    
    if (in_array($file['type'], $allowed_types) && $file['size'] < 5 * 1024 * 1024) {
        $upload_dir = "uploads/receipts/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $filename = $student_id . "_" . time() . "_" . basename($file["name"]);
        $target_file = $upload_dir . $filename;

        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            // Save file path to database
            $stmt = $conn->prepare("UPDATE students SET receipt_path = :path WHERE id = :student_id");
            $stmt->execute([
                ':path' => $target_file,
                ':student_id' => $student_id
            ]);
            $message = "Receipt uploaded successfully.";
        } else {
            $message = "Error uploading file.";
        }
    } else {
        $message = "Invalid file type or size (max 5MB).";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Payment Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Upload Payment Receipt</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="receipt" class="form-label">Select Receipt (JPG, PNG, PDF):</label>
            <input type="file" class="form-control" name="receipt" id="receipt" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</body>
</html>
