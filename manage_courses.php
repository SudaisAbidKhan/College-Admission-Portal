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

// Handle Add, Edit, and Delete actions
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["add_course"])) {
        $course_name = trim($_POST["course_name"]);

        if (empty($course_name)) {
            $error = "Course name is required.";
        } else {
            $stmt = $conn->prepare("INSERT INTO courses (name) VALUES (:name)");
            if ($stmt->execute([':name' => $course_name])) {
                $success = "Course added successfully.";
            } else {
                $error = "Failed to add the course.";
            }
        }
    }

    if (isset($_POST["edit_course"])) {
        $course_id = $_POST["course_id"];
        $course_name = trim($_POST["course_name"]);

        if (empty($course_name)) {
            $error = "Course name is required.";
        } else {
            $stmt = $conn->prepare("UPDATE courses SET name = :name WHERE id = :id");
            if ($stmt->execute([':name' => $course_name, ':id' => $course_id])) {
                $success = "Course updated successfully.";
            } else {
                $error = "Failed to update the course.";
            }
        }
    }

    if (isset($_POST["delete_course"])) {
        $course_id = $_POST["course_id"];
        $stmt = $conn->prepare("DELETE FROM courses WHERE id = :id");
        if ($stmt->execute([':id' => $course_id])) {
            $success = "Course deleted successfully.";
        } else {
            $error = "Failed to delete the course.";
        }
    }
}

// Fetch existing courses
$result = $conn->query("SELECT * FROM courses");
$courses_result = $result->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Courses</title>
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
    <h2 class="mb-4">Manage Courses</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title">Add New Course</h5>
        </div>
        <div class="card-body">
            <form method="post" action="manage_courses.php">
                <div class="mb-3">
                    <label for="course_name" class="form-label">Course Name</label>
                    <input type="text" class="form-control" id="course_name" name="course_name" required>
                </div>
                <button type="submit" class="btn btn-primary" name="add_course">Add Course</button>
            </form>
        </div>
    </div>

    <h3>Existing Courses</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Course Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($courses_result as $course): ?>
                <tr>
                    <td><?= htmlspecialchars($course["name"]) ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editCourseModal"
                                data-id="<?= $course["id"] ?>" data-name="<?= htmlspecialchars($course["name"]) ?>">
                            Edit
                        </button>

                        <form method="post" action="manage_courses.php" class="d-inline">
                            <input type="hidden" name="course_id" value="<?= $course["id"] ?>">
                            <button type="submit" class="btn btn-danger btn-sm" name="delete_course"
                                    onclick="return confirm('Are you sure you want to delete this course?');">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="editCourseModal" tabindex="-1" aria-labelledby="editCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="manage_courses.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCourseModalLabel">Edit Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="course_id" id="edit_course_id">
                    <div class="mb-3">
                        <label for="edit_course_name" class="form-label">Course Name</label>
                        <input type="text" class="form-control" id="edit_course_name" name="course_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="edit_course">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editCourseModal = document.getElementById('editCourseModal');
    editCourseModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const courseId = button.getAttribute('data-id');
        const courseName = button.getAttribute('data-name');

        document.getElementById('edit_course_id').value = courseId;
        document.getElementById('edit_course_name').value = courseName;
    });
});
</script>

</body>
</html>
