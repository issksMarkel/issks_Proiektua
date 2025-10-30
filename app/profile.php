<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_pokemon'])) {
    $pokemon_name = $_POST['pokemon_name'];
    $stmt = $conn->prepare("DELETE FROM erabiltzaile_pokemon WHERE usuario_id = ? AND elementu_izena = ?");
    $stmt->execute([$_SESSION['user_id'], $pokemon_name]);
    header("Location: profile.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM erabiltzaile WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$stmt = $conn->prepare("
    SELECT p.* 
    FROM pokemon p 
    INNER JOIN erabiltzaile_pokemon ep ON p.izena = ep.elementu_izena 
    WHERE ep.usuario_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$pokemons = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $izena = $_POST['izena'];
    $telefono = $_POST['telefono'];
    $jaiotze_data = $_POST['jaiotze_data'];
    $email = $_POST['email'];
    
    $stmt = $conn->prepare("SELECT id FROM erabiltzaile WHERE email = ? AND id != ?");
    $stmt->execute([$email, $user_id]);
    if ($stmt->rowCount() > 0) {
        $error_message = "Email hau beste erabiltzaile batek erabiltzen du";
    } else {
        $stmt = $conn->prepare("UPDATE erabiltzaile SET 
                              izena = ?,
                              telefono = ?,
                              jaiotze_data = ?,
                              email = ?
                              WHERE id = ?");
        
        if ($stmt->execute([$izena, $telefono, $jaiotze_data, $email, $user_id])) {
            $_SESSION['email'] = $email;
            $success_message = "Datuak eguneratu dira!";
            $stmt = $conn->prepare("SELECT * FROM erabiltzaile WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
        } else {
            $error_message = "Errorea datuak eguneratzean";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($current_password === $user['pasahitza']) {
        if ($new_password === $confirm_password) {
            if (strlen($new_password) >= 6) {
                $stmt = $conn->prepare("UPDATE erabiltzaile SET pasahitza = ? WHERE id = ?");
                
                if ($stmt->execute([$new_password, $user_id])) {
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

        <?php if (isset($success_message)): ?>
            <div class="success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <!-- Personal data form -->
        <div class="profile-section">
            <h2>Datu Pertsonalak</h2>
            <form method="POST" onsubmit="return validarDatosPersonales()">
                <div class="form-group">
                    <label for="izena">Izen abizenak:</label>
                    <input type="text" id="izena" name="izena" value="<?= htmlspecialchars($user['izena']) ?>" required>
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
                    <label for="jaiotze_data">Jaiotze data:</label>
                    <input type="date" id="jaiotze_data" name="jaiotze_data" value="<?= htmlspecialchars($user['jaiotze_data']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <button type="submit" name="update_profile" class="btn">Gorde Aldaketak</button>
            </form>
        </div>

        <!-- Password change form -->
        <div class="profile-section">
            <h2>Pasahitza Aldatu</h2>
            <form method="POST" onsubmit="return validarPassword()">
                <div class="form-group">
                    <label for="current_password">Oraingo pasahitza:</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>

                <div class="form-group">
                    <label for="new_password">Pasahitz berria:</label>
                    <input type="password" id="new_password" name="new_password" required minlength="6">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Berretsi pasahitza:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                </div>

                <button type="submit" name="change_password" class="btn">Pasahitza Aldatu</button>
            </form>
        </div>

        <!-- Account information -->
        <div class="profile-section">
            <h2>Kontu Informazioa</h2>
            <p><strong>Erregistro data:</strong> <?= date('Y-m-d H:i', strtotime($user['erregistro_data'])) ?></p>
        </div>

        <h2>Zure Pokemon:</h2>
        <div class="pokemon-list">
            <?php foreach ($pokemons as $pokemon): ?>
                <div class="pokemon-card">
                    <h3><?php echo htmlspecialchars($pokemon['izena']); ?></h3>
                    <p>Mota: <?php echo htmlspecialchars($pokemon['mota']); ?></p>
                    <p>Bizitza: <?php echo htmlspecialchars($pokemon['bizitza']); ?></p>
                    <p>Erasoa: <?php echo htmlspecialchars($pokemon['erasoa']); ?></p>
                    <p>Defentsa: <?php echo htmlspecialchars($pokemon['defentsa']); ?></p>
                    <form method="POST">
                        <input type="hidden" name="pokemon_name" value="<?php echo htmlspecialchars($pokemon['izena']); ?>">
                        <button type="submit" name="delete_pokemon" class="delete-btn">Pokemon kendu</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="scripts.js"></script>
</body>
</html>
