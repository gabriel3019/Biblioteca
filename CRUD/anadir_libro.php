<?php
// Conexión con la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "biblioteca";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error al conectar con la base de datos: " . $conn->connect_error);
}

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Recoger datos del formulario
    $nombre = $conn->real_escape_string($_POST["nombre"]);
    $autor = $conn->real_escape_string($_POST["autor"]);
    $publicacion = $conn->real_escape_string($_POST["publicacion"]);
    $isbn = $conn->real_escape_string($_POST["isbn"]);
    $sinopsis = $conn->real_escape_string($_POST["sinopsis"]);
    $n_totales = (int)$_POST["n_totales"];

    // Al principio, los disponibles son los mismos que los totales
    $n_disponibles = $n_totales;

    // Consulta SQL
    $sql = "INSERT INTO libros (nombre, autor, publicacion, isbn, sinopsis, n_totales, n_disponibles)
            VALUES ('$nombre', '$autor', '$publicacion', '$isbn', '$sinopsis', $n_totales, $n_disponibles)";

    // Ejecutar la consulta
    if ($conn->query($sql)) {
        echo "<p>Libro añadido correctamente.</p>";
    } else {
        echo "<p>Error al añadir libro: " . $conn->error . "</p>";
    }
}

// Enlace para volver al inicio
echo '<br><a href="../index.php">Volver al inicio</a>';

// Cerrar conexión
$conn->close();
?>
