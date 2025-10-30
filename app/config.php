<?php
// Configuración de la base de datos
define('DB_HOST', 'db');
define('DB_USER', 'admin');
define('DB_PASS', 'test');
define('DB_NAME', 'database');

// Función para conectar a la base de datos
function getConnection() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Error de conexión a la base de datos");
    }
    return $conn;
}

// Función para validar NAN
function validarNAN($nan) {
    if (!preg_match('/^[0-9]{8}-[A-Z]$/', $nan)) return false;
    $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
    return $letras[intval(substr($nan, 0, 8)) % 23] === substr($nan, 9, 1);
}

// Configuración de la base de datos PDO
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'database';

try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>