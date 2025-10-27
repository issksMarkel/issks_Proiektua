<?php
$host = 'db';
$user = 'admin';
$pass = 'test';
$db = 'database';

$mensaje = '';
$error = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    if (!empty($nombre) && !empty($email)) {
        try {
            $conn = new mysqli($host, $user, $pass, $db);
            
            if ($conn->connect_error) {
                throw new Exception("Error de conexión");
            }
            
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email) VALUES (?, ?)");
            $stmt->bind_param("ss", $nombre, $email);
            
            if ($stmt->execute()) {
                $mensaje = "✅ Izena eman da!";
            } else {
                $mensaje = "❌ Error";
            }
            
            $stmt->close();
            $conn->close();
            
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    } else {
        $mensaje = "❌ Mesedez atal guztiak bete ezazu";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Formulario de Registro</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background: #f5f5f5; 
        }
        .container { 
            max-width: 500px; 
            margin: 0 auto; 
            background: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        input[type="text"], input[type="email"] { 
            padding: 12px; 
            width: 100%; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            box-sizing: border-box;
        }
        input[type="submit"] { 
            background: #007bff; 
            color: white; 
            padding: 12px 30px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-size: 16px;
        }
        input[type="submit"]:hover { 
            background: #0056b3; 
        }
        .message { 
            padding: 15px; 
            margin: 15px 0; 
            border-radius: 5px; 
            text-align: center;
        }
        .success { 
            background-color: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb; 
        }
        .error { 
            background-color: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Formulario de Registro</h1>
        
        <?php if ($mensaje): ?>
            <div class="message <?php echo strpos($mensaje, '✅') !== false ? 'success' : 'error'; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <input type="text" name="nombre" placeholder="Nombre" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Enviar">
            </div>
        </form>
    </div>
</body>
</html>
