<?php
require("conexion.php");
require("funciones.php");

$mensaje = "";
$conn = conectarBD();

// Cargamos categorías para el desplegable
$categorias = desplegableCategoria($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = limpiar($_POST["nombre"]);
    $id_categoria = limpiar($_POST["id_categoria"]);
    $precio = limpiar($_POST["precio"]);

    try {
        // Generamos el nuevo ID y insertamos el producto
        $nuevo_id = generarNuevoId_producto($conn);
        insertarProducto($conn, $nuevo_id, $nombre, $precio, $id_categoria);

        $mensaje = "Producto '$nombre' creado con ID $nuevo_id.";
    } catch (PDOException $e) {
        $mensaje = "Error: " . $e->getMessage();
    } finally {
        $conn = null; // Cerramos la conexión
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Alta de Productos</title>
</head>

<body>
    <h2>Alta de Productos</h2>
    <form method="POST" action="comaltapro.php">
        <label>Nombre de la producto:</label><br>
        <input type="text" name="nombre" required>
        <br><br>
        <label>Precio del producto:</label><br>
        <input type="number" name="precio" required>
        <br><br>
        <label>ID Categoria del producto:</label><br>
        <select name="id_categoria" required>
            <?php // Rellenamos el desplegable con las categorías
            foreach ($categorias as $categoria) {
                echo "<option value='" . $categoria['id_categoria'] . "'>" . $categoria['nombre'] . "</option>";
            }
            ?>
        </select><br><br>
        <input type="submit" value="Dar de alta">
    </form>

    <?php if ($mensaje): ?>
        <p><?= $mensaje ?></p>
    <?php endif; ?>
</body>

</html>