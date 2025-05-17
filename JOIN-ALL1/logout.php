<?php
session_start();

if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];

    // Conectarse a la base de datos
    $conexion = new mysqli("localhost", "root", "", "poi");
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Actualizar la última conexión y estado activo
    $sql = "UPDATE Usuario SET estado_conexion = 0, ultima_conexion = NOW() WHERE id_usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $stmt->close();
    $conexion->close();
}

// Cerrar sesión
session_unset();
session_destroy();

// Redirigir al inicio
header("Location: index.php");
exit();
?>
