<?php

function limpiar($dato)
{
    return htmlspecialchars(stripslashes(trim($dato)));
}

/*********************************************************************************************************************************************/

function generarNuevoId_categoria($conn)
{
    $sql = "SELECT MAX(ID_CATEGORIA) AS max_cat FROM CATEGORIA";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $ultimo = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si la tabla está vacía, max_cat será null
    if ($ultimo && $ultimo["max_cat"] !== null) {
        // Quitar el prefijo "C-" y convertir a número
        $num = intval(substr($ultimo["max_cat"], 2)) + 1;
    } else {
        $num = 1;
    }

    return "C-" . str_pad($num, 3, "0", STR_PAD_LEFT);
}


function insertarCategoria($conn, $id, $nombre)
{
    $sql = "INSERT INTO CATEGORIA (ID_CATEGORIA, NOMBRE) VALUES (:id, :nombre)";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":nombre", $nombre);

    $stmt->execute();
}

/*********************************************************************************************************************************************/

function generarNuevoId_producto($conn)
{
    $sql = "SELECT MAX(ID_PRODUCTO) AS max_prod FROM PRODUCTO";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $ultimo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($ultimo && $ultimo["max_prod"] !== null) {
        // Quitar la "P" inicial y convertir el resto a número
        $num = intval(substr($ultimo["max_prod"], 1)) + 1;
    } else {
        $num = 1;
    }

    return "P" . str_pad($num, 4, "0", STR_PAD_LEFT);
}


function insertarProducto($conn, $id, $nombre, $precio, $id_categoria)
{
    $sql = "INSERT INTO PRODUCTO (ID_PRODUCTO, NOMBRE, PRECIO, ID_CATEGORIA)
            VALUES (:id, :nombre, :precio, :id_categoria)";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":precio", $precio);
    $stmt->bindParam(":id_categoria", $id_categoria);

    $stmt->execute();
}

/*********************************************************************************************************************************************/

