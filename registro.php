<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conexion = new mysqli("localhost", "root", "", "poi");

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $nombre = $_POST['first_name'];
    $correo = $_POST['email'];
    $contrasena = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Ruta de la imagen
    $foto = null;

    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0) {
        $nombreArchivo = basename($_FILES["foto"]["name"]);
        $rutaDestino = "uploads/" . time() . "_" . $nombreArchivo;

        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $rutaDestino)) {
            $foto = $rutaDestino;
        } else {
            echo "<script>alert('Error al subir la imagen.');</script>";
        }
    }

    $verificar = $conexion->prepare("SELECT id_usuario FROM Usuario WHERE correo = ?");
    $verificar->bind_param("s", $correo);
    $verificar->execute();
    $verificar->store_result();

    if ($verificar->num_rows > 0) {
        echo "<script>alert('Este correo ya está registrado.');</script>";
    } else {
        $stmt = $conexion->prepare("INSERT INTO Usuario (nombre, correo, contraseña, foto) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $correo, $contrasena, $foto);

        if ($stmt->execute()) {
            echo "<script>alert('¡Registro exitoso!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error al registrar: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    }

    $verificar->close();
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <style>
        body {
            background-image: url('fondos/registro.png'); /* Usa la imagen de fondo */
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 700px; /* Aumenta el tamaño del formulario */
            background-color: #d2a074;
            padding: 30px;
            border-radius: 20px;
            margin: 80px auto;
            text-align: center;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.3);
        }

        h2 {
            color: #623e2a;
            font-size: 28px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 15px 0 5px;
            color: #623e2a;
            font-size: 18px;
            font-weight: bold;
        }

        input {
            width: 95%;
            padding: 12px;
            margin-bottom: 20px;
            border: 2px solid #623e2a;
            border-radius: 10px;
            font-size: 18px;
        }

        .button {
            width: 48%;
            padding: 12px;
            margin-top: 15px;
            background-color: white;
            color: black;
            border: none;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
        }

        .button:hover {
            background-color: #623e2a;
            color: white;
        }
    </style>
</head>
<body> 

    <div class="container">
        <h2>REGISTRO</h2>
        <form action="registro.php" method="POST" enctype="multipart/form-data">
        
            <label for="first_name">Nombre Completo:</label>
            <input type="text" id="first_name" name="first_name" required>
        
            <label for="email">Correo:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="foto">Foto de perfil:</label>
            <input type="file" id="foto" name="foto" accept="image/*">

            <button type="submit" class="button">REGISTRAR</button>
            <button type="button" class="button" onclick="window.location.href='index.php'">VOLVER</button>

        </form>
    </div>

</body>
</html>