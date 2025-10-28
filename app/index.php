<?php
// Move session_start to very beginning of file, before any output
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

$conn = mysqli_connect($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Error de conexión a la base de datos");
}

// Función para validar NAN
function validarNAN($nan) {
    $pattern = '/^[0-9]{8}-[A-Z]$/';
    if (!preg_match($pattern, $nan)) return false;
    
    $numeros = substr($nan, 0, 8);
    $letra = substr($nan, 9, 1);
    $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
    $numero = intval($numeros);
    $letraCalculada = $letras[$numero % 23];
    
    return $letraCalculada === $letra;
}

// Procesar login
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = $_POST['password'];
    
    $query = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header('Location: index.php?page=profile');
            exit;
        }
    }
    $login_error = "Email edo pasahitz okerra";
}

// Procesar registro
if (isset($_POST['register'])) {
    $izena = mysqli_real_escape_string($conn, $_POST['izena']);
    $nan = mysqli_real_escape_string($conn, $_POST['nan']);
    $telefonoa = mysqli_real_escape_string($conn, $_POST['telefonoa']);
    $jaiotze_data = mysqli_real_escape_string($conn, $_POST['jaiotze_data']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (validarNAN($nan)) {
        // Check if NAN already exists
        $check_query = "SELECT id FROM usuarios WHERE nan = '$nan'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $register_error = "NAN hau dagoeneko erregistratuta dago";
        } else {
            $insert_query = "INSERT INTO usuarios (nombre, nan, telefono, fecha_nacimiento, email, password) 
                            VALUES ('$izena', '$nan', '$telefonoa', '$jaiotze_data', '$email', '$password')";
            
            if (mysqli_query($conn, $insert_query)) {
                // Get the ID of the newly registered user
                $user_id = mysqli_insert_id($conn);
                
                // Set session variables
                $_SESSION['user_id'] = $user_id;
                $_SESSION['email'] = $email;
                
                // Redirect to profile page
                header('Location: index.php?page=profile');
                exit;
            } else {
                $register_error = "Errorea erregistroan";
            }
        }
    } else {
        $register_error = "NAN formatu okerra";
    }
}

// Actualizar perfil
if (isset($_POST['update_profile']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $izena = mysqli_real_escape_string($conn, $_POST['izena']);
    $telefonoa = mysqli_real_escape_string($conn, $_POST['telefonoa']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    $update_query = "UPDATE usuarios SET nombre = '$izena', telefono = '$telefonoa', email = '$email' 
                    WHERE id = $user_id";
    
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['success'] = "Datuak eguneratu dira";
    }
}

$page = $_GET['page'] ?? 'login';
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erabiltzaile Sistema</title>
    <style>
        .container { max-width: 500px; margin: 0 auto; padding: 20px; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; margin-bottom: 10px; }
        .btn { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; }
        .success { color: green; background: #d4edda; padding: 10px; margin: 10px 0; }
        .error { color: red; background: #f8d7da; padding: 10px; margin: 10px 0; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="nav">
                <a href="?page=profile">Nire Profila</a>
                <a href="logout.php">Saioa Itxi</a>
            </div>
        <?php endif; ?>

        <?php if ($page === 'login'): ?>
            <h1>Saioa Hasi</h1>
            <form method="POST">
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Pasahitza:</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn">Saioa Hasi</button>
                <p>Ez duzu konturik? <a href="?page=register">Erregistratu</a></p>
            </form>

        <?php elseif ($page === 'register'): ?>
            <!-- Existing registration form with password field added -->
            <h1>Erregistratu</h1>
            <form method="POST" onsubmit="return validatuFormularioa()">
                <div class="form-group">
                    <label for="izena">Izen abizenak:</label>
                    <input type="text" id="izena" name="izena" required>
                </div>

                <div class="form-group">
                    <label for="nan">NAN:</label>
                    <input type="text" id="nan" name="nan" required pattern="[0-9]{8}-[A-Z]">
                </div>

                <div class="form-group">
                    <label for="telefonoa">Telefonoa:</label>
                    <input type="tel" id="telefonoa" name="telefonoa" required pattern="[0-9]{9}">
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
                <p>Jadanik kontua duzu? <a href="?page=login">Saioa Hasi</a></p>
            </form>

        <?php elseif ($page === 'profile' && isset($_SESSION['user_id'])): ?>
            <h1>Nire Profila</h1>
            <?php
            $user_id = $_SESSION['user_id'];
            $query = "SELECT * FROM usuarios WHERE id = $user_id";
            $result = mysqli_query($conn, $query);
            $user = mysqli_fetch_assoc($result);
            ?>
            <form method="POST">
                <div class="form-group">
                    <label>Izena:</label>
                    <input type="text" name="izena" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Telefonoa:</label>
                    <input type="tel" name="telefonoa" value="<?php echo htmlspecialchars($user['telefono']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <button type="submit" name="update_profile" class="btn">Eguneratu</button>
            </form>
        <?php endif; ?>
    </div>

    <script>
        function validatuFormularioa() {
            const nan = document.getElementById('nan').value;
            const telefonoa = document.getElementById('telefonoa').value;
            const email = document.getElementById('email').value;

            if (!/^[0-9]{8}-[A-Z]$/.test(nan)) {
                alert('NAN formatu okerra');
                return false;
            }

            if (!/^[0-9]{9}$/.test(telefonoa)) {
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
<?php $conn->close(); ?>
