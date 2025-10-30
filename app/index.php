<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: elements.php');
} else {
    header('Location: login.php');
}
exit;
?>
