<?php
require_once("funciones.php");

if (!isset($_COOKIE['NOMBRE'])) {
    header("Location: pe_login.php");
    exit;
}

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $fecha_inicio = limpiar($_POST["inicio"]);
        $fecha_fin = limpiar($_POST["fin"]);

        $conexion = conectarBD();
        $productos = productosVendidoEntreFechas($conexion,$fecha_inicio,$fecha_fin);
    }
} catch (Exception $e) {
    $mensaje = "Error: " . $e->getMessage();
} finally {
    $conexion = null;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos vendidos entre fechas</title>
</head>
<body>

<?php include("header.php"); ?>

<div class="wp-container">
    <a class="wp-link" href="pe_inicio.php">Volver al menú</a>

    <h1>Productos vendidos entre fechas</h1>

    <?php if (isset($mensaje)): echo renderMessage($mensaje,'error'); endif; ?>

    <form method="POST" action="">
        <label>Fecha inicio:</label>
        <input type="date" name="inicio" required>

        <label>Fecha fin:</label>
        <input type="date" name="fin" required>

        <button type="submit">Buscar</button>
    </form>

    <?php if (isset($productos) && count($productos) > 0): ?>
        <h2>Resultado</h2>

        <table class="wp-table">
            <tr>
                <th>Product Code</th>
                <th>Cantidad Vendida</th>
            </tr>

            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?php echo htmlspecialchars($producto['productCode']); ?></td>
                    <td><?php echo htmlspecialchars($producto['quantityOrdered']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

    <?php elseif (isset($productos)): ?>
        <p>No hay resultados para esas fechas.</p>
    <?php endif; ?>
</div>

</body>
</html>
