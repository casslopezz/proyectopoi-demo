<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$conexion = new mysqli("localhost", "root", "", "poi");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener datos del usuario
$sql = "SELECT nombre, correo, foto FROM Usuario WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->bind_result($nombre, $correo, $foto);
$stmt->fetch();
$stmt->close();

// Obtener el puntaje total
$sql_puntaje = "SELECT SUM(calificacion) AS puntaje_total FROM tarea WHERE id_usuario_asignado = ?";
$stmt_puntaje = $conexion->prepare($sql_puntaje);
$stmt_puntaje->bind_param("i", $id_usuario);
$stmt_puntaje->execute();
$stmt_puntaje->bind_result($puntaje_total);
$stmt_puntaje->fetch();
$stmt_puntaje->close();

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('Fondos/paginas.png') no-repeat center center fixed;
            background-size: cover;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 30px;
            background-color: #3b2413;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.3);
        }

        .logout-btn:hover {
            background-color:rgb(169, 66, 38);
        }

        .sidebar {
            width: 200px;
            height: 100vh;
            background-color: #3b2413;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
        }

        .sidebar a:hover {
            background-color: #5a3821;
        }

        .search-container {
            background-color: #3b2413;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            top: 0;
            left: 200px;
            width: calc(100% - 200px);
            z-index: 100;
        }

        .search-container input {
            width: 60%;
            padding: 8px;
            border-radius: 20px;
            border: none;
            font-size: 16px;
        }

        .search-container button {
            background: none;
            border: none;
            cursor: pointer;
            margin-left: 10px;
        }

        .search-container img {
            width: 30px;
            height: 30px;
        }

        .perfil-container {
            margin-left: 200px;
            margin-top: 80px;
            padding: 20px;
            color: #3b2413;
            text-align: center;
            position: relative;
        }

        .perfil-container img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .perfil-container form {
            margin-top: 20px;
        }

        .perfil-container input[type="file"] {
            padding: 10px;
            margin-bottom: 10px;
        }

        .perfil-container button {
            padding: 10px 20px;
            background-color: #3b2413;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .perfil-container button:hover {
            background-color: #5a3821;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <a href="notificacion.php">Notificaciones</a>
    <a href="equipos.php">Equipos</a>
    <a href="tareas.php">Tareas</a>
    <a href="llamadas.php">Llamadas</a>
    <a href="chats.php">Chat</a>
    <a href="correo.php">Correo</a>
    <a href="perfil.php">Perfil</a>
</div>

<div class="search-container">
    <button><img src="fondos/buscar.png" alt="Buscar"></button>
    <input type="text" placeholder=" Buscar...">
</div>

<div class="perfil-container">
    
    <a href="logout.php" class="logout-btn">Cerrar sesión</a>

    <h1>Perfil</h1>
    
    <?php if ($foto): ?>
        <img src="<?= htmlspecialchars($foto) ?>" alt="Foto de perfil">
    <?php else: ?>
        <img src="fondos/default-user.png" alt="Foto por defecto">
    <?php endif; ?>
    
    <p><strong>Nombre:</strong> <?= htmlspecialchars($nombre) ?></p>
    
    <p><strong>Correo:</strong> <?= htmlspecialchars($correo) ?></p>

     <?php if ($puntaje_total !== null): ?>
        <p><strong>PUNTAJE TOTAL:</strong> <?= htmlspecialchars($puntaje_total) ?></p>
    <?php else: ?>
        <p><strong>PUNTAJE TOTAL:</strong> Aún no tienes puntaje.</p>
    <?php endif; ?>

    <!-- Formulario para cambiar la foto -->
    <form action="actualizar_foto.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="nueva_foto" accept="image/*" required>
        <button type="submit">Cambiar foto de perfil</button>
    </form>

</div>

</body>
</html>
