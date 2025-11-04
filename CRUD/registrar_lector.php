<?php
require_once("../baseDatos/conecta.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $conn->real_escape_string($_POST["nombre_apellidos"]);
    $dni = $conn->real_escape_string($_POST["dni"]);

    $sql = "INSERT INTO lectores (lector, DNI, estado, n_prestado)
            VALUES ('$nombre', '$dni', 'alta', 0)";

    if ($conn->query($sql)) {
        echo "<p>Lector registrado correctamente.</p>";
    } else {
        echo "<p>Error al registrar lector: " . $conn->error . "</p>";
    }
}

echo '<br><a href="../index.php">Volver al inicio</a>';
$conn->close();
