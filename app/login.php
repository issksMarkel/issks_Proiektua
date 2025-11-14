<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: elements.php');
    exit;
}

$conn = getConnection();
$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    // Validar CSRF token
    validarTokenCSRF($_POST['csrf_token']);
    
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $result = mysqli_query($conn, "SELECT * FROM usuarios WHERE email = '$email'");
    
    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            // Regenerar token después del login
            unset($_SESSION['csrf_token']);
            header('Location: elements.php');
            exit;
        }
    }
    $login_error = "Email edo pasahitz okerra";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saioa Hasi</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Saioa Hasi</h1>
        
        <?php if ($login_error): ?>
            <div class="error"><?= $login_error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= generarTokenCSRF() ?>">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Pasahitza:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" name="login" class="btn">Saioa Hasi</button>
            <p>Ez duzu konturik? <a href="register.php">Erregistratu</a></p>
        </form>
    </div>
</body>
</html>
