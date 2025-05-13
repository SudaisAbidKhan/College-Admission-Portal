<?php
// Include the config file for database credentials
require_once 'config.php';

// Create a function to establish a connection to the database using PDO
function getDB() {
    // Globalize database connection settings
    global $conn;
    
    try {
        // Create a new PDO instance for the database connection
        $conn = new PDO(
            "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8", 
            DB_USERNAME, 
            DB_PASSWORD
        );
        
        // Set PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Return the connection object
        return $conn;
    } catch (PDOException $e) {
        // Handle connection errors
        die("Connection failed: " . $e->getMessage());
    }
}

// Get the DB connection
getDB();
?>
