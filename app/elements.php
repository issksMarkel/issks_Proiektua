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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_element'])) {
    $elementu = mysqli_real_escape_string($conn, $_POST['elementu_izena']);
    $check = mysqli_query($conn, "SELECT * FROM usuario_elementuak WHERE usuario_id = {$_SESSION['user_id']} AND elementu_izena = '$elementu'");
    
    if (mysqli_num_rows($check) > 0) {
        $error_message = "Elementu hau dagoeneko zerrenda";
    } else {
        mysqli_query($conn, "INSERT INTO usuario_elementuak (usuario_id, elementu_izena) VALUES ({$_SESSION['user_id']}, '$elementu')");
        $success_message = "Elementua gehitu da!";
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

        <?php if ($error_message): ?>
            <div class="error"><?= htmlspecialchars($error_message) ?></div>
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
<?php $conn->close(); ?>
