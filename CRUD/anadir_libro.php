<?php
require_once("../baseDatos/conecta.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $conn->real_escape_string($_POST["nombre"]);
    $autor = $conn->real_escape_string($_POST["autor"]);
    $publicacion = $conn->real_escape_string($_POST["publicacion"]);
    $isbn = $conn->real_escape_string($_POST["isbn"]);
    $sinopsis = $conn->real_escape_string($_POST["sinopsis"]);
    $n_totales = (int)$_POST["n_totales"];

    $n_disponibles = $n_totales;

    $sql = "INSERT INTO libros (nombre, autor, publicacion, isbn, sinopsis, n_totales, n_disponibles)
            VALUES ('$nombre', '$autor', '$publicacion', '$isbn', '$sinopsis', $n_totales, $n_disponibles)";

    if ($conn->query($sql)) {
        echo "<p>Libro añadido correctamente.</p>";
    } else {
        echo "<p>Error al añadir libro: " . $conn->error . "</p>";
    }
}

echo '<br><a href="../index.php">Volver al inicio</a>';
$conn->close();
?>
