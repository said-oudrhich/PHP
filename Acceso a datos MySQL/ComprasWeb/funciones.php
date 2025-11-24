<?php

function limpiar($dato)
{
    return htmlspecialchars(stripslashes(trim($dato)));
}

/*********************************************************************************************************************************************/

function generarNuevoId_categoria($conn)
{
    $sql = "SELECT MAX(id_categoria) FROM categoria";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $ultimo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($ultimo) {
        $num = substr($ultimo["id_categoria"], 2) + 1;
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
    $sql = "SELECT MAX(id_producto) FROM producto";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $ultimo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($ultimo) {
        $num = substr($ultimo["id_producto"], 1) + 1;
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
    $sql = "SELECT MAX(num_almacen) FROM almacen";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $ultimo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($ultimo) {
        return $ultimo["num_almacen"] + 1;
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
