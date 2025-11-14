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

<<<<<<< HEAD
$stmt = $conn->prepare("
    SELECT p.* 
    FROM pokemon p
    INNER JOIN erabiltzaile_pokemon ep ON p.izena = ep.elementu_izena 
    WHERE ep.usuario_id = ?
    ORDER BY p.izena
");
$stmt->execute([$_SESSION['user_id']]);
$user_pokemons = $stmt->fetchAll();

$stmt = $conn->query("SELECT izena, mota FROM pokemon ORDER BY izena");
$all_pokemons = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_pokemon'])) {
        try {
            $stmt = $conn->prepare("INSERT INTO erabiltzaile_pokemon (usuario_id, elementu_izena) VALUES (?, ?)");
            $stmt->execute([$_SESSION['user_id'], $_POST['pokemon_izena']]);
            $_SESSION['success'] = "Pokemona gehitu da!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION['error'] = "Pokemon hau dagoeneko zerrenda";
            } else {
                $_SESSION['error'] = "Errorea pokemona gehitzean";
            }
        }
        header("Location: elements.php");
        exit();
    } elseif (isset($_POST['remove_pokemon'])) {
        $stmt = $conn->prepare("DELETE FROM erabiltzaile_pokemon WHERE usuario_id = ? AND elementu_izena = ?");
        $stmt->execute([$_SESSION['user_id'], $_POST['pokemon_izena']]);
        $_SESSION['success'] = "Pokemona kendu da!";
        header("Location: elements.php");
        exit();
    } elseif (isset($_POST['update_pokemon'])) {
        $stmt = $conn->prepare("
            UPDATE pokemon 
            SET bizitza = ?, erasoa = ?, defentsa = ? 
            WHERE izena = ?
        ");
        $stmt->execute([
            $_POST['bizitza'],
            $_POST['erasoa'],
            $_POST['defentsa'],
            $_POST['izena_original']
        ]);
        $_SESSION['success'] = "Pokemona eguneratu da!";
        header("Location: elements.php");
        exit();
=======
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_element'])) {
    $elementu = mysqli_real_escape_string($conn, $_POST['elementu_izena']);
    $check = mysqli_query($conn, "SELECT * FROM usuario_elementuak WHERE usuario_id = {$_SESSION['user_id']} AND elementu_izena = '$elementu'");
    
    if (mysqli_num_rows($check) > 0) {
        $error_message = "Elementu hau dagoeneko zerrenda";
    } else {
        mysqli_query($conn, "INSERT INTO usuario_elementuak (usuario_id, elementu_izena) VALUES ({$_SESSION['user_id']}, '$elementu')");
        $success_message = "Elementua gehitu da!";
>>>>>>> temp-branch
    }
}

// Borrar elemento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_element'])) {
    $elementu = mysqli_real_escape_string($conn, $_POST['elementu_izena']);
    mysqli_query($conn, "DELETE FROM usuario_elementuak WHERE usuario_id = {$_SESSION['user_id']} AND elementu_izena = '$elementu'");
    $success_message = "Elementua kendu da!";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_element'])) {
    $izena_original = mysqli_real_escape_string($conn, $_POST['izena_original']);
    $check = mysqli_query($conn, "SELECT * FROM usuario_elementuak WHERE usuario_id = {$_SESSION['user_id']} AND elementu_izena = '$izena_original'");
    
    if (mysqli_num_rows($check) > 0) {
        $query = sprintf(
            "UPDATE elementuak SET bizitza=%d, erasoa=%d, defentsa=%d WHERE izena='%s'",
            intval($_POST['bizitza']),
            intval($_POST['erasoa']),
            intval($_POST['defentsa']),
            $izena_original
        );
        mysqli_query($conn, $query);
        $success_message = "Elementua eguneratu da!";
    }
}

$result = mysqli_query($conn, "SELECT e.* FROM elementuak e INNER JOIN usuario_elementuak ue ON e.izena = ue.elementu_izena WHERE ue.usuario_id = {$_SESSION['user_id']} ORDER BY e.izena");

$all_elements = mysqli_query($conn, "SELECT izena, mota FROM elementuak ORDER BY izena");
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nire Elementuak</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="elements.php">Nire Elementuak</a>
            <a href="profile.php">Profila</a>
            <a href="logout.php">Saioa Itxi</a>
        </div>

        <?php if ($success_message): ?>
            <div class="success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>

<<<<<<< HEAD
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <h1>Nire Pokemonak</h1>
=======
        <?php if ($error_message): ?>
            <div class="error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <h1>Nire Elementuak</h1>
>>>>>>> temp-branch
        
        <div class="add-form">
            <h2>Elementu berria gehitu</h2>
            <form method="POST">
                <div class="form-group">
