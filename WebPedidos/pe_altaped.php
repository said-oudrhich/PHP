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
    if (isset($_COOKIE['carrito'])) {
        $carrito = unserialize($_COOKIE['carrito']);
        return is_array($carrito) ? $carrito : [];
    }
    return [];
}

// Guardar carrito en cookie única serializada
function guardarCarrito($carrito) {
    $valor = serialize($carrito);
    setcookie("carrito", $valor, time() + 86400, '/');
    $_COOKIE['carrito'] = $valor;
}

// Vaciar carrito
function vaciarCarrito() {
    setcookie("carrito", '', time() - 3600, '/');
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<?php include("header.php"); ?>

<div class="wp-container">
    <h2>Realizar Pedido - Cliente: <?= htmlspecialchars($customerNumber) ?></h2>
    <p><a class="wp-link" href="pe_inicio.php">Volver</a></p>

    <?php if (!empty($mensaje)) echo renderMessage($mensaje, strpos($mensaje,'Error')===0 ? 'error' : 'success'); ?>

    <fieldset>
    <legend>Añadir Productos</legend>
    <form method="post">
        <label>Producto:</label>
        <select name="productCode" required>
            <?php foreach ($productos as $prod): ?>
                <option value="<?= htmlspecialchars($prod['productCode']) ?>">
                    <?= htmlspecialchars($prod['productName']) ?> (<?= htmlspecialchars(number_format($prod['buyPrice'],2)) ?> €)
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

        <table class="wp-table">
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

        <form action="pe_altaped.php" method="post">
            <label>Nº Pago (AA99999):</label>
            <input type="text" name="checkNumber" placeholder="AB12345" required>

            <br><br>

            <input type="submit" name="confirmar" value="Confirmar Pedido">
        </form><br>
        <form action="pe_altaped.php" method="post">
            <input type="submit" name="vaciar" value="Vaciar Carrito">
        </form>
        
    </fieldset>
    <?php endif; ?>
</div>

</body>
</html>
