<?php
// Conexión con la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "biblioteca";

$conn = new mysqli($servername, $username, $password, $database);

// Ejecutar solo si el formulario se envía por POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Limpiar los datos del formulario
    $nombre = $conn->real_escape_string($_POST["nombre_apellidos"]);
    $dni = $conn->real_escape_string($_POST["dni"]);

    // Insertar nuevo lector en la base de datos
    $sql = "INSERT INTO lectores (lector, DNI, estado, n_prestado)
            VALUES ('$nombre', '$dni', 'alta', 0)";

    // Verificar si la inserción fue exitosa
    if ($conn->query($sql)) {
        echo "<p>Lector registrado correctamente.</p>";
    } else {
        echo "<p>Error al registrar lector: " . $conn->error . "</p>";
    }
}

// Enlace para volver al inicio
echo '<br><a href="../index.php">Volver al inicio</a>';

// Cerrar la conexión
$conn->close();
?>
