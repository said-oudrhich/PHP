<?php
include("conexion.php");
include("funciones.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = limpiar($_POST["nombre"]);

    try {
        $conn = conectarBD();

        $nuevo_id = generarNuevoId_categoria($conn);
        insertarCategoria($conn, $nuevo_id, $nombre);

        $mensaje = "Categoría '$nombre' creada con ID $nuevo_id.";
    } catch (PDOException $e) {
        $mensaje = "Error: " . $e->getMessage();
    } finally {
        $conn = null;
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
    <form method="POST" action="">
        <label>Nombre de la producto:</label><br>
        <input type="text" name="nombre" required>
        <br><br>
        <label>Precio del producto:</label><br>
        <input type="number" name="precio" required>
        <br><br>
        <label>Categoria del producto:</label><br>
        <input type="text" name="categoria" required>
        <br><br>
        <input type="submit" value="Dar de alta">
    </form>

    <?php if ($mensaje): ?>
        <p><?= $mensaje ?></p>
    <?php endif; ?>
</body>

</html>