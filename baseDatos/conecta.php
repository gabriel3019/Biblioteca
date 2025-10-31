<?php

$servidor = "localhost";
$usuario = "root";
$password = ""; // Vacío significa que no hay contraseña para acceder
$database = "biblioteca"; // El nombre de la base para el supermercado

$conexion = new mysqli($servidor, $usuario, $password);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
} else {
    $sql = "SHOW DATABASES like '$database'";
    if (! $registro = $conexion->query($sql)) {
        echo "Error al crear la base: " . $conexion->connect_error;
    }
    if($registro->num_rows <= 0){
        include_once "tablas.php";
    }
    $conexion->select_db($database); 
}

?>