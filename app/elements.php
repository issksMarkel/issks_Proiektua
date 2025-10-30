<?php
session_start();
require_once 'config.php';

// Verificar si está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

<<<<<<< HEAD
$stmt = $conn->query("SELECT * FROM pokemon");
$pokemons = $stmt->fetchAll();

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
        $stmt = $conn->prepare("INSERT INTO erabiltzaile_pokemon (usuario_id, elementu_izena) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $_POST['pokemon_izena']]);
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
        header("Location: elements.php");
        exit();
=======
$conn = getConnection();

// Procesar añadir elemento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_element'])) {
    $elementu = mysqli_real_escape_string($conn, $_POST['elementu_izena']);
    mysqli_query($conn, "INSERT IGNORE INTO usuario_elementuak (usuario_id, elementu_izena) VALUES ({$_SESSION['user_id']}, '$elementu')");
    $_SESSION['success'] = "Elementua gehitu da!";
    header('Location: elements.php');
    exit;
}

// Procesar actualizar elemento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_element'])) {
    $izena_original = mysqli_real_escape_string($conn, $_POST['izena_original']);
    $check = mysqli_query($conn, "SELECT * FROM usuario_elementuak WHERE usuario_id = {$_SESSION['user_id']} AND elementu_izena = '$izena_original'");
    
    if (mysqli_num_rows($check) > 0) {
        $query = sprintf(
            "UPDATE elementuak SET izena='%s', bizitza=%d, erasoa=%d, defentsa=%d WHERE izena='%s'",
            mysqli_real_escape_string($conn, $_POST['izena']),
            intval($_POST['bizitza']),
            intval($_POST['erasoa']),
            intval($_POST['defentsa']),
            $izena_original
        );
        mysqli_query($conn, $query);
        
        if ($_POST['izena'] !== $izena_original) {
            mysqli_query($conn, "UPDATE usuario_elementuak SET elementu_izena='{$_POST['izena']}' WHERE usuario_id={$_SESSION['user_id']} AND elementu_izena='$izena_original'");
        }
        $_SESSION['success'] = "Elementua eguneratu da!";
>>>>>>> bbd0d26b0a922afa64144c98ab2631935f560bd9
    }
    header('Location: elements.php');
    exit;
}

// Obtener elementos del usuario
$result = mysqli_query($conn, "SELECT e.* FROM elementuak e INNER JOIN usuario_elementuak ue ON e.izena = ue.elementu_izena WHERE ue.usuario_id = {$_SESSION['user_id']} ORDER BY e.izena");

// Obtener todos los elementos disponibles
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

        <?php if (isset($_SESSION['success'])): ?>
            <div class="success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <h1>Nire Elementuak</h1>
        
        <div class="add-form">
            <h2>Elementu berria gehitu</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Aukeratu elementua:</label>
                    <select name="elementu_izena" required>
                        <option value="">Aukeratu bat...</option>
                        <?php while ($el = mysqli_fetch_assoc($all_elements)): ?>
                            <option value="<?= htmlspecialchars($el['izena']) ?>">
                                <?= htmlspecialchars($el['izena']) ?> (<?= htmlspecialchars($el['mota']) ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" name="add_element" class="btn">Gehitu</button>
            </form>
        </div>

        <div class="element-list">
            <h2>Nire zerrenda</h2>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($el = mysqli_fetch_assoc($result)): ?>
                    <?php $json = htmlspecialchars(json_encode($el), ENT_QUOTES, 'UTF-8'); ?>
                    <div class="element-card">
                        <h3><?= htmlspecialchars($el['izena']) ?></h3>
                        <p><strong>Mota:</strong> <?= htmlspecialchars($el['mota']) ?></p>
                        <button class="btn btn-edit" onclick='editElement(<?= $json ?>)'>✏️ Editatu</button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Ez duzu elementurik oraindik.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal para editar elemento -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Elementua Editatu</h2>
            <form method="POST">
                <input type="hidden" name="izena_original" id="izena_original">
                <div class="form-group">
                    <label>Izena:</label>
                    <input type="text" name="izena" id="modal_izena" required>
                </div>
                <div class="form-group">
                    <label>Mota:</label>
                    <input type="text" id="modal_mota" class="readonly-field" readonly>
                    <span class="info-text">Mota ezin da aldatu</span>
                </div>
                <div class="form-group">
                    <label>Bizitza:</label>
                    <input type="number" name="bizitza" id="modal_bizitza" min="1" required>
                </div>
                <div class="form-group">
                    <label>Erasoa:</label>
                    <input type="number" name="erasoa" id="modal_erasoa" min="1" required>
                </div>
                <div class="form-group">
                    <label>Defentsa:</label>
                    <input type="number" name="defentsa" id="modal_defentsa" min="1" required>
                </div>
                <button type="submit" name="update_element" class="btn">Gorde</button>
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Utzi</button>
            </form>
        </div>
    </div>

    <script src="scripts.js"></script>
</body>
</html>
<<<<<<< HEAD
<?php $conn = null; ?>
=======
<?php $conn->close(); ?>
>>>>>>> bbd0d26b0a922afa64144c98ab2631935f560bd9
