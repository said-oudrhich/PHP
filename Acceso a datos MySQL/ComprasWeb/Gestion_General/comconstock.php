<?php
require("../conexion.php");
require("../funciones.php");

$mensaje = "";

$conn = conectarBD();
$productos = desplegableProducto($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = limpiar($_POST["id_producto"]);
    try {
        $stock = stockProductoEnAlmacen($conn, $id_producto);
        if (empty($stock)) {
            $mensaje = "No hay stock disponible para el producto '$id_producto'.";
        } else {
            $mensaje = "Stock del producto '$id_producto':<br>";
        }
    } catch (PDOException $e) {
        $mensaje = mostrarError($e, "Consulta de Stock");
    } finally {
        $conn = null;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Consulta de Stock</title>
</head>

<body>
    <h2>Consulta de Stock de Productos</h2>
    <form method="POST" action="comconstock.php">
        <label>Producto:</label><br>
        <select name="id_producto" required>
            <?php
            foreach ($productos as $producto) {
                echo "<option value='" . $producto['id_producto'] . "'>" . $producto['nombre'] . "</option>";
            }
            ?>
        </select><br><br>
        <input type="submit" value="Consultar Stock">
    </form>

    <?php if ($mensaje): ?>
        <p><?= $mensaje ?></p>
    <?php endif; ?>

    <?php if (!empty($stock)): ?>
        <?php foreach ($stock as $registro): ?>
            <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Almacén</th>
                        <th>Localidad</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stock as $registro): ?>
                        <tr>
                            <td><?= $registro['num_almacen'] ?></td>
                            <td><?= $registro['localidad'] ?></td>
                            <td><?= $registro['cantidad'] ?> unidades</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    <?php endif; ?>
</body>

</html>