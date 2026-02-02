<?php
require_once("funciones.php");

if (!isset($_COOKIE['NOMBRE'])) {
    header("Location: pe_login.php");
    exit;
}
try {
    $conexion = conectarBD();

    $clientes = deplegableCustomerNumber($conexion);
    $total = 0;

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $cliente = limpiar($_POST["cliente"]);
        $fecha_inicio = limpiar($_POST["inicio"]);
        $fecha_fin = limpiar($_POST["fin"]);
        
        $pagos = pagosCliente($conexion,$cliente,$fecha_inicio,$fecha_fin);
        $total = calcularTotalItems($pagos, 'amount');
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
    <title>Consulta de pagos por cliente</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include("header.php"); ?>

<div class="wp-container">
    <h1>Consulta de Pagos por Cliente</h1>
    <p><a class="wp-link" href="pe_inicio.php">← Volver al menú</a></p>

    <?php if (isset($mensaje)) echo renderMessage($mensaje,'error'); ?>

    <fieldset>
        <legend>Seleccionar Cliente y Fechas</legend>
        <form method="POST">
            <div class="wp-form-group">
                <label for="cliente">Cliente:</label>
                <?php echo renderSelectClientes($conexion, isset($cliente) ? $cliente : null); ?>
            </div>

            <div class="wp-form-group">
                <label for="inicio">Fecha Inicio:</label>
                <input type="date" name="inicio" id="inicio" value="<?php echo isset($fecha_inicio) ? htmlspecialchars($fecha_inicio) : ''; ?>">
            </div>

            <div class="wp-form-group">
                <label for="fin">Fecha Fin:</label>
                <input type="date" name="fin" id="fin" value="<?php echo isset($fecha_fin) ? htmlspecialchars($fecha_fin) : ''; ?>">
            </div>

            <button class="wp-button wp-button-primary" type="submit">Consultar Pagos</button>
        </form>
    </fieldset>

    <?php if (!empty($pagos)): ?>
        <fieldset class="mt-16">
            <legend>Pagos del Cliente <?php echo htmlspecialchars($cliente); ?></legend>

            <table class="wp-table">
                <thead>
                    <tr>
                        <th>Número de Cheque</th>
                        <th>Fecha de Pago</th>
                        <th class="text-right">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pagos as $pago): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pago['checkNumber']); ?></td>
                            <td><?php echo htmlspecialchars($pago['paymentDate']); ?></td>
                            <td class="text-right"><?php echo htmlspecialchars(number_format($pago['amount'],2)); ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="background: #f0f0f0; font-weight: 600;">
                        <td colspan="2"><strong>Total Pagos:</strong></td>
                        <td class="text-right"><strong><?php echo number_format($total ?? 0,2); ?> €</strong></td>
                    </tr>
                </tfoot>
            </table>
        </fieldset>
    <?php elseif ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
        <?php echo renderMessage('No se encontraron pagos para ese cliente en el periodo indicado.','info'); ?>
    <?php endif; ?>

</div>

</body>
</html>
