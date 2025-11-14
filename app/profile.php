<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$conn = getConnection();
$success_message = '';
$error_message = '';

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM usuarios WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $fecha_nacimiento = mysqli_real_escape_string($conn, $_POST['fecha_nacimiento']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    $check_email = mysqli_query($conn, "SELECT id FROM usuarios WHERE email = '$email' AND id != $user_id");
    if (mysqli_num_rows($check_email) > 0) {
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
            $result = mysqli_query($conn, $query);
            $user = mysqli_fetch_assoc($result);
        } else {
            $error_message = "Errorea datuak eguneratzean";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    

    if (password_verify($current_password, $user['pasahitza'])) {
        if ($new_password === $confirm_password) {
            if (strlen($new_password) >= 8) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE erabiltzaile SET pasahitza = ? WHERE id = ?");
                
                if ($stmt->execute([$hashed_password, $user_id])) {

    if (password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            if (strlen($new_password) >= 6) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_pass = "UPDATE usuarios SET password = '$hashed_password' WHERE id = $user_id";
                
                if (mysqli_query($conn, $update_pass)) {

                    $success_message = "Pasahitza aldatu da!";
                } else {
                    $error_message = "Errorea pasahitza aldatzean";
                }
            } else {
                $error_message = "Pasahitzak gutxienez 6 karaktere izan behar ditu";
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

        <div class="profile-section">
            <h2>Datu Pertsonalak</h2>
            <form method="POST" onsubmit="return validarDatosPersonales()">
                <div class="form-group">
<<<<<<< HEAD
                    <label>Izena:</label>
                    <input type="text" name="izena" value="<?= htmlspecialchars($user['izena']) ?>" required>
=======
                    <label for="nombre">Izen abizenak:</label>
                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>" required>
>>>>>>> temp-branch
                </div>

                <div class="form-group">
                    <label>NAN:</label>
                    <input type="text" value="<?= htmlspecialchars($user['nan']) ?>" class="readonly-field" readonly>
                    <small class="info-text">NANa ezin da aldatu</small>
                </div>

                <div class="form-group">
                    <label>Telefonoa:</label>
                    <input type="tel" name="telefono" value="<?= htmlspecialchars($user['telefono']) ?>" required>
                </div>

                <div class="form-group">
<<<<<<< HEAD
                    <label>Jaiotze data:</label>
                    <input type="date" name="jaiotze_data" value="<?= htmlspecialchars($user['jaiotze_data']) ?>" required>
=======
                    <label for="fecha_nacimiento">Jaiotze data:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= htmlspecialchars($user['fecha_nacimiento']) ?>" required>
>>>>>>> temp-branch
                </div>

                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <button type="submit" name="update_profile" class="btn">Datuak Gorde</button>
            </form>
        </div>

        <div class="profile-section">
            <h2>Pasahitza Aldatu</h2>
            <form method="POST" onsubmit="return validarPassword()">
                <div class="form-group">
                    <label>Oraingo pasahitza:</label>
                    <input type="password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label>Pasahitz berria:</label>
                    <input type="password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label>Berretsi pasahitz berria:</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit" name="change_password" class="btn">Pasahitza Aldatu</button>
            </form>
        </div>

        <div class="profile-section">
            <h2>Kontu Informazioa</h2>
<<<<<<< HEAD
            <p><strong>Erregistro data:</strong> <?= date('Y-m-d H:i', strtotime($user['erregistro_data'])) ?></p>
        </div>

        <h2>Zure Pokemon:</h2>
        <div class="pokemon-list">
            <?php foreach ($pokemons as $pokemon): ?>
                <div class="pokemon-card">
                    <h3><?= htmlspecialchars($pokemon['izena']) ?></h3>
                    <p><strong>Mota:</strong> <?= htmlspecialchars($pokemon['mota']) ?></p>
                    <p><strong>Bizitza:</strong> <?= htmlspecialchars($pokemon['bizitza']) ?></p>
                    <p><strong>Erasoa:</strong> <?= htmlspecialchars($pokemon['erasoa']) ?></p>
                    <p><strong>Defentsa:</strong> <?= htmlspecialchars($pokemon['defentsa']) ?></p>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="pokemon_name" value="<?= htmlspecialchars($pokemon['izena']) ?>">
                        <button type="submit" name="delete_pokemon" class="delete-btn" onclick="return confirm('Ziur zaude pokemon hau ezabatu nahi duzula?')">Ezabatu</button>
                    </form>
                </div>
            <?php endforeach; ?>
=======
            <p><strong>Erregistro data:</strong> <?= date('Y-m-d H:i', strtotime($user['fecha_registro'])) ?></p>
>>>>>>> temp-branch
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
            
            if (newPass.length < 6) {
                alert('Pasahitzak gutxienez 6 karaktere izan behar ditu');
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
