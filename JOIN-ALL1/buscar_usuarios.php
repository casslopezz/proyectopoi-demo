<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "poi");

if (isset($_GET['q'])) {
    $query = "%" . $_GET['q'] . "%";
    $stmt = $conexion->prepare("SELECT id_usuario, nombre, correo FROM Usuario WHERE (nombre LIKE ? OR correo LIKE ?) AND id_usuario != ?");
    $stmt->bind_param("ssi", $query, $query, $_SESSION['id_usuario']);
    $stmt->execute();
    $result = $stmt->get_result();

    $usuarios = [];
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($usuarios);
}
?>