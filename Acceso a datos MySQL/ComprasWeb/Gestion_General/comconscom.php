<?php
require_once("../funciones.php");
require_once("../conexion.php");

$mensaje = "";

$conn = conectarBD();

// Cargamos clientes para el desplegable
$clientes = desplegableClientes($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nif = limpiar($_POST["nif"]);
    $fecha_desde = limpiar($_POST["fecha_desde"]);
    $fecha_hasta = limpiar($_POST["fecha_hasta"]);

    try {
        // Obtenemos las compras del cliente en el periodo seleccionado
        $compras = obtenerComprasPorClienteYFecha($conn, $nif, $fecha_desde, $fecha_hasta);
        if (!empty($compras)) {
            $mensaje = "Consulta realizada correctamente.";
        } else {
            $mensaje = "No hay compras para el cliente en el periodo seleccionado.";
        }
    } catch (PDOException $e) {
        $mensaje = mostrarError($e, "Consulta de Compras");
    } finally {
        $conn = null;
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Consulta de Compras por Cliente y Fecha</title>
</head>

<body>
    <h2>Consulta de Compras por Cliente y Fecha</h2>
    <form method="POST" action="comconscom.php">
        <label>Seleccione un cliente:</label><br>
        <select name="nif" required>
            <?php
            foreach ($clientes as $cliente) {
                echo "<option value='" . $cliente['nif'] . "'>" . $cliente['nombre'] . " (" . $cliente['nif'] . ")</option>";
            }
            ?>
        </select><br><br>
        <label>Fecha desde:</label><br>
        <input type="date" name="fecha_desde" required><br><br>
        <label>Fecha hasta:</label><br>
        <input type="date" name="fecha_hasta" required><br><br>
        <input type="submit" value="Consultar">
    </form>

    <?php
    if (!empty($compras)) {
        echo "<h3>Compras del Cliente " . htmlspecialchars($nif) . " desde " . htmlspecialchars($fecha_desde) . " hasta " . htmlspecialchars($fecha_hasta) . ":</h3>";
        echo "<ul>";
        foreach ($compras as $compra) {
            echo "<li>Producto: " . htmlspecialchars($compra['nombre']) . "</li>";
        }
        echo "</ul>";
    }
    if ($mensaje) {
        echo "<p>" . htmlspecialchars($mensaje) . "</p>";
    }
    ?>

</body>

</html>