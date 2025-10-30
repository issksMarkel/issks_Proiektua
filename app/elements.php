<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get all pokemon
$stmt = $conn->query("SELECT * FROM pokemon");
$pokemons = $stmt->fetchAll();

// Get user's pokemon
$stmt = $conn->prepare("
    SELECT p.* 
    FROM pokemon p
    INNER JOIN erabiltzaile_pokemon ep ON p.izena = ep.elementu_izena 
    WHERE ep.usuario_id = ?
    ORDER BY p.izena
");
$stmt->execute([$_SESSION['user_id']]);
$user_pokemons = $stmt->fetchAll();

// Get all available pokemon
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
    }
}
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nire Pokemonak</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="elements.php">Nire Pokemonak</a>
            <a href="profile.php">Profila</a>
            <a href="logout.php">Saioa Itxi</a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <h1>Nire Pokemonak</h1>
        
        <div class="add-form">
            <h2>Pokemon berria gehitu</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Aukeratu pokemona:</label>
                    <select name="pokemon_izena" required>
                        <option value="">Aukeratu bat...</option>
                        <?php foreach ($all_pokemons as $pokemon): ?>
                            <option value="<?= htmlspecialchars($pokemon['izena']) ?>">
                                <?= htmlspecialchars($pokemon['izena']) ?> (<?= htmlspecialchars($pokemon['mota']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="add_pokemon" class="btn">Gehitu</button>
            </form>
        </div>

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
                        <button class="btn btn-edit" onclick='editPokemon(<?= json_encode($pokemon) ?>)'>✏️ Editatu</button>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="pokemon_izena" value="<?= htmlspecialchars($pokemon['izena']) ?>">
                            <button type="submit" name="remove_pokemon" class="btn btn-delete">❌ Kendu</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Ez duzu pokemonik oraindik.</p>
            <?php endif; ?>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Pokemon Editatu</h2>
            <form method="POST">
                <input type="hidden" name="izena_original" id="izena_original">
                <div class="form-group">
                    <label>Izena:</label>
                    <input type="text" id="modal_izena" class="readonly-field" readonly>
                </div>
                <div class="form-group">
                    <label>Mota:</label>
                    <input type="text" id="modal_mota" class="readonly-field" readonly>
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
                <button type="submit" name="update_pokemon" class="btn">Gorde</button>
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Utzi</button>
            </form>
        </div>
    </div>

    <script src="scripts.js"></script>
</body>
</html>
<?php $conn = null; ?>