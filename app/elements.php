<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = getConnection();
$success_message = '';
$error_message = '';T_METHOD'] === 'POST' && isset($_POST['add_element'])) {
    $elementu = mysqli_real_escape_string($conn, $_POST['elementu_izena']);
// Agregar pokémonconn, "INSERT IGNORE INTO usuario_elementuak (usuario_id, elementu_izena) VALUES ({$_SESSION['user_id']}, '$elementu')");
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_pokemon'])) {
    $pokemon_izena = mysqli_real_escape_string($conn, $_POST['pokemon_izena']);
    exit;
    $check = mysqli_query($conn, "SELECT * FROM erabiltzaile_pokemon WHERE usuario_id = {$_SESSION['user_id']} AND elementu_izena = '$pokemon_izena'");
    
    if (mysqli_num_rows($check) > 0) {ST' && isset($_POST['update_element'])) {
        $error_message = "Pokemon hau dagoeneko zerrenda";OST['izena_original']);
    } else { mysqli_query($conn, "SELECT * FROM usuario_elementuak WHERE usuario_id = {$_SESSION['user_id']} AND elementu_izena = '$izena_original'");
        if (mysqli_query($conn, "INSERT INTO erabiltzaile_pokemon (usuario_id, elementu_izena) VALUES ({$_SESSION['user_id']}, '$pokemon_izena')")) {
            $success_message = "Pokemona gehitu da!";
        } else { sprintf(
            $error_message = "Errorea pokemona gehitzean"; erasoa=%d, defentsa=%d WHERE izena='%s'",
        }   mysqli_real_escape_string($conn, $_POST['izena']),
    }       intval($_POST['bizitza']),
}           intval($_POST['erasoa']),
            intval($_POST['defentsa']),
// Borrar pokémona_original
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_pokemon'])) {
    $pokemon_izena = mysqli_real_escape_string($conn, $_POST['pokemon_izena']);
        
    if (mysqli_query($conn, "DELETE FROM erabiltzaile_pokemon WHERE usuario_id = {$_SESSION['user_id']} AND elementu_izena = '$pokemon_izena'")) {
        $success_message = "Pokemona kendu da!";elementuak SET elementu_izena='{$_POST['izena']}' WHERE usuario_id={$_SESSION['user_id']} AND elementu_izena='$izena_original'");
    } else {
        $error_message = "Errorea pokemona kentzean";a!";
    }
}   header('Location: elements.php');
    exit;
// Editar pokémon
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_pokemon'])) {
    $pokemon_izena = mysqli_real_escape_string($conn, $_POST['izena_original']);lementuak ue ON e.izena = ue.elementu_izena WHERE ue.usuario_id = {$_SESSION['user_id']} ORDER BY e.izena");
    $bizitza = intval($_POST['bizitza']);
    $erasoa = intval($_POST['erasoa']);LECT izena, mota FROM elementuak ORDER BY izena");
    $defentsa = intval($_POST['defentsa']);
    CTYPE html>
    if (mysqli_query($conn, "UPDATE pokemon SET bizitza = $bizitza, erasoa = $erasoa, defentsa = $defentsa WHERE izena = '$pokemon_izena'")) {
        $success_message = "Pokemona eguneratu da!";
    } else {arset="UTF-8">
        $error_message = "Errorea pokemona eguneratzean";itial-scale=1.0">
    }title>Nire Elementuak</title>
}   <link rel="stylesheet" href="styles.css">
</head>
// Obtener pokémons del usuario
$result = mysqli_query($conn, "SELECT p.* FROM pokemon p INNER JOIN erabiltzaile_pokemon ep ON p.izena = ep.elementu_izena WHERE ep.usuario_id = {$_SESSION['user_id']} ORDER BY p.izena");
$user_pokemons = mysqli_fetch_all($result, MYSQLI_ASSOC);
            <a href="elements.php">Nire Elementuak</a>
// Obtener todos los pokémons disponibles</a>
$all_result = mysqli_query($conn, "SELECT izena, mota FROM pokemon ORDER BY izena");
$all_pokemons = mysqli_fetch_all($all_result, MYSQLI_ASSOC);

$conn->close();f (isset($_SESSION['success'])): ?>
?>          <div class="success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
<!DOCTYPE html>
<html lang="eu"> Elementuak</h1>
<head>  
    <meta charset="UTF-8">rm">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nire Pokemonak</title>
    <link rel="stylesheet" href="styles.css">
