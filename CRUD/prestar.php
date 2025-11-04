<?php
// Conexión con la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "biblioteca";

$conn = new mysqli($servername, $username, $password, $database);

// Ejecutar solo si el formulario se envía por POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Obtener los IDs del lector y del libro desde el formulario
    $id_lector = (int) $_POST["lector"];
    $id_libro = (int) $_POST["libro"];

    // Consultar cuántos ejemplares del libro están disponibles
    $check = $conn->query("SELECT n_disponibles FROM libros WHERE id_libros=$id_libro");
    $disponibles = $check->fetch_assoc()["n_disponibles"];

    // Si hay ejemplares disponibles, registrar el préstamo
    if ($disponibles > 0) {
        // Insertar el préstamo en la tabla "prestamos"
        $conn->query("INSERT INTO prestamos (id_lectores, id_libros) VALUES ($id_lector, $id_libro)");

        // Reducir el número de ejemplares disponibles
        $conn->query("UPDATE libros SET n_disponibles = n_disponibles - 1 WHERE id_libros=$id_libro");

        // Aumentar el número de préstamos del lector
        $conn->query("UPDATE lectores SET n_prestado = n_prestado + 1 WHERE id_lectores=$id_lector");

        echo "<p>Préstamo registrado correctamente.</p>";
    } else {
        echo "<p>No hay ejemplares disponibles de ese libro.</p>";
    }
}

// Enlace para volver al inicio
echo '<br><a href="../index.php">Volver al inicio</a>';

// Cerrar la conexión
$conn->close();
?>
