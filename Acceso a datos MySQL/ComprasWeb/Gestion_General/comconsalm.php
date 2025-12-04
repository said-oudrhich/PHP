Consulta de Almacenes (comconsalm.php): se mostrarán los almacenes en un desplegable
y se mostrará la información de los productos disponibles en el almacén seleccionado.

<?php
require("../conexion.php");
require("../funciones.php");

$mensaje = "";
$conn = conectarBD();
// Cargamos almacenes para el desplegable
$almacenes = desplegableAlmacen($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num_almacen = limpiar($_POST["num_almacen"]);

    try {
        // Obtenemos los productos del almacén seleccionado
        $productos = obtenerProductosEnAlmacen($conn, $num_almacen);
        if (!empty($productos)) {
            $mensaje = "Consulta realizada correctamente.";
        } else {
            $mensaje = "No hay productos en el almacén seleccionado.";
        }
    } catch (PDOException $e) {
        $mensaje = mostrarError($e, "Consulta de Almacén");
    } finally {
        $conn = null;
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Consulta de Almacenes</title>
</head>

<body>
    <h2>Consulta de Almacenes</h2>
    <form method="POST" action="comconsalm.php">
        <label>Seleccione un almacén:</label><br>
        <select name="num_almacen" required>
            <?php // Rellenamos el desplegable con los almacenes
            foreach ($almacenes as $almacen) {
                echo "<option value='" . $almacen['num_almacen'] . "'>Almacén " . $almacen['num_almacen'] . " - " . $almacen['localidad'] . "</option>";
            }
            ?>
        </select><br><br>
        <input type="submit" value="Consultar">
    </form>

    <?php
    if (!empty($productos)) {
        echo "<h3>Productos en el Almacén " . htmlspecialchars($num_almacen) . ":</h3>";
        echo "<ul>";
        foreach ($productos as $producto) {
            echo "<li>" . htmlspecialchars($producto['nombre']) . " - Cantidad: " . htmlspecialchars($producto['cantidad']) . "</li>";
        }
        echo "</ul>";
    }

    if ($mensaje) {
        echo "<p>" . htmlspecialchars($mensaje) . "</p>";
    }
    ?>
</body>

</html>