<?php
session_start();
require_once 'config.php';

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
        $check = mysqli_query($conn, "SELECT id FROM usuarios WHERE nan = '$nan'");
        
        if (mysqli_num_rows($check) > 0) {
            $register_error = "NAN hau dagoeneko erregistratuta dago";
        } elseif (strlen($_POST['password']) < 8) {
            $register_error = "Pasahitzak gutxienez 8 karaktere izan behar ditu";
        } else {
            $query = sprintf(
<<<<<<< HEAD
                "INSERT INTO erabiltzaile (izena, nan, telefono, jaiotze_data, email, pasahitza) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')",
=======
                "INSERT INTO usuarios (nombre, nan, telefono, fecha_nacimiento, email, password) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')",
>>>>>>> temp-branch
                mysqli_real_escape_string($conn, $_POST['izena']),
                $nan,
                mysqli_real_escape_string($conn, $_POST['telefono']),
                mysqli_real_escape_string($conn, $_POST['jaiotze_data']),
                mysqli_real_escape_string($conn, $_POST['email']),
                password_hash($_POST['password'], PASSWORD_DEFAULT)
            );
            
            if (mysqli_query($conn, $query)) {
                $_SESSION['user_id'] = mysqli_insert_id($conn);
                $_SESSION['email'] = $_POST['email'];
                header('Location: elements.php');
                exit;
            } else {
                $register_error = "Errorea erregistratzerakoan: " . mysqli_error($conn);
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
                <label>Izena:</label>
                <input type="text" name="izena" id="izena" required>
            </div>
            <div class="form-group">
                <label>NAN:</label>
                <input type="text" name="nan" id="nan" placeholder="12345678-A" required>
            </div>
            <div class="form-group">
                <label>Telefonoa:</label>
                <input type="tel" name="telefono" id="telefono" placeholder="612345678" required>
            </div>
            <div class="form-group">
                <label>Jaiotze data:</label>
                <input type="date" name="jaiotze_data" id="jaiotze_data" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label>Pasahitza:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" name="register" class="btn">Erregistratu</button>
            <p>Jadanik kontua duzu? <a href="login.php">Saioa Hasi</a></p>
        </form>
    </div>

    <script>
        function validatuFormularioa() {
            const nan = document.getElementById('nan').value;
            const tel = document.getElementById('telefono').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!/^[0-9]{8}-[A-Z]$/.test(nan)) {
                alert('NAN formatua okerra da (12345678-A)');
                return false;
            }
            if (!/^[0-9]{9}$/.test(tel)) {
                alert('Telefono formatua okerra da (9 zenbaki)');
                return false;
            }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                alert('Email formatua okerra da');
                return false;
            }
            if (password.length < 8) {
                alert('Pasahitzak gutxienez 8 karaktere izan behar ditu');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
