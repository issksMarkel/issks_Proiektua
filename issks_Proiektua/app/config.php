<?php
define('DB_HOST', 'db');
define('DB_USER', 'admin');
define('DB_PASS', 'test');
define('DB_NAME', 'database');

function getConnection() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Error de conexión a la base de datos");
    }
    return $conn;
}

function validarNAN($nan) {
    if (!preg_match('/^[0-9]{8}-[A-Z]$/', $nan)) return false;
    $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
    return $letras[intval(substr($nan, 0, 8)) % 23] === substr($nan, 9, 1);
}

// Funciones CSRF
function generarTokenCSRF() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validarTokenCSRF($token) {
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        die("Token CSRF inválido");
    }
    return true;
}
?>
