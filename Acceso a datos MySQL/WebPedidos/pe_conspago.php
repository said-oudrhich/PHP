<?php
require_once("funciones.php");

if (!isset($_COOKIE['NOMBRE'])) {
    header("Location: pe_login.php");
    exit;
}
try {
    $conexion = conectarBD();

    $clientes = deplegableCustomerNumber($conexion);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $cliente = limpiar($_POST["cliente"]);
        $fecha_inicio = limpiar($_POST["inicio"]);
        $fecha_fin = limpiar($_POST["fin"]);
        
        $pagos = pagosCliente($conexion,$cliente,$fecha_inicio,$fecha_fin);
        foreach ($pagos as $pago) {
            $total += $pago['amount'];
        }
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
    <title>Consulta de pagos por cliente</title>
</head>
<body>

<?php include("header.php"); ?>

<a href="pe_inicio.php">Volver al menú</a>

<h1>Consulta de pagos por cliente</h1>

<?php if (isset($mensaje)): ?>
    <p style="color:red;"><?php echo htmlspecialchars($mensaje); ?></p>
<?php endif; ?>

<form method="POST">
    <label for="cliente">Cliente:</label>
    <select name="cliente" id="cliente" required>
        <option value="">-- Selecciona un cliente --</option>
        <?php foreach ($clientes as $c): ?>
            <option value="<?php echo htmlspecialchars($c['customerNumber']); ?>"
                <?php if (isset($cliente) && $cliente == $c['customerNumber']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($c['customerNumber']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <br><br>

    <label for="inicio">Fecha inicio:</label>
    <input type="date" name="inicio" id="inicio" value="<?php echo isset($fecha_inicio) ? htmlspecialchars($fecha_inicio) : ''; ?>">

    <label for="fin">Fecha fin:</label>
    <input type="date" name="fin" id="fin" value="<?php echo isset($fecha_fin) ? htmlspecialchars($fecha_fin) : ''; ?>">

    <br><br>

    <button type="submit">Consultar</button>
</form>


<?php if (!empty($pagos)): ?>
    <h2>Pagos del cliente <?php echo htmlspecialchars($cliente); ?></h2>

    <table border="1" cellpadding="5">
        <tr>
            <th>Check Number</th>
            <th>Fecha</th>
            <th>Importe</th>
        </tr>
        <?php foreach ($pagos as $pago): ?>
            <tr>
                <td><?php echo htmlspecialchars($pago['checkNumber']); ?></td>
                <td><?php echo htmlspecialchars($pago['paymentDate']); ?></td>
                <td><?php echo htmlspecialchars(number_format($pago['amount'],2)); ?> €</td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th colspan="2">Total</th>
            <th><?php echo number_format($total,2); ?> €</th>
        </tr>
    </table>
<?php elseif ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
    <p>No se encontraron pagos para ese cliente en el periodo indicado.</p>
<?php endif; ?>

</body>
</html>
