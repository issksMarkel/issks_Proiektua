<?php
session_start();
require_once 'config.php';

<<<<<<< HEAD
=======
// Verificar si está logueado
>>>>>>> bbd0d26b0a922afa64144c98ab2631935f560bd9
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

<<<<<<< HEAD
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_pokemon'])) {
    $pokemon_name = $_POST['pokemon_name'];
    $stmt = $conn->prepare("DELETE FROM erabiltzaile_pokemon WHERE usuario_id = ? AND elementu_izena = ?");
    $stmt->execute([$_SESSION['user_id'], $pokemon_name]);
    header("Location: profile.php");
    exit();
}

=======
$conn = getConnection();
$success_message = '';
$error_message = '';

// Obtener datos actuales del usuario
>>>>>>> bbd0d26b0a922afa64144c98ab2631935f560bd9
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM usuarios WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

<<<<<<< HEAD
$stmt = $conn->prepare("
    SELECT p.* 
    FROM pokemon p 
    INNER JOIN erabiltzaile_pokemon ep ON p.izena = ep.elementu_izena 
    WHERE ep.usuario_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$pokemons = $stmt->fetchAll();

=======
// Procesar actualización de datos
>>>>>>> bbd0d26b0a922afa64144c98ab2631935f560bd9
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $fecha_nacimiento = mysqli_real_escape_string($conn, $_POST['fecha_nacimiento']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
<<<<<<< HEAD
    $stmt = $conn->prepare("SELECT id FROM erabiltzaile WHERE email = ? AND id != ?");
    $stmt->execute([$email, $user_id]);
    if ($stmt->rowCount() > 0) {
=======
    // Verificar si el email ya existe (excepto el del usuario actual)
    $check_email = mysqli_query($conn, "SELECT id FROM usuarios WHERE email = '$email' AND id != $user_id");
    if (mysqli_num_rows($check_email) > 0) {
>>>>>>> bbd0d26b0a922afa64144c98ab2631935f560bd9
        $error_message = "Email hau beste erabiltzaile batek erabiltzen du";
    } else {
        $update_query = "UPDATE usuarios SET 
                        nombre = '$nombre',
                        telefono = '$telefono',
                        fecha_nacimiento = '$fecha_nacimiento',
                        email = '$email'
                        WHERE id = $user_id";
        
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['email'] = $email;
            $success_message = "Datuak eguneratu dira!";
<<<<<<< HEAD
            $stmt = $conn->prepare("SELECT * FROM erabiltzaile WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
=======
            // Recargar datos actualizados
            $result = mysqli_query($conn, $query);
            $user = mysqli_fetch_assoc($result);
>>>>>>> bbd0d26b0a922afa64144c98ab2631935f560bd9
        } else {
            $error_message = "Errorea datuak eguneratzean";
        }
    }
}

<<<<<<< HEAD
=======
// Procesar cambio de contraseña
>>>>>>> bbd0d26b0a922afa64144c98ab2631935f560bd9
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            if (strlen($new_password) >= 8) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_pass = "UPDATE usuarios SET password = '$hashed_password' WHERE id = $user_id";
                
                if (mysqli_query($conn, $update_pass)) {
                    $success_message = "Pasahitza aldatu da!";
                } else {
                    $error_message = "Errorea pasahitza aldatzean";
                }
            } else {
                $error_message = "Pasahitzak gutxienez 8 karaktere izan behar ditu";
            }
        } else {
            $error_message = "Pasahitz berriak ez datoz bat";
        }
    } else {
        $error_message = "Oraingo pasahitza okerra da";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nire Profila</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="elements.php">Nire Elementuak</a>
            <a href="profile.php">Profila</a>
            <a href="logout.php">Saioa Itxi</a>
        </div>

        <h1>Nire Profila</h1>

        <?php if ($success_message): ?>
            <div class="success"><?= $success_message ?></div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="error"><?= $error_message ?></div>
        <?php endif; ?>

        <!-- Formulario de datos personales -->
        <div class="profile-section">
            <h2>Datu Pertsonalak</h2>
            <form method="POST" onsubmit="return validarDatosPersonales()">
                <div class="form-group">
                    <label for="nombre">Izen abizenak:</label>
                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="nan">NAN:</label>
                    <input type="text" id="nan" name="nan" value="<?= htmlspecialchars($user['nan']) ?>" class="readonly-field" readonly>
                    <span class="info-text">NANa ezin da aldatu</span>
                </div>

                <div class="form-group">
                    <label for="telefono">Telefonoa:</label>
                    <input type="tel" id="telefono" name="telefono" value="<?= htmlspecialchars($user['telefono']) ?>" required pattern="[0-9]{9}">
                </div>

                <div class="form-group">
                    <label for="fecha_nacimiento">Jaiotze data:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= htmlspecialchars($user['fecha_nacimiento']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <button type="submit" name="update_profile" class="btn">Gorde Aldaketak</button>
            </form>
        </div>

        <!-- Formulario de cambio de contraseña -->
        <div class="profile-section">
            <h2>Pasahitza Aldatu</h2>
            <form method="POST" onsubmit="return validarPassword()">
                <div class="form-group">
                    <label for="current_password">Oraingo pasahitza:</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>

                <div class="form-group">
                    <label for="new_password">Pasahitz berria:</label>
                    <input type="password" id="new_password" name="new_password" required minlength="8">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Berretsi pasahitza:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
                </div>

                <button type="submit" name="change_password" class="btn">Pasahitza Aldatu</button>
            </form>
        </div>

        <!-- Información de cuenta -->
        <div class="profile-section">
            <h2>Kontu Informazioa</h2>
            <p><strong>Erregistro data:</strong> <?= date('Y-m-d H:i', strtotime($user['fecha_registro'])) ?></p>
        </div>
    </div>

    <script>
        function validarDatosPersonales() {
            const tel = document.getElementById('telefono').value;
            const email = document.getElementById('email').value;
            
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

        function validarPassword() {
            const newPass = document.getElementById('new_password').value;
            const confirmPass = document.getElementById('confirm_password').value;
            
            if (newPass.length < 8) {
                alert('Pasahitzak gutxienez 8 karaktere izan behar ditu');
                return false;
            }
            if (newPass !== confirmPass) {
                alert('Pasahitz berriak ez datoz bat');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
