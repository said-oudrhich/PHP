<?php

function limpiar($dato)
{
    return htmlspecialchars(stripslashes(trim($dato)));
}

/*********************************************************************************************************************************************/

function generarNuevoId_categoria($conn)
{
    $sql = "SELECT MAX(id_categoria) AS max_cat FROM categoria";
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
    $sql = "INSERT INTO categoria (id_categoria, nombre) VALUES (:id, :nombre)";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":nombre", $nombre);

    $stmt->execute();
}

/*********************************************************************************************************************************************/

function generarNuevoId_producto($conn)
{
    $sql = "SELECT MAX(id_producto) AS max_prod FROM producto";
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
    $sql = "INSERT INTO producto (id_producto, nombre, precio, id_categoria)
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
    $sql = "SELECT id_categoria, nombre FROM categoria";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*********************************************************************************************************************************************/

function insertarAlmacen($conn, $num_almacen, $localidad)
{
    $sql = "INSERT INTO almacen (num_almacen, localidad)
            VALUES (:num_almacen, :localidad)";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(":num_almacen", $num_almacen);
    $stmt->bindParam(":localidad", $localidad);

    $stmt->execute();
}

function generarNuevoId_almacen($conn)
{
    $sql = "SELECT MAX(num_almacen) AS max_id FROM almacen";
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
    $sql = "SELECT id_producto, nombre FROM producto";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function desplegableAlmacen($conn)
{
    $sql = "SELECT num_almacen, localidad FROM almacen";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function insertarAprovisionamiento($conn, $num_almacen, $id_producto, $cantidad)
{
    $sql = "INSERT INTO almacena (num_almacen, id_producto, cantidad)
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
    $clave = strrev($apellido);

    $sql = "INSERT INTO cliente (nif, nombre, apellido, cp, direccion, ciudad, clave) 
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

function verificarCliente($conn, $nombre, $clave)
{
    $sql = "SELECT * FROM cliente WHERE nombre = :nombre AND clave = :clave";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":clave", $clave);

    $stmt->execute();

    return $stmt->rowCount() > 0;
}

/*********************************************************************************************************************************************/

function stockProductoEnAlmacen($conn, $id_producto)
{
    $sql = "SELECT a.num_almacen, a.localidad, a2.cantidad
                FROM almacen a, almacena a2  
                where a.num_almacen = a2.num_almacen AND a2.id_producto = :id_producto";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id_producto", $id_producto);
    $stmt->execute();
    $stock = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $stock;
}

/*********************************************************************************************************************************************/

function obtenerProductosEnAlmacen($conn, $num_almacen)
{
    $sql = "SELECT p.id_producto, p.nombre, p.precio, a2.cantidad
            FROM producto p, almacena a2
            WHERE p.id_producto = a2.id_producto AND a2.num_almacen = :num_almacen";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":num_almacen", $num_almacen);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
