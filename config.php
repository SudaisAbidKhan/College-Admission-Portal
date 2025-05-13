<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database connection settings
define('DB_SERVER', 'localhost');      // Database server (usually 'localhost')
define('DB_USERNAME', 'root');         // Database username
define('DB_PASSWORD', '');             // Database password (keep empty for local development)
define('DB_NAME', 'college_portal');   // Database name (replace with your actual database name)

// SMTP settings for email notifications (mockup)
define('SMTP_SERVER', 'smtp.gmail.com'); // Example SMTP server (replace with actual one)
define('SMTP_PORT', 587);                // SMTP Port for TLS
define('SMTP_USERNAME', 'your-email@gmail.com'); // Replace with actual SMTP username (your email)
define('SMTP_PASSWORD', 'your-email-password');  // Replace with actual SMTP password
define('SMTP_FROM_EMAIL', 'no-reply@collegeportal.com'); // Sender's email address
define('SMTP_FROM_NAME', 'College Admission Portal');  // Sender's name

// Site Settings
define('SITE_NAME', 'College Admission Portal');  // The name of the site
define('SITE_URL', 'http://localhost/college-portal'); // URL of the site (update for production)
define('SITE_EMAIL', 'info@collegeportal.com'); // Email address for general inquiries

// Admin settings
define('ADMIN_USERNAME', 'admin');   // Default admin username (for login)
define('ADMIN_PASSWORD', 'admin123'); // Default admin password (for login)

// Security settings
define('SESSION_TIMEOUT', 3600); // Timeout for user session (in seconds)

// File upload settings
define('UPLOAD_DIR', 'uploads/'); // Directory for file uploads
define('MAX_FILE_SIZE', 10485760); // Maximum file size for uploads (10MB)

// Application status constants
define('STATUS_ACCEPTED', 'Accepted');
define('STATUS_REJECTED', 'Rejected');
define('STATUS_PENDING', 'Pending');

// Miscellaneous
define('DATE_FORMAT', 'Y-m-d H:i:s');  // Format for date and time
define('TIMEZONE', 'America/New_York'); // Default time zone (change based on location)

// Error reporting (enable or disable for development/production)
define('DISPLAY_ERRORS', true); // Set to false in production

// Error handling (use custom error handler if needed)
if (DISPLAY_ERRORS) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// Timezone configuration
date_default_timezone_set(TIMEZONE);

?>
