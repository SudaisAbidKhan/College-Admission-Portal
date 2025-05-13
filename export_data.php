<?php
session_start();
require_once "db_connection.php";

// Redirect if not logged in
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all application data with proper column names
$query = "
    SELECT 
        a.id, 
        s.name AS student_name, 
        s.email, 
        c.name AS course_name, 
        a.status, 
        a.created_at AS application_date
    FROM applications a
    JOIN students s ON a.student_id = s.id
    JOIN courses c ON a.course_id = c.id
";
$stmt = $conn->prepare($query);
$stmt->execute();
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Export to CSV
if (isset($_POST['export_csv'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="applications_report.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Application ID', 'Student Name', 'Email', 'Course', 'Status', 'Application Date']);
    foreach ($applications as $application) {
        fputcsv($output, [
            $application['id'], 
            $application['student_name'], 
            $application['email'], 
            $application['course_name'], 
            $application['status'], 
            $application['application_date']
        ]);
    }
    fclose($output);
    exit();
}

// Export to Excel
if (isset($_POST['export_excel'])) {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="applications_report.xls"');
    echo "<table border='1'>";
    echo "<tr><th>Application ID</th><th>Student Name</th><th>Email</th><th>Course</th><th>Status</th><th>Application Date</th></tr>";
    foreach ($applications as $application) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($application['id']) . "</td>";
        echo "<td>" . htmlspecialchars($application['student_name']) . "</td>";
        echo "<td>" . htmlspecialchars($application['email']) . "</td>";
        echo "<td>" . htmlspecialchars($application['course_name']) . "</td>";
        echo "<td>" . htmlspecialchars($application['status']) . "</td>";
        echo "<td>" . htmlspecialchars($application['application_date']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    exit();
}
?>
