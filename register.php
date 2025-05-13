<?php
session_start();
require_once "db_connection.php";

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name       = trim($_POST["name"]);
    $email      = trim($_POST["email"]);
    $phone      = trim($_POST["phone"]);
    $password   = $_POST["password"];
    $confirm_pw = $_POST["confirm_password"];

    // Basic validation
    if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($confirm_pw)) {
        $errors[] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } elseif ($password !== $confirm_pw) {
        $errors[] = "Passwords do not match.";
    }

    // Check if user already exists
    if (empty($errors)) {
        $check = $conn->prepare("SELECT id FROM students WHERE email = :email");
        $check->bindParam(':email', $email, PDO::PARAM_STR);
        $check->execute();
        if ($check->rowCount() > 0) {
            $errors[] = "Email already registered.";
        }
        $check = null;
    }

    // Register user
    if (empty($errors)) {
        $hashed_pw = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO students (name, email, phone, password) VALUES (:name, :email, :phone, :password)");
        
        // Bind parameters
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashed_pw, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            $success = "Registration successful. You can now <a href='login.php'>login</a>.";
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
        $stmt = null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Student Registration</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <?= $success ?>
        </div>
    <?php endif; ?>

    <form method="post" action="register.php" novalidate>
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" name="name" id="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" name="email" id="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="tel" class="form-control" name="phone" id="phone" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Create Password</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>

    <p class="text-center mt-3">Already registered? <a href="login.php">Login here</a>.</p>
</div>

</body>
</html>
