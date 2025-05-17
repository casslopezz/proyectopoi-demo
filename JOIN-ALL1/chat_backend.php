<?php
session_start();
include("conexion.php");

$id_emisor = $_SESSION['id_usuario'];
$id_receptor = $_POST['id_receptor'] ?? null;
$id_grupo = $_POST['id_grupo'] ?? null;
$contenido = $_POST['contenido'] ?? '';
$tipo = $_POST['tipo'] ?? 'Texto'; // 'Texto', 'Imagen', etc.
$cifrado = $_POST['cifrado'] ?? 0;

if (($id_receptor || $id_grupo) && $contenido !== '') {
    $stmt = $conn->prepare("INSERT INTO mensaje (id_emisor, id_receptor, id_grupo, contenido, tipo, cifrado) VALUES (?, ?, ?, ?, ?, ?)");
    if ($id_receptor) {
        $stmt->bind_param("iisssi", $id_emisor, $id_receptor, null, $contenido, $tipo, $cifrado);
    } else {
        $stmt->bind_param("iiissi", $id_emisor, null, $id_grupo, $contenido, $tipo, $cifrado);
    }
    $stmt->execute();
}
?>