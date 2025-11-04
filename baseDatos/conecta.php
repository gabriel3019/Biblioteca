<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$database = "biblioteca";

// Creamos conexión y la guardamos en $conn
$conn = new mysqli($servidor, $usuario, $password);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Comprobamos si existe la base de datos
$sql = "SHOW DATABASES LIKE '$database'";
$registro = $conn->query($sql);

if (!$registro) {
    die("Error al comprobar la base de datos: " . $conn->error);
}

// Si no existe, la crea automáticamente
if ($registro->num_rows <= 0) {
    include_once "tablas.php";
}

// Seleccionamos la base
$conn->select_db($database);
?>
