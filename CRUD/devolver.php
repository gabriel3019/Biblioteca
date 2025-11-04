<?php
// Conexión con la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "biblioteca";

$conn = new mysqli($servername, $username, $password, $database);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['prestamo']) || empty($_POST['prestamo'])) {
        echo "<p>Error: no se seleccionó ningún préstamo.</p>";
    } else {
        // Separar el valor "id_lector-id_libro"
        list($id_lector, $id_libro) = explode('-', $_POST['prestamo']);
        $id_lector = (int) $id_lector;
        $id_libro = (int) $id_libro;

        // Eliminar préstamo correspondiente
        $conn->query("DELETE FROM prestamos WHERE id_lector=$id_lector AND id_libro=$id_libro");

        // Aumentar libros disponibles
        $conn->query("UPDATE libros SET n_disponibles = n_disponibles + 1 WHERE id=$id_libro");

        // Disminuir préstamos del lector
        $conn->query("UPDATE lectores SET n_prestado = n_prestado - 1 WHERE id=$id_lector");

        echo "<p>Libro devuelto correctamente.</p>";
    }
}

echo '<br><a href="../index.php">Volver al inicio</a>';
$conn->close();
?>
