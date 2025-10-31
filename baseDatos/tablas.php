<?php
//Creamos la base de datos:
$sql = "CREATE DATABASE $database";
//Lanzar la query
if (!$conexion->query($sql)) {
    echo "Error al crear la base: " . $conexion->connect_error;
}
$conexion->select_db($database); // Indica con qué BD se va a trabajar

// Multiquery para generar todas las tablas y datos de la base
$sql2 = "
        -- Creamos la tabla libros
        CREATE TABLE libros(
        id INT AUTO_INCREMENT PRIMARY KEY, 
        nombre VARCHAR(50), 
        autor VARCHAR(50), 
        publicacion INTEGER(4), 
        isbn VARCHAR(13), 
        sinopsis VARCHAR(300), 
        n_disponibles INTEGER(5), 
        n_totales INTEGER(5));
        
        
        -- Creamos la tabla lectores
		CREATE TABLE lectores(
        id INT AUTO_INCREMENT PRIMARY KEY, 
        lector VARCHAR(50), 
        DNI VARCHAR(10),
        estado VARCHAR(4),
        n_prestado INTEGER(3)
        );

        -- Creamos la tabla prestamos
        CREATE TABLE prestamos(
        id_lector INT,
        id_libro INT,
        FOREIGN KEY (id_lector) REFERENCES lectores(id),
        FOREIGN KEY (id_libro) REFERENCES libros(id)
        );

        -- Añadimos datos a libros:
        INSERT INTO libros(nombre, autor, publicacion, isbn, sinopsis, n_disponibles, n_totales) VALUES 
        ('Death Metal', 'Greg Capullo', 2021, '9788419325914', 'El caballero más oscuro ha llegado a Gotham', 10, 12),
        ('El hobbit', 'J.R.R Tolkien', 1892, '1234567890112', 'Una gran aventura en la era medieval', 40, 39),
        ('Moby dickc', 'Herman Melville', 1851, '0987654321009', 'Averigüa quien mato a moby dickc', 20, 20);


        -- Añadimos datos a lectores:
        INSERT INTO lectores(lector, DNI, estado, n_prestado) VALUES 
        ('Gabriel Gimenes', '123456789A', 'alta', 1),
        ('Celia Nuñez', '098765432B', 'alta', 1),
        ('Alejandro Raboso', '087654321C', 'alta', 0);

        -- Añadimos datos a prestamos:
        INSERT INTO prestamos(id_lector, id_libro) VALUES 
        (1,1),
        (2,2);
        ";
if ($conexion->multi_query($sql2)) {
    while ($conexion->next_result()) {;
    }
    echo "Tabla creada y actualizada correctamente.";
} else {
    echo "Error: {$conexion->error}";
}

$conexion->close();