</head>             <label>Aukeratu elementua:</label>
<body>              <select name="elementu_izena" required>
    <div class="container">tion value="">Aukeratu bat...</option>
        <div class="nav">?php while ($el = mysqli_fetch_assoc($all_elements)): ?>
            <a href="elements.php">Nire Pokemonak</a>ecialchars($el['izena']) ?>">
            <a href="profile.php">Profila</a>alchars($el['izena']) ?> (<?= htmlspecialchars($el['mota']) ?>)
            <a href="logout.php">Saioa Itxi</a>
        </div>          <?php endwhile; ?>
                    </select>
        <?php if ($success_message): ?>
            <div class="success"><?= htmlspecialchars($success_message) ?></div>ton>
        <?php endif; ?>
        </div>
        <?php if ($error_message): ?>
            <div class="error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>rrenda</h2>
            <?php if (mysqli_num_rows($result) > 0): ?>
        <h1>Nire Pokemonak</h1>l = mysqli_fetch_assoc($result)): ?>
                    <?php $json = htmlspecialchars(json_encode($el), ENT_QUOTES, 'UTF-8'); ?>
        <div class="add-form">="element-card">
            <h2>Pokemon berria gehitu</h2>lchars($el['izena']) ?></h3>
            <form method="POST">ng>Mota:</strong> <?= htmlspecialchars($el['mota']) ?></p>
                <div class="form-group">tn btn-edit" onclick='editElement(<?= $json ?>)'>✏️ Editatu</button>
                    <label>Aukeratu pokemona:</label>
                    <select name="pokemon_izena" required>
                        <option value="">Aukeratu pokemona...</option>
                        <?php foreach ($all_pokemons as $pokemon): ?>
                            <option value="<?= htmlspecialchars($pokemon['izena']) ?>">
                                <?= htmlspecialchars($pokemon['izena']) ?> (<?= htmlspecialchars($pokemon['mota']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>="modal">
                </div>dal-content">
                <button type="submit" name="add_pokemon" class="btn">Gehitu</button>
            </form>mentua Editatu</h2>
        </div>orm method="POST">
                <input type="hidden" name="izena_original" id="izena_original">
        <div class="pokemon-list">roup">
            <h2>Nire zerrenda (<?= count($user_pokemons) ?> pokemonak)</h2>
            <?php if (!empty($user_pokemons)): ?>a" id="modal_izena" required>
                <?php foreach ($user_pokemons as $pokemon): ?>
                    <div class="pokemon-card">
                        <h3><?= htmlspecialchars($pokemon['izena']) ?></h3>
                        <p><strong>Mota:</strong> <?= htmlspecialchars($pokemon['mota']) ?></p>
                        <p><strong>Bizitza:</strong> <?= htmlspecialchars($pokemon['bizitza']) ?></p>
                        <p><strong>Erasoa:</strong> <?= htmlspecialchars($pokemon['erasoa']) ?></p>
                        <p><strong>Defentsa:</strong> <?= htmlspecialchars($pokemon['defentsa']) ?></p>
                        <div class="pokemon-actions">
                            <button type="button" class="btn btn-edit btn-edit-pokemon" data-pokemon="<?= htmlspecialchars(json_encode($pokemon)) ?>">✏️ Editatu</button>
                            <form method="POST" style="display: inline; flex: 1;">
                                <input type="hidden" name="pokemon_izena" value="<?= htmlspecialchars($pokemon['izena']) ?>">
                                <button type="submit" name="delete_pokemon" class="btn btn-delete btn-delete-pokemon">🗑️ Ezabatu</button>
                            </form>mber" name="erasoa" id="modal_erasoa" min="1" required>
                        </div>
                    </div>="form-group">
                <?php endforeach; ?></label>
            <?php else: ?> type="number" name="defentsa" id="modal_defentsa" min="1" required>
                <p class="empty-message">Ez duzu pokemonik oraindik.</p>
            <?php endif; ?>e="submit" name="update_element" class="btn">Gorde</button>
        </div>  <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Utzi</button>
    </div>  </form>
        </div>
    <!-- Modal para editar pokémon -->
    <div id="editModal" class="modal">
        <div class="modal-content">pt>
            <span class="close">&times;</span>
            <h2>Pokemon Editatu</h2>
            <form method="POST">
                <input type="hidden" name="izena_original" id="izena_original">                <div class="form-group">                    <label>Izena:</label>                    <input type="text" id="modal_izena" class="readonly-field" readonly>                </div>                <div class="form-group">                    <label>Mota:</label>                    <input type="text" id="modal_mota" class="readonly-field" readonly>                </div>                <div class="form-group">                    <label>Bizitza:</label>                    <input type="number" name="bizitza" id="modal_bizitza" min="1" max="999" required>                </div>                <div class="form-group">                    <label>Erasoa:</label>                    <input type="number" name="erasoa" id="modal_erasoa" min="1" max="999" required>                </div>                <div class="form-group">                    <label>Defentsa:</label>                    <input type="number" name="defentsa" id="modal_defentsa" min="1" max="999" required>                </div>                <button type="submit" name="update_pokemon" class="btn">Gorde</button>                <button type="button" class="btn btn-secondary">Utzi</button>            </form>        </div>    </div>    <script src="scripts.js"></script></body></html>