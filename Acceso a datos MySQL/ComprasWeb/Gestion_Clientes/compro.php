<?php
require_once("../funciones.php");
require_once("../conexion.php");
session_start();

if (!isset($_SESSION['NIF'])) {
    header("Location: ../Portal/comlogincli.php");
    exit();
}

$conexion = conectarBD();
$productos = desplegableProducto($conexion);
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = limpiar($_POST["producto"]);
    $cantidad = (int)limpiar($_POST["cantidad"]);
    $fecha_compra = date("Y-m-d");
    $NIF = $_SESSION['NIF'];

    if ($cantidad <= 0) {
        $mensaje = "La cantidad debe ser mayor a 0.";
    } else {
        try {
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conexion->beginTransaction();

            $stockInfo = stockProductoEnAlmacen($conexion, $id_producto);

            if (!$stockInfo || (int)$stockInfo['CANTIDAD'] < $cantidad) {
                $conexion->rollBack();
                $mensaje = "No hay suficiente stock disponible para realizar esta compra.";
            } else {
                $num_almacen = $stockInfo['NUM_ALMACEN'];

                insertarCompra($conexion, $NIF, $id_producto, $fecha_compra, $cantidad);
                reducirStock($conexion, $num_almacen, $id_producto, $cantidad);

                $conexion->commit();
                $mensaje = "Compra realizada con éxito.";
            }
        } catch (PDOException $e) {
            $conexion->rollBack();
            $mensaje = "Error en la base de datos: " . $e->getMessage();
        } finally {
            $conexion = null;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra de Productos</title>
</head>

<body>
    <?php require_once __DIR__ . '/../header.php'; ?>
    <h1>Compra de Productos</h1>
    <form action="compro.php" method="post">
        <label for="producto">Producto:</label>
        <select name="producto" id="producto">
            <?php foreach ($productos as $producto): ?>
                <option value="<?php echo $producto['ID_PRODUCTO']; ?>"><?php echo $producto['NOMBRE']; ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad">
        <br>
        <input type="submit" value="Comprar">
    </form>

    <?php if ($mensaje): ?>
        <p><?= $mensaje ?></p>
    <?php endif; ?>



</body>

</html>