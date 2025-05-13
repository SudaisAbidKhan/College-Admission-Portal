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

// Fetch available courses using PDO
$courses = [];
$stmt = $conn->prepare("SELECT id, name FROM courses");
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $course_id = $_POST["course_id"] ?? "";
    $school = trim($_POST["school"] ?? "");
    $marks = trim($_POST["marks"] ?? "");
    $passing_year = trim($_POST["passing_year"] ?? "");
    
    if (!$course_id || !$school || !$marks || !$passing_year) {
        $error = "All fields are required.";
    } else {
        $ref_no = "APP" . time() . rand(100, 999);
        $stmt = $conn->prepare("INSERT INTO applications (student_id, course_id, school, marks, passing_year, reference_no, status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
        $stmt->bindParam(1, $student_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $course_id, PDO::PARAM_INT);
        $stmt->bindParam(3, $school, PDO::PARAM_STR);
        $stmt->bindParam(4, $marks, PDO::PARAM_STR);
        $stmt->bindParam(5, $passing_year, PDO::PARAM_INT);
        $stmt->bindParam(6, $ref_no, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $success = "Application submitted successfully. Your Reference No: <strong>$ref_no</strong>";
        } else {
            $error = "Submission failed. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Application</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Submit Admission Application</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="post" action="submit_application.php">
        <div class="mb-3">
            <label for="course_id" class="form-label">Select Program</label>
            <select name="course_id" id="course_id" class="form-select" required>
                <option value="">-- Select --</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?= $course['id'] ?>" <?= (($_POST['course_id'] ?? '') == $course['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($course['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="school" class="form-label">Previous School</label>
            <input type="text" class="form-control" name="school" id="school" required value="<?= htmlspecialchars($_POST['school'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="marks" class="form-label">Marks (%)</label>
            <input type="number" step="0.01" class="form-control" name="marks" id="marks" required value="<?= htmlspecialchars($_POST['marks'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="passing_year" class="form-label">Year of Passing</label>
            <input type="number" class="form-control" name="passing_year" id="passing_year" required value="<?= htmlspecialchars($_POST['passing_year'] ?? '') ?>">
        </div>
        <button type="submit" class="btn btn-success w-100">Submit Application</button>
    </form>
</div>

</body>
</html>
