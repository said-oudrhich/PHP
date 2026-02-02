<?php
require_once("funciones.php");

if (!isset($_COOKIE['NOMBRE'])) {
    header("Location: pe_login.php");
    exit;
}

try {
    $conexion = conectarBD();
    $AllproductLine = desplegarProductLine($conexion);

    if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["productLine"])) {
        $productLine = limpiar($_POST["productLine"]);
        $stock = stockProductLine($conexion, $productLine);
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock por línea de producto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include("header.php"); ?>

<div class="wp-container">
    <h1>Consultar Stock por Línea de Producto</h1>
    <p><a class="wp-link" href="pe_inicio.php">← Volver al menú</a></p>

    <?php if (isset($mensaje)) echo renderMessage($mensaje,'error'); ?>

    <fieldset>
        <legend>Seleccionar Línea de Producto</legend>
        <form method="POST" action="">
            <div class="wp-form-group">
                <label for="productLine">Línea de Producto:</label>
                <select name="productLine" id="productLine" required>
                    <option value="">-- Selecciona una opción --</option>
                    <?php foreach ($AllproductLine as $line): ?>
                        <option value="<?php echo htmlspecialchars($line['productLine']); ?>"
                            <?php if (isset($productLine) && $productLine === $line['productLine']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($line['productLine']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button class="wp-button wp-button-primary" type="submit">Ver Stock</button>
        </form>
    </fieldset>

    <?php if (isset($stock)): ?>
        <fieldset class="mt-16">
            <legend>Resultado de la Consulta</legend>
            <p>
                <strong>Línea:</strong> <?php echo htmlspecialchars($productLine); ?><br>
                <strong>Stock total:</strong> <span style="color: #0b67a3; font-weight: 600;">
                    <?php echo htmlspecialchars($stock['stock'] ?? 0); ?>
                </span> unidades
            </p>
        </fieldset>
    <?php endif; ?>

</div>

</body>
</html>
