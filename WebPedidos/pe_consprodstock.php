<?php
require_once("funciones.php");

if (!isset($_COOKIE['NOMBRE'])) {
    header("Location: pe_login.php");
    exit();
}

try {
    $conn = conectarBD();
    $productos = productos($conn);

    $productoSeleccionado = null;
    $stock = null;

    if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["producto"])) {
        $productoSeleccionado = limpiar($_POST["producto"]);
        $stock = stockProducto($conn, $productoSeleccionado);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conn = null;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock de productos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include("header.php"); ?>

<div class="wp-container">
    <h1>Consulta de Stock de Productos</h1>
    <p><a class="wp-link" href="pe_inicio.php">← Volver al menú</a></p>

    <?php if (isset($mensaje)) echo renderMessage($mensaje,'error'); ?>

    <fieldset>
        <legend>Seleccionar Producto</legend>
        <form method="post" action="pe_consprodstock.php">
            <div class="wp-form-group">
                <label for="producto">Producto:</label>
                <select name="producto" id="producto" required>
                    <option value="">-- Seleccione --</option>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?= htmlspecialchars($producto['productCode']) ?>"
                            <?= ($productoSeleccionado === $producto['productCode']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($producto['productName']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button class="wp-button wp-button-primary" type="submit">Consultar Stock</button>
        </form>
    </fieldset>

    <?php if ($productoSeleccionado): ?>
        <fieldset class="mt-16">
            <legend>Resultado de la Consulta</legend>
            <p>
                <strong>Producto:</strong> <?= htmlspecialchars($productoSeleccionado) ?><br>
                <strong>Stock disponible:</strong> <span style="color: #0b67a3; font-weight: 600;">
                    <?= htmlspecialchars($stock ?? 'No disponible') ?>
                </span> unidades
            </p>
        </fieldset>
    <?php endif; ?>

</div>

</body>
</html>
