<?php

session_start();
require_once 'security_headers.php';

if (isset($_SESSION['user_id'])) {
    header('Location: elements.php');
} else {
    header('Location: login.php');
}
exit;
?>
