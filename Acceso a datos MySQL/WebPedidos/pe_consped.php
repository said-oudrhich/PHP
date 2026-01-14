<?php
require_once("funciones.php");

if (!isset($_COOKIE['NOMBRE'])) {
    header("Location: pe_login.php");
    exit;
}


$clientes = [];
$clienteSeleccionado = null;
$pedidos = [];
$detallesPorPedido = [];
$error = null;

try {
    $conexion = conectarBD();

    $clientes = deplegableCustomerNumber($conexion);

    if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["cliente"])) {
        $clienteSeleccionado = limpiar($_POST["cliente"]);
        $pedidos = pedidosCliente($conexion, $clienteSeleccionado);

        foreach ($pedidos as $p) {
            $detallesPorPedido[$p['orderNumber']] = detallesPedido($conexion, $p['orderNumber']);
        }
    }

} catch (PDOException $e) {
    $error = "Error al acceder a la base de datos";
} finally {
    $conexion = null;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Pedidos</title>
</head>
<body>

<?php include("header.php"); ?>

<h1>Consulta de Pedidos por Cliente</h1>
<p><a href="pe_inicio.php">Volver</a></p>

<?php if ($error): ?>
    <p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post">
    <label for="cliente">Cliente:</label>
    <select name="cliente" id="cliente" required>
        <option value="">-- Seleccione --</option>
        <?php foreach ($clientes as $c): ?>
            <option value="<?= htmlspecialchars($c['customerNumber']) ?>"
                <?= ($clienteSeleccionado == $c['customerNumber']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['customerNumber']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button>Consultar</button>
</form>

<?php if ($clienteSeleccionado): ?>

    <?php if (!empty($pedidos)): ?>
        <h2>Pedidos del cliente <?= htmlspecialchars($clienteSeleccionado) ?></h2>

        <?php foreach ($pedidos as $pedido): ?>
            <h3>
                Pedido #<?= htmlspecialchars($pedido['orderNumber']) ?> |
                Fecha <?= htmlspecialchars($pedido['orderDate']) ?> |
                Estado <?= htmlspecialchars($pedido['status']) ?>
            </h3>

            <?php $detalles = $detallesPorPedido[$pedido['orderNumber']] ?? []; ?>

            <?php if (!empty($detalles)): ?>
                <table border="1" cellpadding="5">
                    <tr>
                        <th>Línea</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                    </tr>
                    <?php foreach ($detalles as $d): ?>
                        <tr>
                            <td><?= htmlspecialchars($d['orderLineNumber']) ?></td>
                            <td><?= htmlspecialchars($d['productName']) ?></td>
                            <td><?= htmlspecialchars($d['quantityOrdered']) ?></td>
                            <td><?= number_format($d['priceEach'], 2) ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p><em>Sin líneas de pedido</em></p>
            <?php endif; ?>

        <?php endforeach; ?>

    <?php else: ?>
        <p>Este cliente no tiene pedidos.</p>
    <?php endif; ?>

<?php endif; ?>

</body>
</html>
