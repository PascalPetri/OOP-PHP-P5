<?php

//Gemaakt door pascal
// Datum 18-12-2025

session_start();

// Database config
define('DB_HOST', 'localhost');
define('DB_NAME', '2fa_system');
define('DB_USER', 'root');
define('DB_PASS', '');

// App config
define('APP_NAME', '2FA SYSTEEM');
define('SITE_URL', 'http://localhost/login2fa');

// Verwijder of comment deze regels uit als je ze hebt:
// require_once 'GoogleAuthenticator.php';
// $ga = new GoogleAuthenticator();
// use PHPGangsta\GoogleAuthenticator;

// Database connection
function getDB() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}

// Helper functions
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['logged_in']);
}

function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Auto-install database
function installDatabase() {
    try {
        $pdo = getDB();
        
        $check = $pdo->query("SHOW TABLES LIKE 'users'")->fetch();
        
        if (!$check) {
            $sql = "CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                twofa_secret VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            
            $pdo->exec($sql);
            return "Database table created successfully!";
        }
        
        return "Database is ready.";
    } catch(Exception $e) {
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
            $pdo->exec("USE " . DB_NAME);
            
            $sql = "CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                twofa_secret VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            
            $pdo->exec($sql);
            return "Database and table created successfully!";
        } catch(PDOException $ex) {
            return "Installation error: " . $ex->getMessage();
        }
    }
}

$installResult = installDatabase();
?>