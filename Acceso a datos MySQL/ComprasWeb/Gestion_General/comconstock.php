<?php
session_start();

if (!isset($_SESSION['NIF'])) {
    header("Location: ../Portal/comlogincli.php");
    exit();
}

require_once("../conexion.php");
require_once("../funciones.php");

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
    <?php require_once __DIR__ . '/../header.php'; ?>
    <h2>Consulta de Stock de Productos</h2>
    <form method="POST" action="comconstock.php">
        <label>Producto:</label><br>
        <select name="id_producto" required>
            <?php
            foreach ($productos as $producto) {
                echo "<option value='" . $producto['ID_PRODUCTO'] . "'>" . $producto['NOMBRE'] . "</option>";
            }
            ?>
        </select><br><br>
        <input type="submit" value="Consultar Stock">
    </form>

    <?php if ($mensaje): ?>
        <p><?= $mensaje ?></p>
    <?php endif; ?>

    <?php if (!empty($stock)): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Almacén</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stock as $registro): ?>
                    <tr>
                        <td><?= $registro['NUM_ALMACEN'] ?></td>
                        <td><?= (int)$registro['CANTIDAD'] ?> unidades</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>


</body>

</html>