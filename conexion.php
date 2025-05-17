<?php
$servername = "localhost"; // En 000WebHost se queda igual
$username = "u928535580_equipopoi2"; // Lo que crees en el panel
$password = "EquipoPOI2025*";
$database = "u928535580_proyectopoi2";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
