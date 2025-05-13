<?php
session_start();
require_once "db_connection.php";

// Redirect if not logged in
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

// Default response
$error = "";
$success = "";

// Function to send Email notification
function sendEmail($recipient, $subject, $message) {
    $headers = "From: no-reply@collegeportal.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    // Mock SMTP settings (this would be an actual SMTP setup in a real-world application)
    $smtp_server = "smtp.gmail.com"; // Example SMTP server (use actual server settings)
    $smtp_port = 587;
    $smtp_username = "your-email@gmail.com"; // Replace with your SMTP email
    $smtp_password = "your-email-password"; // Replace with your SMTP password

    // Mail setup (use PHP mail() or external libraries like PHPMailer for actual implementation)
    $smtp_sent = mail($recipient, $subject, $message, $headers);

    return $smtp_sent;
}

// Function to send SMS notification (mockup)
function sendSMS($phone_number, $message) {
    // Mockup: Display message for demo purposes
    // You can replace this with an actual SMS API like Twilio, Nexmo, etc.
    $mock_sms_api_url = "https://api.mock-sms.com/send"; // Replace with real API URL
    $mock_sms_api_key = "your-api-key"; // Replace with your API key

    // Simulate sending SMS
    echo "Sending SMS to $phone_number: $message\n";
    // In a real-world scenario, this would be a request to the SMS API

    return true;
}

// Send notifications for application updates
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["send_notification"]) && isset($_POST["application_id"])) {
        $application_id = $_POST["application_id"];
        $status = $_POST["status"];

        // Fetch application data
        $stmt = $conn->prepare("SELECT * FROM applications WHERE id = ?");
        $stmt->bind_param("i", $application_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $application = $result->fetch_assoc();
        $stmt->close();

        if ($application) {
            // Prepare the message
            $student_name = $application["student_name"];
            $email = $application["email"];
            $phone_number = $application["phone_number"];
            $course = $application["course"];
            $message = "Dear $student_name, your application for $course has been updated to '$status'.\nThank you for applying.";

            // Send Email
            $email_sent = sendEmail($email, "Application Status Update", $message);
            if (!$email_sent) {
                $error = "Failed to send email notification.";
            } else {
                $success = "Email notification sent successfully to $email.";
            }

            // Send SMS (Mockup)
            $sms_sent = sendSMS($phone_number, $message);
            if ($sms_sent) {
                $success .= " SMS notification sent to $phone_number.";
            } else {
                $error = "Failed to send SMS notification.";
            }
        } else {
            $error = "Application not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Notifications</title>
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

<!-- Send Notifications -->
<div class="container mt-5">
    <h2 class="mb-4">Send Notifications</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <!-- Send Notification Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title">Send Application Status Update</h5>
        </div>
        <div class="card-body">
            <form method="post" action="send_notifications.php">
                <div class="mb-3">
                    <label for="application_id" class="form-label">Application ID</label>
                    <input type="number" class="form-control" id="application_id" name="application_id" required>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Application Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="Accepted">Accepted</option>
                        <option value="Rejected">Rejected</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" name="send_notification">Send Notification</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
