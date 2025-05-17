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

if (isset($_FILES['nueva_foto']) && $_FILES['nueva_foto']['error'] == 0) {
    $nombreArchivo = basename($_FILES["nueva_foto"]["name"]);

    //Crear carpeta si no existe
    $carpeta = "uploads/";
    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0777, true);
    }    

    $rutaDestino = "uploads/" . time() . "_" . $nombreArchivo;

    if (move_uploaded_file($_FILES["nueva_foto"]["tmp_name"], $rutaDestino)) {
        $stmt = $conexion->prepare("UPDATE Usuario SET foto = ? WHERE id_usuario = ?");
        $stmt->bind_param("si", $rutaDestino, $id_usuario);
        if ($stmt->execute()) {
            header("Location: perfil.php");
            exit();
        } else {
            echo "Error al actualizar la foto.";
        }
        $stmt->close();
    } else {
        echo "Error al subir la nueva foto.";
    }
} else {
    echo "No se seleccionó una imagen válida.";
}
?>