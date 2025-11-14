<?php
// Configuración de sesión debe ir PRIMERO
require_once 'config.php';

// Ahora iniciar sesión
session_start();
require_once 'security_headers.php';

if (isset($_SESSION['user_id'])) {
    header('Location: elements.php');
    exit;
}

$conn = getConnection();
// INICIALIZAR la variable para evitar el warning
$register_error = '';

// Procesar registro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    // Validar CSRF token
    validarTokenCSRF($_POST['csrf_token']);
    
    $nan = mysqli_real_escape_string($conn, $_POST['nan']);
    
    if (validarNAN($nan)) {
        $check = mysqli_query($conn, "SELECT id FROM usuarios WHERE nan = '$nan'");
        
        if (mysqli_num_rows($check) > 0) {
            $register_error = "NAN hau dagoeneko erregistratuta dago";
        } else {
            // Validar que todos los campos requeridos están presentes
            $required_fields = ['izena', 'telefonoa', 'jaiotze_data', 'email', 'password'];
            $missing_fields = [];
            foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    $missing_fields[] = $field;
                }
            }
            
            if (!empty($missing_fields)) {
                $register_error = "Datu guztiak bete behar dira";
            } else {
                $query = sprintf(
                    "INSERT INTO usuarios (nombre, nan, telefono, fecha_nacimiento, email, password) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')",
                    mysqli_real_escape_string($conn, $_POST['izena']),
                    $nan,
                    mysqli_real_escape_string($conn, $_POST['telefonoa']),
                    mysqli_real_escape_string($conn, $_POST['jaiotze_data']),
                    mysqli_real_escape_string($conn, $_POST['email']),
                    password_hash($_POST['password'], PASSWORD_DEFAULT)
                );
                
                if (mysqli_query($conn, $query)) {
                    $_SESSION['user_id'] = mysqli_insert_id($conn);
                    $_SESSION['email'] = $_POST['email'];
                    // Regenerar token después del registro
                    unset($_SESSION['csrf_token']);
                    $_SESSION['success'] = "Ongi etorri! Zure kontua sortu da.";
                    header('Location: elements.php');
                    exit;
                } else {
                    $register_error = "Errorea erregistroan: " . mysqli_error($conn);
                }
            }
        }
    } else {
        $register_error = "NAN formatu okerra";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erregistratu</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Erregistratu</h1>
        
        <?php if (!empty($register_error)): ?>
            <div class="error"><?= htmlspecialchars($register_error) ?></div>
        <?php endif; ?>
        
        <form method="POST" id="registerForm">
            <input type="hidden" name="csrf_token" value="<?= generarTokenCSRF() ?>">
            <div class="form-group">
                <label for="izena">Izen abizenak:</label>
                <input type="text" id="izena" name="izena" value="<?= isset($_POST['izena']) ? htmlspecialchars($_POST['izena']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="nan">NAN:</label>
                <input type="text" id="nan" name="nan" value="<?= isset($_POST['nan']) ? htmlspecialchars($_POST['nan']) : '' ?>" required pattern="[0-9]{8}-[A-Z]" placeholder="12345678-Z">
            </div>
            <div class="form-group">
                <label for="telefonoa">Telefonoa:</label>
                <input type="tel" id="telefonoa" name="telefonoa" value="<?= isset($_POST['telefonoa']) ? htmlspecialchars($_POST['telefonoa']) : '' ?>" required pattern="[0-9]{9}" placeholder="612345678">
            </div>
            <div class="form-group">
                <label for="jaiotze_data">Jaiotze data:</label>
                <input type="date" id="jaiotze_data" name="jaiotze_data" value="<?= isset($_POST['jaiotze_data']) ? htmlspecialchars($_POST['jaiotze_data']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label>Pasahitza:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" name="register" class="btn">Erregistratu</button>
            <p>Jadanik kontua duzu? <a href="login.php">Saioa Hasi</a></p>
        </form>
    </div>

    <script src="validation.js"></script>
</body>
</html>
