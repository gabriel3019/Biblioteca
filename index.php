<?php

// $conn = new mysqli("localhost", "root", "", "biblioteca");
// if ($conn->connect_error) die("Error de conexión: " . $conn->connect_error);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gestión de Biblioteca</title>
</head>
<body>
<h1>BIBLIOTECA</h1>

<!-- REGISTRAR NUEVO LECTOR -->
<h2>Registrar nuevo lector</h2>
<form action="procesar.php" method="post">
    <input type="hidden" name="accion" value="registrar_lector">
    Nombre y apellidos: <input type="text" id="nombre_apellidos" name="nombre_apellidos" placeholder="Nombre y apellidos"required><br><br>
    DNI: <input type="text" id="dni" name="dni" maxlength="9" placeholder="Introduce tu DNI" required><br><br>
    
    <input type="submit" value="Registrar">
</form>

<!-- AÑADIR LIBRO -->
<h2>Añadir libro al catálogo</h2>
<form action="procesar.php" method="post">
    <input type="hidden" name="accion" value="añadir_libro">

    Nombre del libro: <input type="text" id="nombre" name="nombre" placeholder="Introduce el nombre" required><br><br>
    Autor: <input type="text" id="autor" name="autor" placeholder="Introduce el autor" required><br><br>
    Año de publicación: <input type="int" maxlength="4" id="publicacion" name="publicacion" placeholder="Introduce el año" required><br><br>
    ISBN: <input type="text" minlength="10" maxlength="13" id="ISBN" name="ISBN" placeholder="Introduce el ISBN" required><br><br>
    Sinopsis:<br><textarea id="ISBN" name="ISBN" placeholder="Introduce la sinopsis" required></textarea><br><br>
    Ejemplares: <input type="text" maxlength="99999" id="titulo" name="titulo" required><br><br>
  
    <input type="submit" value="Añadir libro">
</form>

<!-- REALIZAR PRÉSTAMO -->
<h2>Realizar préstamo</h2>
<form action="procesar.php" method="post">
    <input type="hidden" name="accion" value="prestar">
    Lector:
    <select name="lector" required>
        <option value="">Selecciona lector</option>
        <?php
        $lectores = $conn->query("SELECT * FROM lectores WHERE estado='activo'");
        while ($l = $lectores->fetch_assoc()) echo "<option value='{$l['id']}'>{$l['nombre']} ({$l['dni']})</option>";
        ?>
    </select>
    Libro:
    <select name="libro" required>
        <option value="">Selecciona libro</option>
        <?php
        $libros = $conn->query("SELECT * FROM libros WHERE disponible=1");
        while ($b = $libros->fetch_assoc()) echo "<option value='{$b['id']}'>{$b['titulo']}</option>";
        ?>
    </select>
    <input type="submit" value="Prestar libro">
</form>

<!-- DEVOLVER PRÉSTAMO -->
<h2>Devolver préstamo</h2>
<form action="procesar.php" method="post">
    <input type="hidden" name="accion" value="devolver">
    <select name="prestamo" required>
        <option value="">Selecciona préstamo</option>
        <?php
        $prestamos = $conn->query("SELECT p.id, l.nombre, b.titulo
                                   FROM prestamos p
                                   JOIN lectores l ON p.id_lector=l.id
                                   JOIN libros b ON p.id_libro=b.id");
        if ($prestamos->num_rows == 0) {
            echo "<option disabled>No hay préstamos registrados</option>";
        } else {
            while ($p = $prestamos->fetch_assoc())
                echo "<option value='{$p['id']}'>{$p['nombre']} - {$p['titulo']}</option>";
        }
        ?>
    </select>
    <input type="submit" value="Devolver libro">
</form>

<!-- DAR DE BAJA LECTOR -->
<h2>Dar de baja lector</h2>
<form action="procesar.php" method="post">
    <input type="hidden" name="accion" value="baja">
    <select name="lector_baja" required>
        <option value="">Selecciona lector</option>
        <?php
        $lectores = $conn->query("SELECT * FROM lectores WHERE estado='activo'");
        if ($lectores->num_rows == 0) {
            echo "<option disabled>No hay lectores activos</option>";
        } else {
            while ($l = $lectores->fetch_assoc()) echo "<option value='{$l['id']}'>{$l['nombre']} ({$l['dni']})</option>";
        }
        ?>
    </select>
    <input type="submit" value="Dar de baja">
</form>

<!-- CONSULTAR CATÁLOGO -->
<h2>Catálogo de libros disponibles</h2>
<ul>
<?php
$libros = $conn->query("SELECT * FROM libros WHERE disponible=1");
if ($libros->num_rows == 0) {
    echo "<li>No hay libros disponibles actualmente</li>";
} else {
    while ($b = $libros->fetch_assoc()) echo "<li>{$b['titulo']} - {$b['autor']}</li>";
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
        while ($l = $lectores->fetch_assoc()) echo "<option value='{$l['id']}'>{$l['nombre']} ({$l['dni']})</option>";
        ?>
    </select>
    <input type="submit" value="Consultar">
</form>

<?php
if (isset($_GET['id_lector_consulta'])) {
    $id = $_GET['id_lector_consulta'];
    $prestamos = $conn->query("SELECT b.titulo FROM prestamos p
                               JOIN libros b ON p.id_libro=b.id
                               WHERE p.id_lector=$id");
    if ($prestamos->num_rows == 0) {
        echo "<p>Este lector no tiene préstamos. ¡Anímate a leer!</p>";
    } else {
        echo "<ul>";
        while ($p = $prestamos->fetch_assoc()) echo "<li>{$p['titulo']}</li>";
        echo "</ul>";
    }
}
?>

</body>
</html>
