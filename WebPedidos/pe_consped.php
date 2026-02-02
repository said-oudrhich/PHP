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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Pedidos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include("header.php"); ?>

<div class="wp-container">
    <h1>Consulta de Pedidos por Cliente</h1>
    <p><a class="wp-link" href="pe_inicio.php">← Volver</a></p>

    <?php if ($error) echo renderMessage($error,'error'); ?>

    <fieldset>
        <legend>Seleccionar Cliente</legend>
        <form method="post">
            <div class="wp-form-group">
                <label for="cliente">Cliente:</label>
                <?php echo renderSelectClientes($conexion, $clienteSeleccionado); ?>
            </div>
            <button class="wp-button wp-button-primary" type="submit">Consultar</button>
        </form>
    </fieldset>

    <?php if ($clienteSeleccionado): ?>

        <?php if (!empty($pedidos)): ?>
            <h2 class="mt-16">Pedidos del cliente <?= htmlspecialchars($clienteSeleccionado) ?></h2>

            <?php foreach ($pedidos as $pedido): ?>
                <fieldset class="mt-16">
                    <legend>
                        Pedido #<?= htmlspecialchars($pedido['orderNumber']) ?> | 
                        Fecha: <?= htmlspecialchars($pedido['orderDate']) ?> | 
                        Estado: <?= htmlspecialchars($pedido['status']) ?>
                    </legend>

                    <?php $detalles = $detallesPorPedido[$pedido['orderNumber']] ?? []; ?>

                    <?php if (!empty($detalles)): ?>
                        <table class="wp-table">
                            <thead>
                                <tr>
                                    <th>Línea</th>
                                    <th>Producto</th>
                                    <th class="text-right">Cantidad</th>
                                    <th class="text-right">Precio Unitario</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($detalles as $d): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($d['orderLineNumber']) ?></td>
                                        <td><?= htmlspecialchars($d['productName']) ?></td>
                                        <td class="text-right"><?= htmlspecialchars($d['quantityOrdered']) ?></td>
                                        <td class="text-right"><?= number_format($d['priceEach'], 2) ?> €</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-muted"><em>Sin líneas de pedido</em></p>
                    <?php endif; ?>
                </fieldset>

            <?php endforeach; ?>

        <?php else: ?>
            <?php echo renderMessage('Este cliente no tiene pedidos.','info'); ?>
        <?php endif; ?>

    <?php endif; ?>

</div>

</body>
</html>
