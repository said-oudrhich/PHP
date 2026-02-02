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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include("header.php"); ?>

<div class="wp-container">
    <h1>Productos Vendidos entre Fechas</h1>
    <p><a class="wp-link" href="pe_inicio.php">← Volver al menú</a></p>

    <?php if (isset($mensaje)): echo renderMessage($mensaje,'error'); endif; ?>

    <fieldset>
        <legend>Seleccionar Rango de Fechas</legend>
        <form method="POST" action="">
            <div class="wp-form-group">
                <label for="inicio">Fecha Inicio:</label>
                <input type="date" id="inicio" name="inicio" required>
            </div>

            <div class="wp-form-group">
                <label for="fin">Fecha Fin:</label>
                <input type="date" id="fin" name="fin" required>
            </div>

            <button class="wp-button wp-button-primary" type="submit">Buscar Productos</button>
        </form>
    </fieldset>

    <?php if (isset($productos) && count($productos) > 0): ?>
        <fieldset class="mt-16">
            <legend>Resultados de la Búsqueda</legend>
            <table class="wp-table">
                <thead>
                    <tr>
                        <th>Código Producto</th>
                        <th class="text-right">Cantidad Vendida</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['productCode']); ?></td>
                            <td class="text-right"><?php echo htmlspecialchars($producto['quantityOrdered']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </fieldset>

    <?php elseif (isset($productos)): ?>
        <?php echo renderMessage('No hay resultados para esas fechas.','info'); ?>
    <?php endif; ?>
</div>

</body>
</html>
