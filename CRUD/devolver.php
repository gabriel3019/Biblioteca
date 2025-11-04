<?php
require_once("../baseDatos/conecta.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_prestamo = (int) $_POST["prestamo"];

    $datos = $conn->query("SELECT id_lector, id_libro FROM prestamos WHERE id=$id_prestamo")->fetch_assoc();
    $id_lector = $datos["id_lector"];
    $id_libro = $datos["id_libro"];

    $conn->query("DELETE FROM prestamos WHERE id=$id_prestamo");
    $conn->query("UPDATE libros SET n_disponibles = n_disponibles + 1 WHERE id=$id_libro");
    $conn->query("UPDATE lectores SET n_prestado = n_prestado - 1 WHERE id=$id_lector");

    echo "<p>Libro devuelto correctamente.</p>";
}

echo '<br><a href="../index.php">Volver al inicio</a>';
$conn->close();
?>
