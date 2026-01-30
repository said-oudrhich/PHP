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
</head>
<body>

<?php include("header.php"); ?>

<div class="wp-container">
    <a class="wp-link" href="pe_inicio.php">Volver al menú</a>

    <h1>Consulta de stock de productos</h1>

    <?php if (isset($mensaje)) echo renderMessage($mensaje,'error'); ?>

    <form method="post" action="pe_consprodstock.php">
        <label for="producto">Producto:</label><br>

        <select name="producto" id="producto" required>
            <option value="">-- Seleccione --</option>
            <?php foreach ($productos as $producto): ?>
                <option value="<?= htmlspecialchars($producto['productCode']) ?>"
                    <?= ($productoSeleccionado === $producto['productCode']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($producto['productName']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <br><br>
        <button type="submit">Consultar</button>
    </form>

    <?php if ($productoSeleccionado): ?>
        <h2>Resultado</h2>
        <p>
            Producto: <strong><?= htmlspecialchars($productoSeleccionado) ?></strong><br>
            Stock disponible: <strong><?= htmlspecialchars($stock) ?></strong>
        </p>
    <?php endif; ?>

</div>

</body>
</html>
