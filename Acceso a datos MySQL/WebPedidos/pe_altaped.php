<?php
require_once("funciones.php");

if (!isset($_COOKIE['NOMBRE'])) {
    header("Location: pe_login.php");
    exit();
}

$customerNumber = $_COOKIE['NOMBRE'];
$mensaje = "";
$conexion = conectarBD();

// Obtener carrito (array asociativo)
function obtenerCarrito() {
    return isset($_COOKIE['carrito']) && is_array($_COOKIE['carrito'])
        ? $_COOKIE['carrito']
        : [];
}

// Guardar carrito en cookies (array asociativo)
function guardarCarrito($carrito) {
    foreach ($carrito as $codigo => $cantidad) {
        setcookie("carrito[$codigo]", $cantidad, time() + 86400, '/');
        $_COOKIE['carrito'][$codigo] = $cantidad;
    }
}

// Vaciar carrito
function vaciarCarrito() {
    if (isset($_COOKIE['carrito'])) {
        foreach ($_COOKIE['carrito'] as $codigo => $valor) {
            setcookie("carrito[$codigo]", '', time() - 3600, '/');
        }
    }
    unset($_COOKIE['carrito']);
}

$carrito = obtenerCarrito();

// Añadir producto
if (isset($_POST['añadir'])) {
    $productCode = limpiar($_POST['productCode']);
    $cantidad = (int)$_POST['quantity'];

    if ($cantidad > 0) {
        if (isset($carrito[$productCode])) {
            $carrito[$productCode] += $cantidad;
        } else {
            $carrito[$productCode] = $cantidad;
        }
        guardarCarrito($carrito);
        $mensaje = "Producto añadido al carrito.";
    }
}

// Vaciar carrito
if (isset($_POST['vaciar'])) {
    vaciarCarrito();
    $carrito = [];
    $mensaje = "Carrito vaciado.";
}

// Confirmar pedido
if (isset($_POST['confirmar'])) {
    $checkNumber = limpiar($_POST['checkNumber']);

    if (empty($carrito)) {
        $mensaje = "Error: El carrito está vacío.";
    } elseif (!validarCheckNumber($checkNumber)) {
        $mensaje = "Error: Formato incorrecto (AA99999).";
    } else {
        try {
            realizarPedido($conexion, $carrito, $customerNumber, $checkNumber);
            vaciarCarrito();
            $carrito = [];
            $mensaje = "Pedido realizado con éxito.";
        } catch (Exception $e) {
            $mensaje = "Error: " . $e->getMessage();
        }
    }
}

$productos = obtenerProductosConStock($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alta de Pedido</title>
</head>
<body>

<?php include("header.php"); ?>

<h2>Realizar Pedido - Cliente: <?= htmlspecialchars($customerNumber) ?></h2>
<p><a href="pe_inicio.php">Volver</a></p>

<?php if ($mensaje): ?>
    <p><strong><?= htmlspecialchars($mensaje) ?></strong></p>
<?php endif; ?>

<fieldset>
    <legend>Añadir Productos</legend>
    <form method="post">
        <label>Producto:</label>
        <select name="productCode" required>
            <?php foreach ($productos as $prod): ?>
                <option value="<?= htmlspecialchars($prod['productCode']) ?>">
                    <?= htmlspecialchars($prod['productName']) ?> (<?= $prod['buyPrice'] ?>€)
                </option>
            <?php endforeach; ?>
        </select>

        <label>Cantidad:</label>
        <input type="number" name="quantity" value="1" min="1" required>

        <input type="submit" name="añadir" value="Añadir">
    </form>
</fieldset>

<br>

<?php if (!empty($carrito)): ?>
<fieldset>
    <legend>Carrito</legend>

    <table border="1">
        <tr>
            <th>Código</th>
            <th>Cantidad</th>
        </tr>
        <?php foreach ($carrito as $code => $qty): ?>
        <tr>
            <td><?= htmlspecialchars($code) ?></td>
            <td><?= (int)$qty ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <br>

    <form method="post">
        <label>Nº Pago (AA99999):</label>
        <input type="text" name="checkNumber" placeholder="AB12345" required>

        <br><br>

        <input type="submit" name="confirmar" value="Confirmar Pedido">
        <input type="submit" name="vaciar" value="Vaciar Carrito">
    </form>
</fieldset>
<?php endif; ?>

</body>
</html>
