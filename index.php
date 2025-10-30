<?php
session_start();

// Redirigir según el estado de sesión
if (isset($_SESSION['user_id'])) {
    header('Location: elements.php');
} else {
    header('Location: login.php');
}
exit;
?>
