<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "biblioteca");
if ($conn->connect_error) die("Error de conexión: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST["accion"];

    switch ($accion) {

        /* ---------- REGISTRAR NUEVO LECTOR ---------- */
        case "registrarlector":
            $nombre = $conn->real_escape_string($_POST["nombre_apellidos"]);
            $dni = $conn->real_escape_string($_POST["dni"]);

            $sql = "INSERT INTO lectores (lector, DNI, estado, n_prestado)
                    VALUES ('$nombre', '$dni', 'alta', 0)";
            if ($conn->query($sql))
                echo "<p>Lector registrado correctamente.</p>";
            else
                echo "<p>❌ Error al registrar lector: " . $conn->error . "</p>";
            break;

        /* ---------- AÑADIR LIBRO ---------- */
        case "anadirlibro":
            $nombre = $conn->real_escape_string($_POST["nombre"]);
            $autor = $conn->real_escape_string($_POST["autor"]);
            $publicacion = (int) $_POST["publicacion"];
            $isbn = $conn->real_escape_string($_POST["ISBN"]);
            $sinopsis = $conn->real_escape_string($_POST["sinopsis"] ?? '');
            $ejemplares = (int) $_POST["titulo"]; // campo equivocado en tu form

            $sql = "INSERT INTO libros (nombre, autor, publicacion, isbn, sinopsis, n_disponibles, n_totales)
                    VALUES ('$nombre', '$autor', $publicacion, '$isbn', '$sinopsis', $ejemplares, $ejemplares)";
            if ($conn->query($sql))
                echo "<p>Libro añadido correctamente.</p>";
            else
                echo "<p>Error al añadir libro: " . $conn->error . "</p>";
            break;

        /* ---------- REALIZAR PRÉSTAMO ---------- */
        case "prestar":
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
            break;

        /* ---------- DEVOLVER PRÉSTAMO ---------- */
        case "devolver":
    list($id_lector, $id_libro) = explode("-", $_POST["prestamo"]);

    // Eliminar préstamo
    $conn->query("DELETE FROM prestamos WHERE id_lector=$id_lector AND id_libro=$id_libro");

    // Actualizar libro y lector
    $conn->query("UPDATE libros SET n_disponibles = n_disponibles + 1 WHERE id=$id_libro");
    $conn->query("UPDATE lectores SET n_prestado = n_prestado - 1 WHERE id=$id_lector");

    echo "<p>Libro devuelto correctamente.</p>";
    break;

        /* ---------- DAR DE BAJA LECTOR ---------- */
        case "baja":
            $id_lector = (int) $_POST["lector_baja"];
            $sql = "UPDATE lectores SET estado='inactivo' WHERE id=$id_lector";
            if ($conn->query($sql))
                echo "<p>Lector dado de baja correctamente.</p>";
            else
                echo "<p>Error al dar de baja: " . $conn->error . "</p>";
            break;

        default:
            echo "<p>Acción no reconocida.</p>";
    }
}

echo '<br><a href="../index.php">Volver al inicio</a>';
$conn->close();
?>
