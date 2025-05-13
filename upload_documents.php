<?php
session_start();
require_once "db_connection.php";

// Redirect if not logged in
if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION["student_id"];
$success = "";
$error = "";

$uploadDir = "uploads/";
$allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
$maxSize = 2 * 1024 * 1024; // 2MB

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $photo = $_FILES["photo"];
    $marksheet = $_FILES["marksheet"];
    $certificate = $_FILES["certificate"];

    $files = ["photo" => $photo, "marksheet" => $marksheet, "certificate" => $certificate];
    $stored = [];

    foreach ($files as $key => $file) {
        if ($file["error"] !== UPLOAD_ERR_OK) {
            $error = "Error uploading $key.";
            break;
        }
        if (!in_array($file["type"], $allowedTypes)) {
            $error = ucfirst($key) . " must be JPG, PNG, or PDF.";
            break;
        }
        if ($file["size"] > $maxSize) {
            $error = ucfirst($key) . " exceeds size limit (2MB).";
            break;
        }

        $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
        $filename = $key . "_" . $student_id . "_" . time() . "." . $ext;
        $target = $uploadDir . $filename;

        if (!move_uploaded_file($file["tmp_name"], $target)) {
            $error = "Failed to save $key.";
            break;
        }

        $stored[$key] = $filename;
    }

    // Save to database
    if (empty($error)) {
        // Using PDO prepared statements for the insert operation
        try {
            $stmt = $conn->prepare("INSERT INTO documents (student_id, photo, marksheet, certificate) 
                VALUES (:student_id, :photo, :marksheet, :certificate)
                ON DUPLICATE KEY UPDATE photo = VALUES(photo), marksheet = VALUES(marksheet), certificate = VALUES(certificate)");

            $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $stmt->bindParam(':photo', $stored["photo"], PDO::PARAM_STR);
            $stmt->bindParam(':marksheet', $stored["marksheet"], PDO::PARAM_STR);
            $stmt->bindParam(':certificate', $stored["certificate"], PDO::PARAM_STR);

            if ($stmt->execute()) {
                $success = "Documents uploaded successfully.";
            } else {
                $error = "Database error. Try again.";
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Documents</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Upload Documents</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="post" action="upload_documents.php" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Passport-size Photo (JPG/PNG/PDF, Max 2MB)</label>
            <input type="file" class="form-control" name="photo" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Marksheet (JPG/PNG/PDF, Max 2MB)</label>
            <input type="file" class="form-control" name="marksheet" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Certificate (JPG/PNG/PDF, Max 2MB)</label>
            <input type="file" class="form-control" name="certificate" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Upload Documents</button>
    </form>
</div>

</body>
</html>
