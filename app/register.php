<?php
session_start();
require_once 'config.php';

// Si ya está logueado, redirigir a elements
if (isset($_SESSION['user_id'])) {
    header('Location: elements.php');
    exit;
}

$conn = getConnection();
$register_error = '';

// Procesar registro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $nan = mysqli_real_escape_string($conn, $_POST['nan']);
    
    if (validarNAN($nan)) {
        $check = mysqli_query($conn, "SELECT id FROM erabiltzaile WHERE nan = '$nan'");
        
        if (mysqli_num_rows($check) > 0) {
            $register_error = "NAN hau dagoeneko erregistratuta dago";
        } else {
            $query = sprintf(
                "INSERT INTO erabil (nombre, nan, telefono, fecha_nacimiento, email, password) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')",
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
                $_SESSION['success'] = "Ongi etorri! Zure kontua sortu da.";
                header('Location: elements.php');
                exit;
            } else {
                $register_error = "Errorea erregistroan";
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
        
        <?php if ($register_error): ?>
            <div class="error"><?= $register_error ?></div>
        <?php endif; ?>
        
        <form method="POST" onsubmit="return validatuFormularioa()">
            <div class="form-group">
                <label for="izena">Izen abizenak:</label>
                <input type="text" id="izena" name="izena" required>
            </div>
            <div class="form-group">
                <label for="nan">NAN:</label>
                <input type="text" id="nan" name="nan" required pattern="[0-9]{8}-[A-Z]" placeholder="12345678-Z">
            </div>
            <div class="form-group">
                <label for="telefonoa">Telefonoa:</label>
                <input type="tel" id="telefonoa" name="telefonoa" required pattern="[0-9]{9}" placeholder="612345678">
            </div>
            <div class="form-group">
                <label for="jaiotze_data">Jaiotze data:</label>
                <input type="date" id="jaiotze_data" name="jaiotze_data" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Pasahitza:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" name="register" class="btn">Erregistratu</button>
            <p>Jadanik kontua duzu? <a href="login.php">Saioa Hasi</a></p>
        </form>
    </div>

    <script>
        function validatuFormularioa() {
            const nan = document.getElementById('nan').value;
            const tel = document.getElementById('telefonoa').value;
            const email = document.getElementById('email').value;
            
            if (!/^[0-9]{8}-[A-Z]$/.test(nan)) {
                alert('NAN formatu okerra (Adibidez: 12345678-Z)');
                return false;
            }
            if (!/^[0-9]{9}$/.test(tel)) {
                alert('Telefonoak 9 zenbaki izan behar ditu');
                return false;
            }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                alert('Email formatu okerra');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>