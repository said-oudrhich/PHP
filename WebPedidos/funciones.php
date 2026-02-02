<?php
/* Conexión a BD pedidos - PDO */
function conectarBD()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
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

/* Renderizar mensaje en HTML seguro */
function renderMessage($mensaje, $tipo = 'error'){
    if (!$mensaje) return '';
    $class = 'alert-info';
    if ($tipo === 'error') $class = 'alert-error';
    if ($tipo === 'success') $class = 'alert-success';
    // permitir que el mensaje ya venga seguro, pero escapar por defecto
    $texto = htmlspecialchars($mensaje);
    return "<div class='alert $class'>" . $texto . "</div>";
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
         FROM products 
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
    $stmt = $conexion->prepare("SELECT buyPrice FROM products WHERE productCode = :code");

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
        "SELECT buyPrice, quantityInStock
         FROM products
         WHERE productCode = :code"
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
            WHERE productCode = :code"
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

/* Verificar si un número de cheque ya existe para un cliente */
function verificarCheckNumberUnico($conexion, $cliente, $checkNumber)
{
    $stmt = $conexion->prepare(
        "SELECT COUNT(*) FROM payments 
         WHERE customerNumber = :cliente 
         AND checkNumber = :check"
    );
    $stmt->bindParam(':cliente', $cliente, PDO::PARAM_INT);
    $stmt->bindParam(':check', $checkNumber, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchColumn() == 0; // Retorna true si es único
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

            $precio = $producto['buyPrice'];
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

/*********************************************************************************************************/

function desplegarProductLine($conexion){
    $stmt = $conexion->prepare(
        "SELECT productLine FROM productlines"
    );
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*********************************************************************************************************/

function stockProductLine($conexion, $productLine){
    $stmt = $conexion->prepare(
        "SELECT SUM(p.quantityInStock) AS stock
         FROM products p
         WHERE p.productLine = :productLine"
    );
    $stmt->bindParam(':productLine', $productLine, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/*********************************************************************************************************/

function productosVendidoEntreFechas($conexion, $fecha_inicio, $fecha_fin){
    $stmt = $conexion->prepare("
        SELECT od.productCode, od.quantityOrdered
        FROM orderdetails od, orders o
        WHERE od.orderNumber = o.orderNumber
        AND o.orderDate BETWEEN :fecha_inicio AND :fecha_fin;"
    );
    $stmt->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
    $stmt->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*********************************************************************************************************/

function pagosCliente($conexion,$cliente,$fecha_inicio,$fecha_fin){
    $stmt = $conexion->prepare("
        SELECT p.checkNumber,p.paymentDate,p.amount
        FROM payments p
        WHERE p.customerNumber = :cliente
        AND p.paymentDate BETWEEN :fecha_inicio AND :fecha_fin"
    );
    $stmt->bindParam(':cliente', $cliente, PDO::PARAM_INT);
    $stmt->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
    $stmt->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*********************************************************************************************************/

/* Obtener detalles del carrito con información completa */
function obtenerDetallesCarrito($conexion, $carrito){
    $carritoDetalles = [];
    $totalCarrito = 0;
    
    foreach ($carrito as $code => $qty) {
        $producto = obtenerProducto($conexion, $code);
        $precio = $producto['buyPrice'];
        $subtotal = $precio * $qty;
        $totalCarrito += $subtotal;
        
        // Obtener nombre del producto
        $stmt = $conexion->prepare("SELECT productName FROM products WHERE productCode = :code");
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->execute();
        $nombreProducto = $stmt->fetchColumn();
        
        $carritoDetalles[] = [
            'codigo' => $code,
            'nombre' => $nombreProducto,
            'cantidad' => $qty,
            'precio' => $precio,
            'subtotal' => $subtotal
        ];
    }
    
    return [
        'items' => $carritoDetalles,
        'total' => $totalCarrito
    ];
}

/*********************************************************************************************************/

/* Calcular total de un array de items con campo 'amount' */
function calcularTotalItems($items, $campoMonto = 'amount') {
    $total = 0;
    foreach ($items as $item) {
        if (isset($item[$campoMonto])) {
            $total += $item[$campoMonto];
        }
    }
    return $total;
}

/*********************************************************************************************************/

/* Renderizar un select/dropdown con opciones */
function renderSelectClientes($conexion, $clienteSeleccionado = null, $idSelect = 'cliente', $nombreSelect = 'cliente') {
    $clientes = deplegableCustomerNumber($conexion);
    $html = "<select name=\"$nombreSelect\" id=\"$idSelect\" required>\n";
    $html .= "    <option value=\"\">-- Seleccione --</option>\n";
    
    foreach ($clientes as $c) {
        $selected = ($clienteSeleccionado == $c['customerNumber']) ? 'selected' : '';
        $html .= "    <option value=\"" . htmlspecialchars($c['customerNumber']) . "\" $selected>" . htmlspecialchars($c['customerNumber']) . "</option>\n";
    }
    
    $html .= "</select>\n";
    return $html;
}