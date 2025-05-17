<?php
session_start();
include("conexion.php");

$id_usuario = $_SESSION['id_usuario'];
$id_usuario_chat = $_GET['usuario'] ?? null;
$id_grupo_chat = $_GET['grupo'] ?? null; // Nuevo: Obtener ID del grupo

if (!$id_usuario_chat && !$id_grupo_chat) exit;

$sql = "SELECT m.id_emisor, m.contenido, m.fecha_envio, m.tipo, u.nombre as nombre_emisor, u.foto as foto_emisor 
        FROM mensaje m 
        JOIN usuario u ON m.id_emisor = u.id_usuario ";

if ($id_usuario_chat) {
    $sql .= "WHERE (m.id_emisor = ? AND m.id_receptor = ? AND m.id_grupo IS NULL) 
             OR (m.id_emisor = ? AND m.id_receptor = ? AND m.id_grupo IS NULL)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $id_usuario, $id_usuario_chat, $id_usuario_chat, $id_usuario);
} else {
    $sql .= "WHERE m.id_grupo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_grupo_chat);
}

$stmt->execute();
$result = $stmt->get_result();

$mensajes = [];
while ($row = $result->fetch_assoc()) {
    $mensajes[] = $row;
}

echo json_encode($mensajes);
?>