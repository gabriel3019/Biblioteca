<?php
// Conexión con la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "biblioteca";

$conn = new mysqli($servername, $username, $password, $database);

// Ejecutar solo si el formulario se envía por POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Obtener el ID del lector que se dará de baja
    $id_lector = (int) $_POST["lector_baja"];

    // Cambiar el estado del lector a "baja"
    $sql = "UPDATE lectores SET estado='baja' WHERE id=$id_lector";

    // Verificar si la actualización fue exitosa
    if ($conn->query($sql)) {
        echo "<p>Lector dado de baja correctamente.</p>";
    } else {
        echo "<p>Error al dar de baja: " . $conn->error . "</p>";
    }
}

// Enlace para volver al inicio
echo '<br><a href="../index.php">Volver al inicio</a>';

// Cerrar la conexión
$conn->close();
?>