function desplegableCategoria($conn)
{
    $sql = "SELECT ID_CATEGORIA, NOMBRE FROM CATEGORIA";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*********************************************************************************************************************************************/

function insertarAlmacen($conn, $num_almacen, $localidad)
{
    $sql = "INSERT INTO ALMACEN (NUM_ALMACEN, LOCALIDAD)
            VALUES (:num_almacen, :localidad)";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(":num_almacen", $num_almacen);
    $stmt->bindParam(":localidad", $localidad);

    $stmt->execute();
}

function generarNuevoId_almacen($conn)
{
    $sql = "SELECT MAX(NUM_ALMACEN) AS max_id FROM ALMACEN";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $ultimo = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si la tabla está vacía, max_id será null
    if ($ultimo && $ultimo["max_id"] !== null) {
        return $ultimo["max_id"] + 1;
    } else {
        return 1;
    }
}

/*********************************************************************************************************************************************/

function desplegableProducto($conn)
{
    $sql = "SELECT ID_PRODUCTO, NOMBRE FROM PRODUCTO";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function desplegableAlmacen($conn)
{
    $sql = "SELECT NUM_ALMACEN, LOCALIDAD FROM ALMACEN";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function insertarAprovisionamiento($conn, $num_almacen, $id_producto, $cantidad)
{
    $sql = "INSERT INTO ALMACENA (NUM_ALMACEN, ID_PRODUCTO, CANTIDAD)
            VALUES (:num_almacen, :id_producto, :cantidad)";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(":num_almacen", $num_almacen);
    $stmt->bindParam(":id_producto", $id_producto);
    $stmt->bindParam(":cantidad", $cantidad);

    $stmt->execute();
}


/*********************************************************************************************************************************************/

function registrarCliente($conn, $nif, $nombre, $apellido, $cp, $direccion, $ciudad, $clave)
{

    $sql = "INSERT INTO CLIENTE (NIF, NOMBRE, APELLIDO, CP, DIRECCION, CIUDAD, CLAVE) 
            VALUES (:nif, :nombre, :apellido, :cp, :direccion, :ciudad, :clave)";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(":nif", $nif);
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":apellido", $apellido);
    $stmt->bindParam(":cp", $cp);
    $stmt->bindParam(":direccion", $direccion);
    $stmt->bindParam(":ciudad", $ciudad);
    $stmt->bindParam(":clave", $clave);

    $stmt->execute();
}


function generarClave($apellido)
{
    return strrev($apellido);
}
function verificarCliente($conn, $nombre, $clave)
{
    $sql = "SELECT * FROM CLIENTE WHERE NOMBRE = :nombre AND CLAVE = :clave";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":clave", $clave);

    $stmt->execute();

    return $stmt->rowCount() > 0;
}

/*********************************************************************************************************************************************/

function stockProductoEnAlmacen($conn, $id_producto)
{
    $sql = "SELECT NUM_ALMACEN, CANTIDAD 
            FROM ALMACENA 
            WHERE ID_PRODUCTO = :id_producto AND CANTIDAD > 0
            ORDER BY CANTIDAD DESC 
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_producto', $id_producto);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


/*********************************************************************************************************************************************/

function obtenerProductosEnAlmacen($conn, $num_almacen)
{
    $sql = "SELECT p.ID_PRODUCTO, p.NOMBRE, p.PRECIO, a2.CANTIDAD
            FROM PRODUCTO p, ALMACENA a2
            WHERE p.ID_PRODUCTO = a2.ID_PRODUCTO AND a2.NUM_ALMACEN = :num_almacen";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":num_almacen", $num_almacen);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/*********************************************************************************************************************************************/

function desplegableClientes($conn)
{
    $sql = "SELECT NIF, NOMBRE FROM CLIENTE";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerComprasPorClienteYFecha($conn, $nif, $fecha_desde, $fecha_hasta)
{
    $sql = "SELECT p.NOMBRE
            FROM COMPRA c, PRODUCTO p
            WHERE c.ID_PRODUCTO = p.ID_PRODUCTO 
              AND c.NIF = :nif 
              AND c.FECHA_COMPRA BETWEEN :fecha_desde AND :fecha_hasta";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":nif", $nif);
    $stmt->bindParam(":fecha_desde", $fecha_desde);
    $stmt->bindParam(":fecha_hasta", $fecha_hasta);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*********************************************************************************************************************************************/
function comprobarNif($nif)
{
    // No puede estar vacío y debe tener 9 caracteres
    if (empty($nif) || strlen($nif) !== 9) {
        return false;
    }

    $numbers = substr($nif, 0, 8);
    $letter = substr($nif, 8, 1);

    // Los primeros 8 deben ser dígitos
    if (!ctype_digit($numbers)) {
        return false;
    }

    // La última debe ser letra (mayúscula o minúscula)
    if (!ctype_alpha($letter)) {
        return false;
    }

    return true;
}

function nifExiste($conexion, $nif)
{
    $sql = "SELECT COUNT(*) FROM CLIENTE WHERE NIF = :nif";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':nif' => $nif]);
    return $stmt->fetchColumn() > 0;
}

/*********************************************************************************************************************************************/

function insertarCompra($conexion, $nif, $id_producto, $fecha_compra, $unidades)
{
    $sql = "INSERT INTO COMPRA (NIF, ID_PRODUCTO, FECHA_COMPRA, UNIDADES) 
        VALUES (:nif, :id_producto, :fecha_compra, :unidades)";


    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':nif', $nif);
    $stmt->bindParam(':id_producto', $id_producto);
    $stmt->bindParam(':fecha_compra', $fecha_compra);
    $stmt->bindParam(':unidades', $unidades);
    $stmt->execute();
}


function reducirStock($conn, $num_almacen, $id_producto, $cantidad)
{
    $sql = "UPDATE ALMACENA 
            SET CANTIDAD = CANTIDAD - :cantidad 
            WHERE ID_PRODUCTO = :id_producto AND NUM_ALMACEN = :num_almacen";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
    $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_STR);
    $stmt->bindParam(':num_almacen', $num_almacen, PDO::PARAM_INT);

    $stmt->execute();
}
