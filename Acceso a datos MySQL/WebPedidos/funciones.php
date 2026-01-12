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


function verificarCliente($conexion, $username, $password)
{
    $stmt = $conexion->prepare("SELECT customerNumber FROM customers WHERE customerNumber = :user AND contactLastName = :pass");
    $stmt->execute([':user' => $username, ':pass' => $password]);
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
    $stmt = $conexion->query("SELECT MAX(orderNumber) FROM orders");
    return $stmt->fetchColumn() + 1;
}

/* Calcular total del pedido según cantidad y precio real */
function calcularTotal($conexion, $pedido)
{
    $total = 0;
    foreach ($pedido as $productCode => $cantidad) {
        if ($cantidad <= 0) continue;

        $stmt = $conexion->prepare(
            "SELECT buyPrice FROM Products WHERE productCode = :code"
        );
        $stmt->execute([':code' => $productCode]);
        $precio = $stmt->fetchColumn();

        $total += $precio * $cantidad;
    }
    return $total;
}
/* En funciones.php - Añadir esta función */

/**
 * Valida el formato del número de cheque (AA99999)
 */
function validarCheckNumber($checkNumber)
{
    return preg_match('/^[A-Z]{2}[0-9]{5}$/', $checkNumber);
}

/* Realizar pedido completo */
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
        $stmt->execute([
            ':order'   => $orderNumber,
            ':cliente' => $cliente
        ]);

        $total = 0;
        $linea = 1;

        // 3. Detalles del pedido y actualizar stock
        foreach ($pedido as $productCode => $cantidad) {
            if ($cantidad <= 0) continue;

            $stmt = $conexion->prepare(
                "SELECT BuyPrice, quantityInStock 
                 FROM Products 
                 WHERE ProductCode = :code"
            );
            $stmt->execute([':code' => $productCode]);
            $producto = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cantidad > $producto['quantityInStock']) {
                throw new Exception("Stock insuficiente para $productCode");
            }

            $precio = $producto['BuyPrice'];
            $total += $precio * $cantidad;

            // Insertar detalle
            $stmt = $conexion->prepare(
                "INSERT INTO orderdetails
                 (orderNumber, productCode, quantityOrdered, priceEach, orderLineNumber)
                 VALUES (:order, :product, :qty, :price, :line)"
            );
            $stmt->execute([
                ':order'   => $orderNumber,
                ':product' => $productCode,
                ':qty'     => $cantidad,
                ':price'   => $precio,
                ':line'    => $linea++
            ]);

            // Actualizar stock
            $stmt = $conexion->prepare(
                "UPDATE products
                 SET quantityInStock = quantityInStock - :qty
                 WHERE ProductCode = :code"
            );
            $stmt->execute([
                ':qty'  => $cantidad,
                ':code' => $productCode
            ]);
        }

        // 4. Registrar pago
        $stmt = $conexion->prepare(
            "INSERT INTO payments
             (customerNumber, checkNumber, paymentDate, amount)
             VALUES (:cliente, :check, CURDATE(), :total)"
        );
        $stmt->execute([
            ':cliente' => $cliente,
            ':check'   => $checkNumber,
            ':total'   => $total
        ]);

        $conexion->commit();
        return true;
    } catch (Exception $e) {
        $conexion->rollBack();
        throw $e;
    }
}
