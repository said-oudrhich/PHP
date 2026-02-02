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
        // Obtener el stock máximo disponible del producto
        $producto = obtenerProducto($conexion, $productCode);
        $stockDisponible = $producto['quantityInStock'];
        
        // Calcular cantidad total que habría en el carrito
        $cantidadActual = isset($carrito[$productCode]) ? $carrito[$productCode] : 0;
        $cantidadTotal = $cantidadActual + $cantidad;

        if ($cantidadTotal > $stockDisponible) {
            $mensaje = "Error: No hay suficiente stock. Máximo disponible: " . ($stockDisponible - $cantidadActual) . " unidades.";
        } else {
            if (isset($carrito[$productCode])) {
                $carrito[$productCode] += $cantidad;
            } else {
                $carrito[$productCode] = $cantidad;
            }
            guardarCarrito($carrito);
            $mensaje = "Producto añadido al carrito.";
        }
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
    } elseif (!verificarCheckNumberUnico($conexion, $customerNumber, $checkNumber)) {
        $mensaje = "Error: Este número de cheque ya existe para su cuenta. Use otro número.";
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
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include("header.php"); ?>

<div class="wp-container">
    <h1>Realizar Pedido</h1>
    <p><strong>Cliente:</strong> <?= htmlspecialchars($customerNumber) ?></p>
    <p><a class="wp-link" href="pe_inicio.php">← Volver</a></p>

    <?php if (!empty($mensaje)) echo renderMessage($mensaje, strpos($mensaje,'Error')===0 ? 'error' : 'success'); ?>

    <fieldset>
        <legend>Añadir Productos</legend>
        <form method="post">
            <div class="wp-form-group">
                <label for="productCode">Producto:</label>
                <select id="productCode" name="productCode" required>
                    <?php foreach ($productos as $prod): ?>
                        <option value="<?= htmlspecialchars($prod['productCode']) ?>">
                            <?= htmlspecialchars($prod['productName']) ?> (<?= htmlspecialchars(number_format($prod['buyPrice'],2)) ?> €)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="wp-form-group">
                <label for="quantity">Cantidad:</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" required>
            </div>

            <button class="wp-button wp-button-primary" type="submit" name="añadir">Añadir al Carrito</button>
        </form>
    </fieldset>

    <?php if (!empty($carrito)): ?>
    <fieldset class="mt-16">
        <legend>Carrito de Compra</legend>

        <?php 
            // Obtener detalles del carrito
            $detallesCarrito = obtenerDetallesCarrito($conexion, $carrito);
            $carritoDetalles = $detallesCarrito['items'];
            $totalCarrito = $detallesCarrito['total'];
        ?>

        <table class="wp-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Producto</th>
                    <th class="text-right">Cantidad</th>
                    <th class="text-right">Precio Unit.</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($carritoDetalles as $item): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($item['codigo']) ?></strong></td>
                    <td><?= htmlspecialchars($item['nombre']) ?></td>
                    <td class="text-right"><?= (int)$item['cantidad'] ?></td>
                    <td class="text-right"><?= number_format($item['precio'], 2) ?> €</td>
                    <td class="text-right"><strong><?= number_format($item['subtotal'], 2) ?> €</strong></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr style="background: #f0f0f0; font-weight: 600;">
                    <td colspan="4" class="text-right"><strong>TOTAL DEL PEDIDO:</strong></td>
                    <td class="text-right" style="color: #0b67a3; font-size: 16px;"><?= number_format($totalCarrito, 2) ?> €</td>
                </tr>
            </tfoot>
        </table>

        <div class="wp-button-group mt-16">
            <form action="pe_altaped.php" method="post" style="flex: 1;">
                <div class="wp-form-group">
                    <label for="checkNumber">Número de Pago (AA99999):</label>
                    <input type="text" id="checkNumber" name="checkNumber" placeholder="AB12345" required>
                </div>
                <button class="wp-button wp-button-primary wp-button-full" type="submit" name="confirmar">Confirmar Pedido</button>
            </form>
            <form action="pe_altaped.php" method="post" style="flex: 1;">
                <button class="wp-button wp-button-secondary wp-button-full" type="submit" name="vaciar">Vaciar Carrito</button>
            </form>
        </div>
    </fieldset>
    <?php endif; ?>
</div>

</body>
</html>
