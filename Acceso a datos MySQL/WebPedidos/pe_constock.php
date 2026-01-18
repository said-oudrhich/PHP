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
</head>
<body>

<?php include("header.php"); ?>

<a href="pe_inicio.php">Volver al menú</a>

<h1>Consultar stock por línea de producto</h1>

<?php if (isset($mensaje)): ?>
    <p style="color:red;"><?php echo $mensaje; ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label for="productLine">Selecciona una línea de producto:</label>
    <br><br>
    <select name="productLine" id="productLine" required>
        <option value="">-- Selecciona una opción --</option>
        <?php foreach ($AllproductLine as $line): ?>
            <option value="<?php echo htmlspecialchars($line['productLine']); ?>"
                <?php if (isset($productLine) && $productLine === $line['productLine']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($line['productLine']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>
    <button type="submit">Ver stock</button>
</form>

<?php if (isset($stock)): ?>
    <h2>Resultado</h2>
    <p>
        Stock total de la línea
        <strong><?php echo htmlspecialchars($productLine); ?></strong>:
        <strong><?php echo htmlspecialchars($stock['stock']); ?></strong>
    </p>
<?php endif; ?>


</body>
</html>
