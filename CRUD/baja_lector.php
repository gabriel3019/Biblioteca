<?php
require_once("../baseDatos/conecta.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_lector = (int) $_POST["lector_baja"];
    $sql = "UPDATE lectores SET estado='baja' WHERE id=$id_lector";

    if ($conn->query($sql)) {
        echo "<p>Lector dado de baja correctamente.</p>";
    } else {
        echo "<p>Error al dar de baja: " . $conn->error . "</p>";
    }
}

echo '<br><a href="../index.php">Volver al inicio</a>';
$conn->close();
?>