<<<<<<< HEAD
                    <label>Aukeratu pokemona:</label>
                    <select name="pokemon_izena" required>
                        <option value="">Aukeratu pokemona...</option>
                        <?php foreach ($all_pokemons as $pokemon): ?>
                            <option value="<?= htmlspecialchars($pokemon['izena']) ?>">
                                <?= htmlspecialchars($pokemon['izena']) ?> (<?= htmlspecialchars($pokemon['mota']) ?>)
=======
                    <label>Aukeratu elementua:</label>
                    <select name="elementu_izena" required>
                        <option value="">Aukeratu bat...</option>
                        <?php while ($el = mysqli_fetch_assoc($all_elements)): ?>
                            <option value="<?= htmlspecialchars($el['izena']) ?>">
                                <?= htmlspecialchars($el['izena']) ?> (<?= htmlspecialchars($el['mota']) ?>)
>>>>>>> temp-branch
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" name="add_element" class="btn">Gehitu</button>
            </form>
        </div>

<<<<<<< HEAD
        <div class="pokemon-list">
            <h2>Nire zerrenda</h2>
            <?php if (!empty($user_pokemons)): ?>
                <?php foreach ($user_pokemons as $pokemon): ?>
                    <div class="pokemon-card">
                        <h3><?= htmlspecialchars($pokemon['izena']) ?></h3>
                        <p><strong>Mota:</strong> <?= htmlspecialchars($pokemon['mota']) ?></p>
                        <p><strong>Bizitza:</strong> <?= htmlspecialchars($pokemon['bizitza']) ?></p>
                        <p><strong>Erasoa:</strong> <?= htmlspecialchars($pokemon['erasoa']) ?></p>
                        <p><strong>Defentsa:</strong> <?= htmlspecialchars($pokemon['defentsa']) ?></p>
                        <div class="pokemon-actions">
                            <button onclick="editPokemon(<?= htmlspecialchars(json_encode($pokemon)) ?>)" class="btn btn-edit">Editatu</button>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="pokemon_izena" value="<?= htmlspecialchars($pokemon['izena']) ?>">
                                <button type="submit" name="remove_pokemon" class="btn btn-delete" onclick="return confirm('Ziur zaude pokemon hau ezabatu nahi duzula?')">Kendu</button>
=======
        <div class="element-list">
            <h2>Nire zerrenda (<?php $count = mysqli_num_rows($result); echo $count; ?> elementuak)</h2>
            <?php if ($count > 0): ?>
                <?php while ($el = mysqli_fetch_assoc($result)): ?>
                    <?php $json = htmlspecialchars(json_encode($el), ENT_QUOTES, 'UTF-8'); ?>
                    <div class="element-card">
                        <h3><?= htmlspecialchars($el['izena']) ?></h3>
                        <p><strong>Mota:</strong> <?= htmlspecialchars($el['mota']) ?></p>
                        <p><strong>Bizitza:</strong> <?= htmlspecialchars($el['bizitza']) ?></p>
                        <p><strong>Erasoa:</strong> <?= htmlspecialchars($el['erasoa']) ?></p>
                        <p><strong>Defentsa:</strong> <?= htmlspecialchars($el['defentsa']) ?></p>
                        <div class="element-actions">
                            <button class="btn btn-edit" onclick='editElement(<?= $json ?>)'>✏️ Editatu</button>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="elementu_izena" value="<?= htmlspecialchars($el['izena']) ?>">
                                <button type="submit" name="delete_element" class="btn btn-delete" onclick="return confirm('Ziur zaude elementu hau ezabatu nahi duzula?')">🗑️ Ezabatu</button>
>>>>>>> temp-branch
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="empty-message">Ez duzu elementurik oraindik.</p>
            <?php endif; ?>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Elementua Editatu</h2>
            <form method="POST">
                <input type="hidden" name="izena_original" id="izena_original">
                <div class="form-group">
                    <label>Izena:</label>
                    <input type="text" id="modal_izena" class="readonly-field" readonly>
                </div>
                <div class="form-group">
                    <label>Mota:</label>
                    <input type="text" id="modal_mota" class="readonly-field" readonly>
                    <span class="info-text">Mota ezin da aldatu</span>
                </div>
                <div class="form-group">
                    <label>Bizitza:</label>
                    <input type="number" name="bizitza" id="modal_bizitza" min="1" max="999" required>
                </div>
                <div class="form-group">
                    <label>Erasoa:</label>
                    <input type="number" name="erasoa" id="modal_erasoa" min="1" max="999" required>
                </div>
                <div class="form-group">
                    <label>Defentsa:</label>
                    <input type="number" name="defentsa" id="modal_defentsa" min="1" max="999" required>
                </div>
                <button type="submit" name="update_element" class="btn">Gorde</button>
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Utzi</button>
            </form>
        </div>
    </div>

    <script src="scripts.js"></script>
</body>
</html>
<?php $conn->close(); ?>
