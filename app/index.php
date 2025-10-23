<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexión a BD al inicio para estar disponible en todas las rutas
$hostname = "localhost";
$username = "admin";
$password = "test";
$db = "db";

$conn = mysqli_connect($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Error de conexión a la base de datos");
}

// Función para validar NAN en el servidor
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

// Obtener la ruta solicitada
$path = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($path, PHP_URL_PATH);
$path = rtrim($path, '/');
if ($path === '') $path = '/';

// Manejar rutas
if ($path === '/') {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Página Principal</title>
        <style>
            .btn {
                display: inline-block;
                padding: 12px 24px;
                background: #007bff;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                margin: 10px;
                font-size: 16px;
            }
            .btn:hover {
                background: #0056b3;
            }
            .container {
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Bienvenido a Nuestro Sitio</h1>
            <p>Esta es la página principal de nuestra aplicación.</p>
            
            <div style="margin: 30px 0;">
                <a href="/register" class="btn">Registrarse</a>
            </div>
            
            <div style="margin-top: 40px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
                <h3>¿Por qué registrarse?</h3>
                <p>Al registrarte podrás acceder a todas las funciones de nuestra plataforma.</p>
            </div>
        </div>
    </body>
    </html>
    <?php
} 
elseif ($path === '/register') {
    ?>
    <!DOCTYPE html>
    <html lang="eu">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Erabiltzaileen Erregistroa</title>
        <style>
            .form-container { max-width: 500px; margin: 0 auto; padding: 20px; }
            .form-group { margin: 15px 0; text-align: left; }
            label { display: block; margin-bottom: 5px; font-weight: bold; }
            input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
            input:valid { border-color: #28a745; }
            input:invalid { border-color: #dc3545; }
            .btn { padding: 12px 30px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
            .btn:hover { background: #0056b3; }
            .back-btn { display: inline-block; padding: 8px 16px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px; margin-bottom: 20px; }
            .example { font-size: 12px; color: #666; margin-top: 5px; }
            .error-message { color: #dc3545; background: #f8d7da; padding: 10px; margin: 10px 0; border-radius: 4px; }
            .success { color: #28a745; background: #d4edda; padding: 10px; margin: 10px 0; border-radius: 4px; }
        </style>
        <script>
            function validatuNAN(nan) {
                const pattern = /^[0-9]{8}-[A-Z]$/;
                if (!pattern.test(nan)) return false;
                const zenbakiak = nan.substring(0, 8);
                const letraJarraitua = nan.substring(9, 10);
                const letrak = 'TRWAGMYFPDXBNJZSQVHLCKE';
                const zenbakia = parseInt(zenbakiak);
                const letraKalkulatua = letrak[zenbakia % 23];
                return letraKalkulatua === letraJarraitua;
            }

            function validatuFormularioa() {
                const izena = document.getElementById('izena').value;
                const nan = document.getElementById('nan').value;
                const telefonoa = document.getElementById('telefonoa').value;
                const jaiotze_data = document.getElementById('jaiotze_data').value;
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                const confirm_password = document.getElementById('confirm_password').value;

                const izenaPattern = /^[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+$/;
                if (!izenaPattern.test(izena)) {
                    alert('Izenak testu soilik izan behar du');
                    return false;
                }

                if (!validatuNAN(nan)) {
                    alert('NAN formatu okerra. Adibidea: 12345678-Z');
                    return false;
                }

                const telefonoPattern = /^[0-9]{9}$/;
                if (!telefonoPattern.test(telefonoa)) {
                    alert('Telefonoak 9 zenbaki izan behar ditu');
                    return false;
                }

                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(email)) {
                    alert('Email formatu okerra. Adibidea: adibidea@zerbitzaria.extentsioa');
                    return false;
                }

                const dataPattern = /^\d{4}-\d{2}-\d{2}$/;
                if (!dataPattern.test(jaiotze_data)) {
                    alert('Data formatu okerra. Adibidea: 2021-08-26');
                    return false;
                }

                const data = new Date(jaiotze_data);
                const gaur = new Date();
                if (data > gaur) {
                    alert('Jaiotze data ezin da etorkizunekoa izan');
                    return false;
                }

                if (password.length < 6) {
                    alert('Pasahitzak gutxienez 6 karaktere izan behar ditu');
                    return false;
                }

                if (password !== confirm_password) {
                    alert('Pasahitzak ez datoz bat');
                    return false;
                }

                return true;
            }

            function erakutsiAdibidea(campo) {
                const examples = {
                    'izena': 'Adibidez: Jon Etxeberria',
                    'nan': 'Adibidez: 12345678-Z',
                    'telefonoa': 'Adibidez: 612345678',
                    'jaiotze_data': 'Adibidez: 1990-05-15',
                    'email': 'Adibidez: erabiltzailea@posta.eus',
                    'password': 'Gutxienez 6 karaktere, adib: Eguzki123'
                };
                document.getElementById(campo + '_example').textContent = examples[campo] || '';
            }

            function ezkutatuAdibidea(campo) {
                document.getElementById(campo + '_example').textContent = '';
            }
        </script>
    </head>
    <body>
        <div class="form-container">
            <a href="/" class="back-btn">← Hasierara itzuli</a>
            <h1>Erabiltzaileen Erregistroa</h1>
            
            <?php
            if (isset($_GET['exito']) && $_GET['exito'] == '1') {
                echo '<div class="success">Erregistroa egokia! Erabiltzailea ondo erregistratu da.</div>';
            }
            if (isset($_GET['error'])) {
                $errores = [
                    'nan' => 'NAN formatu okerra',
                    'email' => 'Email formatu okerra',
                    'telefonoa' => 'Telefono formatu okerra',
                    'password' => 'Pasahitza gutxienez 6 karaktere izan behar ditu',
                    'password_mismatch' => 'Pasahitzak ez datoz bat',
                    'general' => 'Errorea erregistroa prozesatzean'
                ];
                $error = $_GET['error'];
                if (isset($errores[$error])) {
                    echo '<div class="error-message">' . $errores[$error] . '</div>';
                }
            }
            ?>
            
            <form action="/procesar-registro" method="POST" onsubmit="return validatuFormularioa()">
                <div class="form-group">
                    <label for="izena">Izen abizenak:</label>
                    <input type="text" id="izena" name="izena" pattern="[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+" required>
                    <div id="izena_example" class="example"></div>
                </div>

                <div class="form-group">
                    <label for="nan">NAN (Nortasun Agiri Nazionala):</label>
                    <input type="text" id="nan" name="nan" pattern="[0-9]{8}-[A-Z]" required>
                    <div id="nan_example" class="example"></div>
                </div>

                <div class="form-group">
                    <label for="telefonoa">Telefonoa:</label>
                    <input type="tel" id="telefonoa" name="telefonoa" pattern="[0-9]{9}" required>
                    <div id="telefonoa_example" class="example"></div>
                </div>

                <div class="form-group">
                    <label for="jaiotze_data">Jaiotze data:</label>
                    <input type="date" id="jaiotze_data" name="jaiotze_data" required>
                    <div id="jaiotze_data_example" class="example"></div>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <div id="email_example" class="example"></div>
                </div>

                <div class="form-group">
                    <label for="password">Pasahitza:</label>
                    <input type="password" id="password" name="password" minlength="6" required>
                    <div id="password_example" class="example"></div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Pasahitza berriro:</label>
                    <input type="password" id="confirm_password" name="confirm_password" minlength="6" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn">Erregistratu</button>
                </div>
            </form>
        </div>
    </body>
    </html>
    <?php
}
elseif ($path === '/procesar-registro') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $izena = mysqli_real_escape_string($conn, $_POST['izena'] ?? '');
        $nan = mysqli_real_escape_string($conn, $_POST['nan'] ?? '');
        $telefonoa = mysqli_real_escape_string($conn, $_POST['telefonoa'] ?? '');
        $jaiotze_data = mysqli_real_escape_string($conn, $_POST['jaiotze_data'] ?? '');
        $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        $errores = [];

        if (!preg_match('/^[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+$/', $izena)) $errores[] = 'izena';
        if (!validarNAN($nan)) $errores[] = 'nan';
        if (!preg_match('/^[0-9]{9}$/', $telefonoa)) $errores[] = 'telefonoa';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = 'email';
        if (strlen($password) < 6) $errores[] = 'password';
        if ($password !== $confirm_password) $errores[] = 'password_mismatch';
        $fecha_actual = date('Y-m-d');
        if ($jaiotze_data > $fecha_actual) $errores[] = 'fecha';

        if (empty($errores)) {
            $check_query = "SELECT id FROM usuarios WHERE nan = '$nan'";
            $result = mysqli_query($conn, $check_query);
            if (mysqli_num_rows($result) > 0) {
                header('Location: /register?error=nan_existe');
                exit;
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert_query = "INSERT INTO usuarios (nombre, nan, telefono, fecha_nacimiento, email, password) 
                                 VALUES ('$izena', '$nan', '$telefonoa', '$jaiotze_data', '$email', '$hashed_password')";
                if (mysqli_query($conn, $insert_query)) {
                    header('Location: /register?exito=1');
                    exit;
                } else {
                    header('Location: /register?error=general');
                    exit;
                }
            }
        } else {
            $params = http_build_query([
                'error' => $errores[0],
                'izena' => $izena,
                'nan' => $nan,
                'telefonoa' => $telefonoa,
                'jaiotze_data' => $jaiotze_data,
                'email' => $email
            ]);
            header('Location: /register?' . $params);
            exit;
        }
    } else {
        http_response_code(405);
        echo "Método no permitido";
    }
}
elseif ($path === '/usuarios') {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Usuarios Registrados</title>
        <style>
            .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
            .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
            th { background-color: #f8f9fa; }
            tr:hover { background-color: #f5f5f5; }
        </style>
    </head>
    <body>
        <div class="container">
            <a href="/" class="btn">← Volver al inicio</a>
            <h1>Usuarios Registrados</h1>
            
            <?php
            $query = mysqli_query($conn, "SELECT id, nombre, nan, telefono, fecha_nacimiento, email FROM usuarios") or die(mysqli_error($conn));
            if (mysqli_num_rows($query) > 0) {
                echo '<table>';
                echo '<tr><th>ID</th><th>Nombre</th><th>NAN</th><th>Teléfono</th><th>Fecha Nacimiento</th><th>Email</th></tr>';
                while ($row = mysqli_fetch_array($query)) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['nombre']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['nan']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['telefono']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['fecha_nacimiento']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '<p>No hay usuarios registrados.</p>';
            }
            ?>
        </div>
    </body>
    </html>
    <?php
}
else {
    http_response_code(404);
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Página No Encontrada</title>
        <style>
            .error-container { text-align: center; padding: 50px; }
            .error-code { font-size: 72px; color: #dc3545; margin: 0; }
            .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        </style>
    </head>
    <body>
        <div class="error-container">
            <h1 class="error-code">404</h1>
            <h2>Página No Encontrada</h2>
            <p>Lo sentimos, la página que buscas no existe.</p>
            <a href="/" class="btn">Volver a la página principal</a>
        </div>
    </body>
    </html>
    <?php
}

$conn->close();
?>

