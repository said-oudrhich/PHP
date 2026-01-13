<?php
/* Conexión a BD pedidos - PDO */
function conectarBD()
{
    $servername = "localhost";
    $username = "root";
    $password = "rootroot";
    $dbname = "pedidos";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
}

/* Limpiar datos de entrada */
function limpiar($dato)
{
    return htmlspecialchars(stripslashes(trim($dato)));
}

/* Verificar cliente usando bindParam */
function verificarCliente($conexion, $username, $password)
{
    $stmt = $conexion->prepare(
        "SELECT customerNumber 
         FROM customers 
         WHERE customerNumber = :user 
           AND contactLastName = :pass"
    );
    $stmt->bindParam(':user', $username, PDO::PARAM_STR);
    $stmt->bindParam(':pass', $password, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch();
}

/* Obtener productos con stock > 0 */
function obtenerProductosConStock($conexion)
{
    $stmt = $conexion->prepare(
        "SELECT productCode, productName, buyPrice 
         FROM Products 
         WHERE quantityInStock > 0"
    );
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* Obtener siguiente orderNumber */
function obtenerSiguienteOrderNumber($conexion)
{
    $stmt = $conexion->prepare("SELECT MAX(orderNumber) AS maxOrder FROM orders");
    $stmt->execute();
    $max = $stmt->fetchColumn();
    return $max + 1;
}

/* Calcular total del pedido según cantidad y precio real */
function calcularTotal($conexion, $pedido)
{
    $total = 0;
    $stmt = $conexion->prepare("SELECT buyPrice FROM Products WHERE productCode = :code");

    foreach ($pedido as $productCode => $cantidad) {
        if ($cantidad <= 0) continue;

        $stmt->bindParam(':code', $productCode, PDO::PARAM_STR);
        $stmt->execute();
        $precio = $stmt->fetchColumn();

        $total += $precio * $cantidad;
    }

    return $total;
}

/* Valida el formato del número de cheque (AA99999) */
function validarCheckNumber($checkNumber)
{
    return preg_match('/^[A-Z]{2}[0-9]{5}$/', $checkNumber);
}

/* Realizar pedido completo usando bindParam */
function realizarPedido($conexion, $pedido, $cliente, $checkNumber)
{
    try {
        $conexion->beginTransaction();

        // 1. Nuevo orderNumber
        $orderNumber = obtenerSiguienteOrderNumber($conexion);

        // 2. Insertar pedido
        $stmt = $conexion->prepare(
            "INSERT INTO orders 
             (orderNumber, orderDate, requiredDate, shippedDate, status, comments, customerNumber)
             VALUES (:order, CURDATE(), CURDATE(), NULL, 'In Process', NULL, :cliente)"
        );
        $stmt->bindParam(':order', $orderNumber, PDO::PARAM_INT);
        $stmt->bindParam(':cliente', $cliente, PDO::PARAM_INT);
        $stmt->execute();

        $total = 0;
        $linea = 1;

        // Preparar statements fuera del bucle
        $stmtProducto = $conexion->prepare(
            "SELECT BuyPrice, quantityInStock 
             FROM Products 
             WHERE ProductCode = :code"
        );

        $stmtDetalle = $conexion->prepare(
            "INSERT INTO orderdetails
             (orderNumber, productCode, quantityOrdered, priceEach, orderLineNumber)
             VALUES (:order, :product, :qty, :price, :line)"
        );

        $stmtStock = $conexion->prepare(
            "UPDATE products
             SET quantityInStock = quantityInStock - :qty
             WHERE ProductCode = :code"
        );

        // 3. Detalles del pedido y actualizar stock
        foreach ($pedido as $productCode => $cantidad) {
            if ($cantidad <= 0) continue;

            $stmtProducto->bindParam(':code', $productCode, PDO::PARAM_STR);
            $stmtProducto->execute();
            $producto = $stmtProducto->fetch(PDO::FETCH_ASSOC);

            if ($cantidad > $producto['quantityInStock']) {
                throw new Exception("Stock insuficiente para $productCode");
            }

            $precio = $producto['BuyPrice'];
            $total += $precio * $cantidad;

            // Insertar detalle
            $stmtDetalle->bindParam(':order', $orderNumber, PDO::PARAM_INT);
            $stmtDetalle->bindParam(':product', $productCode, PDO::PARAM_STR);
            $stmtDetalle->bindParam(':qty', $cantidad, PDO::PARAM_INT);
            $stmtDetalle->bindParam(':price', $precio);
            $stmtDetalle->bindParam(':line', $linea, PDO::PARAM_INT);
            $stmtDetalle->execute();
            $linea++;

            // Actualizar stock
            $stmtStock->bindParam(':qty', $cantidad, PDO::PARAM_INT);
            $stmtStock->bindParam(':code', $productCode, PDO::PARAM_STR);
            $stmtStock->execute();
        }

        // 4. Registrar pago
        $stmtPago = $conexion->prepare(
            "INSERT INTO payments
             (customerNumber, checkNumber, paymentDate, amount)
             VALUES (:cliente, :check, CURDATE(), :total)"
        );
        $stmtPago->bindParam(':cliente', $cliente, PDO::PARAM_INT);
        $stmtPago->bindParam(':check', $checkNumber, PDO::PARAM_STR);
        $stmtPago->bindParam(':total', $total);
        $stmtPago->execute();

        $conexion->commit();
        return true;

    } catch (Exception $e) {
        $conexion->rollBack();
        throw $e;
    }
}
?>