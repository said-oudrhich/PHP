<?php
session_start(); // Necesario para el carrito
require_once("funciones.php");

// Verificación de login (usando la cookie que creaste en el ej 1)
if (!isset($_COOKIE['NOMBRE'])) {
    header("Location: pe_login.php");
    exit();
}

$customerNumber = $_COOKIE['NOMBRE'];
$mensaje = "";
$conexion = conectarBD();

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// --- LÓGICA DE CONTROL ---

// Acción: Añadir producto al carrito
if (isset($_POST['añadir'])) {
    $productCode = $_POST['productCode'];
    $cantidad = (int)$_POST['quantity'];

    if ($cantidad > 0) {
        // Si ya existe, sumamos la cantidad
        if (isset($_SESSION['carrito'][$productCode])) {
            $_SESSION['carrito'][$productCode] += $cantidad;
        } else {
            $_SESSION['carrito'][$productCode] = $cantidad;
        }
        $mensaje = "Producto añadido al carrito.";
    }
}

// Acción: Vaciar carrito
if (isset($_POST['vaciar'])) {
    $_SESSION['carrito'] = [];
    $mensaje = "Carrito vaciado.";
}

// Acción: Confirmar pedido y pago
if (isset($_POST['confirmar'])) {
    $checkNumber = limpiar($_POST['checkNumber']);

    if (empty($_SESSION['carrito'])) {
        $mensaje = "Error: El carrito está vacío.";
    } elseif (!validarCheckNumber($checkNumber)) {
        $mensaje = "Error: El formato del número de pago es incorrecto (AA99999).";
    } else {
        try {
            // Usamos la función realizarPedido de funciones.php
            realizarPedido($conexion, $_SESSION['carrito'], $customerNumber, $checkNumber);
            $_SESSION['carrito'] = []; // Limpiamos tras éxito
            $mensaje = "Pedido realizado con éxito.";
        } catch (Exception $e) {
            $mensaje = "Error al procesar el pedido: " . $e->getMessage();
        }
    }
}

// Obtener datos para la vista
$productos = obtenerProductosConStock($conexion);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Alta de Pedido</title>
</head>

<body>
    <h2>Realizar Pedido - Cliente: <?= htmlspecialchars($customerNumber) ?></h2>
    <p><a href="pe_inicio.php">Volver al Menú</a></p>

    <?php if ($mensaje): ?>
        <p><strong><?= $mensaje ?></strong></p>
    <?php endif; ?>

    <fieldset>
        <legend>Añadir Productos</legend>
        <form method="post" action="pe_altaped.php">
            <label>Producto:</label>
            <select name="productCode" required>
                <?php foreach ($productos as $prod): ?>
                    <option value="<?= $prod['productCode'] ?>">
                        <?= $prod['productName'] ?> (Stock: <?= $prod['buyPrice'] ?>€)
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Cantidad:</label>
            <input type="number" name="quantity" value="1" min="1" required>

            <input type="submit" name="añadir" value="Añadir al Carrito">
        </form>
    </fieldset>

    <br>

    <?php if (!empty($_SESSION['carrito'])): ?>
        <fieldset>
            <legend>Resumen del Pedido</legend>
            <table border="1">
                <tr>
                    <th>Código Producto</th>
                    <th>Cantidad</th>
                </tr>
                <?php foreach ($_SESSION['carrito'] as $code => $qty): ?>
                    <tr>
                        <td><?= htmlspecialchars($code) ?></td>
                        <td><?= $qty ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <form method="post" action="pe_altaped.php" style="margin-top: 15px;">
                <label>Número de Pago (AA99999):</label>
                <input type="text" name="checkNumber" placeholder="Ej: AB12345">
                <br><br>
                <input type="submit" name="confirmar" value="Finalizar Pedido">
                <input type="submit" name="vaciar" value="Vaciar Carrito">
            </form>
        </fieldset>
    <?php endif; ?>

</body>

</html>