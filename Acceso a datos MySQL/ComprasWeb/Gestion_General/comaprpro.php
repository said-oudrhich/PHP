Aprovisionar Productos (comaprpro.php): asignar una cantidad de un determinado producto
a un almacén. Se seleccionarán los nombres de los productos y los números de los almacenes
desde listas desplegables. El usuario introducirá la cantidad del producto a aprovisionar.

<?php
require("../conexion.php");
require("../funciones.php");
$mensaje = "";
$conn = conectarBD();
// Cargamos productos y almacenes para los desplegables
$productos = desplegableProducto($conn);
$almacenes = desplegableAlmacen($conn);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = limpiar($_POST["id_producto"]);
    $num_almacen = limpiar($_POST["num_almacen"]);
    $cantidad = limpiar($_POST["cantidad"]);
    try {
        // Insertamos el aprovisionamiento
        insertarAprovisionamiento($conn, $num_almacen, $id_producto, $cantidad);
        $mensaje = "Aprovisionamiento realizado: $cantidad unidades del producto '$id_producto' al almacén '$num_almacen'.";
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
    <title>Aprovisionar Productos</title>
</head>

<body>
    <h2>Aprovisionar Productos</h2>
    <form method="POST" action="comaprpro.php">
        <label>Producto:</label><br>
        <select name="id_producto" required>
            <?php
            foreach ($productos as $producto) {
                echo "<option value='" . $producto['id_producto'] . "'>" . $producto['nombre'] . "</option>";
            }
            ?>
        </select><br><br>
        <label>Almacén:</label><br>
        <select name="num_almacen" required>
            <?php
            foreach ($almacenes as $almacen) {
                echo "<option value='" . $almacen['num_almacen'] . "'>" . $almacen['localidad'] . "</option>";
            }
            ?>
        </select><br><br>
        <label>Cantidad:</label><br>
        <input type="number" name="cantidad" required>
        <br><br>
        <input type="submit" value="Aprovisionar">
    </form>
    <?php if ($mensaje): ?>
        <p><?= $mensaje ?></p>
    <?php endif; ?>
</body>

</html>