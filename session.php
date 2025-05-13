<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include the config file for session timeout setting
require_once 'config.php';

// Function to check session timeout and invalidate the session if expired
function checkSessionTimeout() {
    // If the session has a timestamp of when it was last active
    if (isset($_SESSION['LAST_ACTIVITY'])) {
        // Check if the session has timed out
        if ((time() - $_SESSION['LAST_ACTIVITY']) > SESSION_TIMEOUT) {
            // If timeout has occurred, destroy session and redirect to login
            session_unset();     // Unset all session variables
            session_destroy();   // Destroy the session
            header("Location: login.php"); // Redirect to login page
            exit();
        }
    }
    
    // Update the last activity timestamp
    $_SESSION['LAST_ACTIVITY'] = time();
}

// Function to validate user login and prevent unauthorized access
function validateUserLogin() {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Redirect to login page if not logged in
        header("Location: login.php");
        exit();
    }
}

// Function to validate admin login and prevent unauthorized access
function validateAdminLogin() {
    // Check if the admin is logged in
    if (!isset($_SESSION['admin'])) {
        // Redirect to admin login page if not logged in
        header("Location: admin_login.php");
        exit();
    }
}

// Function to prevent session hijacking
function preventSessionHijacking() {
    // Check if the user has an existing session and if the user agent matches
    if (isset($_SESSION['USER_AGENT']) && $_SESSION['USER_AGENT'] != $_SERVER['HTTP_USER_AGENT']) {
        // If the user agent doesn't match, destroy the session (potential session hijacking)
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }

    // Save the current user agent to prevent hijacking
    $_SESSION['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
}

// Function to regenerate session ID to prevent session fixation attacks
function regenerateSessionId() {
    if (session_status() == PHP_SESSION_ACTIVE) {
        session_regenerate_id(true); // Regenerate the session ID to prevent fixation
    }
}

// Call the necessary functions to handle session management
checkSessionTimeout();          // Check for session timeout
preventSessionHijacking();     // Prevent session hijacking
regenerateSessionId();         // Regenerate session ID to prevent session fixation

?>
