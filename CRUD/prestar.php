<?php
require_once("../baseDatos/conecta.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_lector = (int) $_POST["lector"];
    $id_libro = (int) $_POST["libro"];

    $check = $conn->query("SELECT n_disponibles FROM libros WHERE id=$id_libro");
    $disponibles = $check->fetch_assoc()["n_disponibles"];

    if ($disponibles > 0) {
        $conn->query("INSERT INTO prestamos (id_lector, id_libro) VALUES ($id_lector, $id_libro)");
        $conn->query("UPDATE libros SET n_disponibles = n_disponibles - 1 WHERE id=$id_libro");
        $conn->query("UPDATE lectores SET n_prestado = n_prestado + 1 WHERE id=$id_lector");

        echo "<p>Préstamo registrado correctamente.</p>";
    } else {
        echo "<p>No hay ejemplares disponibles de ese libro.</p>";
    }
}

echo '<br><a href="../index.php">Volver al inicio</a>';
$conn->close();
?>
