<?php

$conn = new mysqli("localhost", "root", "", "biblioteca");
if ($conn->connect_error) die("Error de conexión: " . $conn->connect_error);
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Biblioteca</title>
    <link rel="stylesheet" href="css/css.css">
</head>

<body>
    <h1>BIBLIOTECA</h1>

    <!-- REGISTRAR NUEVO LECTOR -->
    <h2>Registrar nuevo lector</h2>
    <form action="DWES/CRUD/registrar_lector.php" method="POST">
        <input type="hidden" name="accion" value="registrarlector">
        Nombre y apellidos: <input type="text" id="nombre_apellidos" name="nombre_apellidos" placeholder="Nombre y apellidos" required><br><br>
        DNI: <input type="text" id="dni" name="dni" maxlength="9" placeholder="Introduce tu DNI" required><br><br>

        <input type="submit" value="Registrar">
    </form>

    <!-- AÑADIR LIBRO -->
    <h2>Añadir libro al catálogo</h2>
    <form action="DWES/CRUD/anadir_libro.php" method="POST">
        <input type="hidden" name="accion" value="anadirlibro">

        Nombre del libro: <input type="text" id="nombre" name="nombre" placeholder="Introduce el nombre" required><br><br>
        Autor: <input type="text" id="autor" name="autor" placeholder="Introduce el autor" required><br><br>
        Año de publicación: <input type="int" maxlength="4" id="publicacion" name="publicacion" placeholder="Introduce el año" required><br><br>
        ISBN: <input type="text" minlength="10" maxlength="13" id="isbn" name="isbn" placeholder="Introduce el ISBN" required><br><br>
        Sinopsis:<br><textarea id="sinopsis" name="sinopsis" placeholder="Introduce la sinopsis" required></textarea><br><br>
        Ejemplares: <input type="number" min="1" id="n_totales" name="n_totales" placeholder="Número de ejemplares" required><br><br>

        <input type="submit" value="Añadir libro">
    </form>

    <!-- REALIZAR PRÉSTAMO -->
    <h2>Realizar préstamo</h2>
    <form action="DWES/CRUD/prestar.php" method="POST">
        <input type="hidden" name="accion" value="prestar">

        Lector:
        <select name="lector" required>
            <option value="">Selecciona lector</option>
            <?php
            $lectores = $conn->query("SELECT * FROM lectores WHERE estado='alta'");
            if ($lectores && $lectores->num_rows > 0) {
                while ($l = $lectores->fetch_assoc()) {
                    echo "<option value='{$l['id']}'>{$l['lector']} ({$l['DNI']})</option>";
                }
            } else {
                echo "<option disabled>No hay lectores dados de alta</option>";
            }
            ?>
        </select>

        Libro:
        <select name="libro" required>
            <option value="">Selecciona libro</option>
            <?php
            // Muestra solo libros con ejemplares disponibles
            $libros = $conn->query("SELECT * FROM libros WHERE n_disponibles > 0");
            if ($libros && $libros->num_rows > 0) {
                while ($b = $libros->fetch_assoc()) {
                    echo "<option value='{$b['id']}'>{$b['nombre']}</option>";
                }
            } else {
                echo "<option disabled>No hay libros disponibles</option>";
            }
            ?>
        </select>

        <input type="submit" value="Prestar libro">
    </form>

    <!-- DEVOLVER PRÉSTAMO -->
    <h2>Devolver préstamo</h2>
    <form action="DWES/CRUD/devolver.php" method="POST">
        <input type="hidden" name="accion" value="devolver">
        <select name="prestamo" required>
            <option value="">Selecciona préstamo</option>
            <?php
            $prestamos = $conn->query("
            SELECT p.id_lector, p.id_libro, l.lector AS nombre_lector, b.nombre AS nombre_libro
            FROM prestamos p
            JOIN lectores l ON p.id_lector = l.id
            JOIN libros b ON p.id_libro = b.id
        ");

            if (!$prestamos) {
                echo "<option disabled> Error en la consulta: " . $conn->error . "</option>";
            } elseif ($prestamos->num_rows == 0) {
                echo "<option disabled> No hay préstamos registrados</option>";
            } else {
                while ($p = $prestamos->fetch_assoc()) {
                    // Usamos una clave compuesta para identificar el préstamo
                    $valor = $p['id_lector'] . "-" . $p['id_libro'];
                    echo "<option value='{$valor}'>{$p['nombre_lector']} - {$p['nombre_libro']}</option>";
                }
            }
            ?>
        </select>
        <input type="submit" value="Devolver libro">
    </form>

    <!-- DAR DE BAJA LECTOR -->
    <h2>Dar de baja lector</h2>
    <form action="DWES/CRUD/baja_lector.php" method="POST">
        <input type="hidden" name="accion" value="baja">
        <select name="lector_baja" required>
            <option value="">Selecciona lector</option>
            <?php
            $lectores = $conn->query("SELECT * FROM lectores WHERE estado='alta'");
            if ($lectores && $lectores->num_rows > 0) {
                while ($l = $lectores->fetch_assoc()) {
                    echo "<option value='{$l['id']}'>{$l['lector']} ({$l['DNI']})</option>";
                }
            } else {
                echo "<option disabled>No hay lectores dados de alta</option>";
            }
            ?>
        </select>
        <input type="submit" value="Dar de baja">
    </form>

    <!-- CONSULTAR CATÁLOGO -->
    <h2>Catálogo de libros disponibles</h2>
    <ul>
        <?php
        $libros = $conn->query("SELECT * FROM libros WHERE n_disponibles > 0");
        if (!$libros || $libros->num_rows == 0) {
            echo "<li>No hay libros disponibles actualmente</li>";
        } else {
            while ($b = $libros->fetch_assoc()) {
                echo "<li>{$b['nombre']} - {$b['autor']} ({$b['n_disponibles']} disponibles)</li>";
            }
        }
        ?>
    </ul>

    <!-- CONSULTAR PRÉSTAMOS DE UN LECTOR -->
    <h2>Consultar préstamos de un lector</h2>
    <form method="get" action="">
        <select name="id_lector_consulta" required>
            <option value="">Selecciona lector</option>
            <?php
            $lectores = $conn->query("SELECT * FROM lectores");
            while ($l = $lectores->fetch_assoc()) {
                echo "<option value='{$l['id']}'>{$l['lector']} ({$l['DNI']})</option>";
            }
            ?>
        </select>
        <input type="submit" value="Consultar">
    </form>

    <?php
    if (isset($_GET['id_lector_consulta'])) {
        $id = (int) $_GET['id_lector_consulta'];
        $prestamos = $conn->query("SELECT b.nombre 
                               FROM prestamos p
                               JOIN libros b ON p.id_libro=b.id
                               WHERE p.id_lector=$id");
        if (!$prestamos || $prestamos->num_rows == 0) {
            echo "<p>Este lector no tiene préstamos. ¡Anímate a leer!</p>";
        } else {
            echo "<ul>";
            while ($p = $prestamos->fetch_assoc()) {
                echo "<li>{$p['nombre']}</li>";
            }
            echo "</ul>";
        }
    }
    ?>

</body>

</html>