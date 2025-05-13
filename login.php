<?php
session_start();
require_once "db_connection.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (!empty($email) && !empty($password)) {
        // Prepare the SQL statement using PDO
        $stmt = $conn->prepare("SELECT id, name, password FROM students WHERE email = :email");

        // Bind the parameters (note that PDO uses named placeholders)
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        // Execute the query
        $stmt->execute();

        // Check if exactly one result is found
        if ($stmt->rowCount() === 1) {
            // Fetch the result
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $id = $user['id'];
            $name = $user['name'];
            $hashed_password = $user['password'];

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Login successful
                $_SESSION["student_id"] = $id;
                $_SESSION["student_name"] = $name;
                header("Location: student_dashboard.php");
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "Email not found.";
        }
    } else {
        $error = "Please enter both email and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Student Login</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="login.php">
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <p class="text-center mt-3">Don't have an account? <a href="register.php">Register here</a>.</p>
</div>

</body>
</html>
