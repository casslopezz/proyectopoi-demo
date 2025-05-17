<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "poi");

if (!isset($_SESSION['id_usuario'])) {
    die("Usuario no autenticado.");
}

$id_emisor = $_SESSION['id_usuario'];
$id_receptor = $_POST['id_receptor'] ?? null;
$contenido = $_POST['contenido'] ?? null;

// Validación básica
if (!$id_receptor || !$contenido) {
    die("Faltan datos del formulario.");
}

// Insertar mensaje
$stmt = $conexion->prepare("INSERT INTO mensaje (id_emisor, id_receptor, contenido, fecha_envio) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iis", $id_emisor, $id_receptor, $contenido);
$stmt->execute();

// Redirigir de vuelta al chat
header("Location: chats.php?usuario=" . $id_receptor);
exit();
?>
