<?php
session_start(); // Inicia la sesión para mantener al usuario autenticado

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "poi");

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $correo = $_POST['email'];
    $contrasena = $_POST['password'];

    // Buscar usuario por correo
    $stmt = $conexion->prepare("SELECT id_usuario, nombre, contraseña FROM Usuario WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id_usuario, $nombre, $hash);
        $stmt->fetch();

        // Verificar contraseña
        if (password_verify($contrasena, $hash)) {
            // Guardar datos en sesión
            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['nombre'] = $nombre;

            // Actualizar estado_conexion y ultima_conexion
            $conexion->query("UPDATE Usuario SET estado_conexion = 1, ultima_conexion = NOW() WHERE id_usuario = $id_usuario");

            // Redirigir al usuario a la página principal
            header("Location: principal.php");
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Correo no registrado.";
    }

    $stmt->close();
    $conexion->close();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index de Sesión</title>
    <!--<link rel="stylesheet" href="styles.css"> <!-Archivo CSS externo-->
    <style>
        /* Estilos mejorados para el formulario */
        body {
            background: url('fondos/registro.png') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }

        .form-container {
            width: 450px;
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            margin: auto;
            margin-top: 100px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #5D4037;
        }

        label {
            display: block;
            text-align: left;
            font-weight: bold;
            margin-bottom: 5px;
            color: #5D4037;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #5D4037;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #4E342E;
        }

        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>INICIO DE SESIÓN</h2>

        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <form action="login.php" method="POST">
            
            <label for="email">Correo:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Iniciar Sesion</button>
        </form>
        <p>¿Aún no tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </div>
</body>
</html>