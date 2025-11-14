<?php
define('DB_HOST', 'db');
define('DB_USER', 'admin');
define('DB_PASS', 'test');
define('DB_NAME', 'database');

// Agregar cabeceras de seguridad SIN unsafe-inline
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; font-src 'self'; connect-src 'self'; form-action 'self'; frame-ancestors 'none';");

function getConnection() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function validarNAN($nan) {
    // Validar formato NAN (8 números + 1 letra)
    if (preg_match('/^[0-9]{8}[A-Z]$/', $nan)) {
        $numeros = substr($nan, 0, 8);
        $letra = substr($nan, 8, 1);
        $letras_validas = 'TRWAGMYFPDXBNJZSQVHLCKE';
        $letra_esperada = $letras_validas[$numeros % 23];
        return $letra === $letra_esperada;
    }
    return false;
}
?>