<?php
// Conexión con la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "biblioteca";

$conn = new mysqli($servername, $username, $password, $database);

// Ejecutar solo si el formulario se envía por POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Obtener el ID del préstamo desde el formulario
    $id_prestamo = (int) $_POST["prestamo"];

    // Buscar los datos del préstamo (lector y libro)
    $datos = $conn->query("SELECT id_lector, id_libro FROM prestamos WHERE id=$id_prestamo")->fetch_assoc();
    $id_lector = $datos["id_lector"];
    $id_libro = $datos["id_libro"];

    // Eliminar el préstamo de la tabla "prestamos"
    $conn->query("DELETE FROM prestamos WHERE id=$id_prestamo");

    // Aumentar la cantidad de libros disponibles
    $conn->query("UPDATE libros SET n_disponibles = n_disponibles + 1 WHERE id=$id_libro");

    // Disminuir el número de préstamos activos del lector
    $conn->query("UPDATE lectores SET n_prestado = n_prestado - 1 WHERE id=$id_lector");

    // Mensaje de confirmación
    echo "<p>Libro devuelto correctamente.</p>";
}

// Enlace para volver al inicio
echo '<br><a href="../index.php">Volver al inicio</a>';

// Cerrar la conexión
$conn->close();
?>
