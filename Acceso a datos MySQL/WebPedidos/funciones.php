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

/*********************************************************************************************************/
/* Limpiar datos de entrada */
function limpiar($dato)
{
    return htmlspecialchars(stripslashes(trim($dato)));
}

/*********************************************************************************************************/
/* Verificar cliente */
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

/*********************************************************************************************************/
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

/*********************************************************************************************************/
/* Obtener siguiente orderNumber */
function obtenerSiguienteOrderNumber($conexion)
{
    $stmt = $conexion->prepare("SELECT MAX(orderNumber) AS maxOrder FROM orders");
    $stmt->execute();
    $max = $stmt->fetchColumn();
    return $max + 1;
}

/*********************************************************************************************************/
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

/*********************************************************************************************************/
/* Valida el formato del número de cheque (AA99999) */
function validarCheckNumber($checkNumber)
{
    return preg_match('/^[A-Z]{2}[0-9]{5}$/', $checkNumber);
}


/*********************************************************************************************************/
/* 1. Insertar pedido */
function insertarPedido($conexion, $orderNumber, $cliente)
{
    $stmt = $conexion->prepare(
        "INSERT INTO orders 
         (orderNumber, orderDate, requiredDate, shippedDate, status, comments, customerNumber)
         VALUES (:order, CURDATE(), CURDATE(), NULL, 'In Process', NULL, :cliente)"
    );
    $stmt->bindParam(':order', $orderNumber, PDO::PARAM_INT);
    $stmt->bindParam(':cliente', $cliente, PDO::PARAM_INT);
    $stmt->execute();
}

/*********************************************************************************************************/
/* 2. Obtener datos del producto */
function obtenerProducto($conexion, $productCode)
{
    $stmt = $conexion->prepare(
        "SELECT BuyPrice, quantityInStock
         FROM products
         WHERE ProductCode = :code"
    );
    $stmt->bindParam(':code', $productCode, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/*********************************************************************************************************/
/* 3. Insertar detalle del pedido */
function insertarDetallePedido($conexion, $orderNumber, $productCode, $cantidad, $precio, $linea)
{
    $stmt = $conexion->prepare(
        "INSERT INTO orderdetails
         (orderNumber, productCode, quantityOrdered, priceEach, orderLineNumber)
         VALUES (:order, :product, :qty, :price, :line)"
    );
    $stmt->bindParam(':order', $orderNumber, PDO::PARAM_INT);
    $stmt->bindParam(':product', $productCode, PDO::PARAM_STR);
    $stmt->bindParam(':qty', $cantidad, PDO::PARAM_INT);
    $stmt->bindParam(':price', $precio);
    $stmt->bindParam(':line', $linea, PDO::PARAM_INT);
    $stmt->execute();
}

/*********************************************************************************************************/
/* 4. Actualizar stock */
function actualizarStock($conexion, $productCode, $cantidad)
{
    $stmt = $conexion->prepare(
        "UPDATE products
         SET quantityInStock = quantityInStock - :qty
         WHERE ProductCode = :code"
    );
    $stmt->bindParam(':qty', $cantidad, PDO::PARAM_INT);
    $stmt->bindParam(':code', $productCode, PDO::PARAM_STR);
    $stmt->execute();
}

/*********************************************************************************************************/
/* 5. Registrar pago */
function registrarPago($conexion, $cliente, $checkNumber, $total)
{
    $stmt = $conexion->prepare(
        "INSERT INTO payments
         (customerNumber, checkNumber, paymentDate, amount)
         VALUES (:cliente, :check, CURDATE(), :total)"
    );
    $stmt->bindParam(':cliente', $cliente, PDO::PARAM_INT);
    $stmt->bindParam(':check', $checkNumber, PDO::PARAM_STR);
    $stmt->bindParam(':total', $total);
    $stmt->execute();
}

/*********************************************************************************************************/
/* 6. Pedido completo */
function realizarPedido($conexion, $pedido, $cliente, $checkNumber)
{
    try {
        $conexion->beginTransaction();

        $orderNumber = obtenerSiguienteOrderNumber($conexion);
        insertarPedido($conexion, $orderNumber, $cliente);

        $total = 0;
        $linea = 1;

        foreach ($pedido as $productCode => $cantidad) {
            if ($cantidad <= 0) continue;

            $producto = obtenerProducto($conexion, $productCode);

            if ($cantidad > $producto['quantityInStock']) {
                throw new Exception("Stock insuficiente para $productCode");
            }

            $precio = $producto['BuyPrice'];
            $total += $precio * $cantidad;

            insertarDetallePedido($conexion,$orderNumber,$productCode,$cantidad,$precio,$linea);

            actualizarStock($conexion, $productCode, $cantidad);
            $linea++;
        }

        registrarPago($conexion, $cliente, $checkNumber, $total);

        $conexion->commit();
        return true;

    } catch (Exception $e) {
        $conexion->rollBack();
        throw $e;
    }
}

/*********************************************************************************************************/
/* 7. Deplegable customerNumber */
function deplegableCustomerNumber($conexion) {
    $stmt = $conexion->prepare("SELECT customerNumber FROM customers");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* 8. Obtener detalles de un pedido con información del producto */
function detallesPedido($conexion, $orderNumber){
    $stmt = $conexion->prepare(
        "SELECT od.orderLineNumber, p.productName, od.quantityOrdered, od.priceEach
         FROM orderdetails od, products p
         WHERE od.productCode = p.productCode AND od.orderNumber = :orderNumber 
         ORDER BY od.orderLineNumber"
    );
    $stmt->bindParam(':orderNumber', $orderNumber, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*********************************************************************************************************/
/* Devuelve los pedidos de un cliente */
function pedidosCliente($conexion, $customerNumber){
    $stmt = $conexion->prepare("SELECT orderNumber, orderDate, status FROM orders WHERE customerNumber = :customerNumber");
    $stmt->bindParam(':customerNumber', $customerNumber, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*********************************************************************************************************/

function productos($conexion){
    $stmt = $conexion->prepare("SELECT productName, productCode FROM products");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function stockProducto($conexion, $productCode){
    $stmt = $conexion->prepare(
        "SELECT quantityInStock
         FROM products
         WHERE productCode = :productCode"
    );
    $stmt->bindParam(':productCode', $productCode, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_COLUMN);
}