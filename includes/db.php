<?php

// Database connection variables
$host = 'localhost';      
$db   = 'user_management'; 
$user = 'root';           
$pass = '';               
$charset = 'utf8mb4';     


// Data Source Name (DSN) is a variable 
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// PDO options 
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch results as associative arrays
];


// Try to create a PDO connection
try {
    // Create a new PDO instance
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // If connection fails, stop the script and display error
    die("Database connection failed: " . $e->getMessage());
}

?>
