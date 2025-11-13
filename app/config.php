<?php
// Database configuration for Docker
define('DB_HOST', 'db');
define('DB_USER', 'admin');
define('DB_PASS', 'test');
define('DB_NAME', 'database');

// PDO connection (used by most of your files)
try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        )
    );
} catch(PDOException $e) {
    die("Errorea konexioan: " . $e->getMessage());
}

// MySQLi connection function (for login.php and register.php)
function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Datu basearekin konexioan errorea egon da: " . $conn->connect_error);
    }
    return $conn;
}

function validarNAN($nan) {
    if (!preg_match('/^[0-9]{8}-[A-Z]$/', $nan)) return false;
    $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
    return $letras[intval(substr($nan, 0, 8)) % 23] === substr($nan, 9, 1);
}
?>
