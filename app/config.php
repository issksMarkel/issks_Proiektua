<?php
define('DB_HOST', 'db');
define('DB_USER', 'admin');
define('DB_PASS', 'test');
define('DB_NAME', 'database');

function getConnection() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Datu basearekin konexioan errorea egon da!!!");
    }
    return $conn;
}

function validarNAN($nan) {
    if (!preg_match('/^[0-9]{8}-[A-Z]$/', $nan)) return false;
    $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
    return $letras[intval(substr($nan, 0, 8)) % 23] === substr($nan, 9, 1);
}
?>
