<?php

// Limpia los datos de entrada para evitar inyecciones y otros problemas
function limpiar($dato)
{
    return htmlspecialchars(stripslashes(trim($dato)));
}

/*********************************************************************************************************************************************/

// Genera un nuevo ID para una categoría
function generarNuevoId_categoria($conn)
{
    $sql = "SELECT id_categoria FROM categoria ORDER BY id_categoria DESC LIMIT 1";
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

// Inserta una nueva categoría en la base de datos
function insertarCategoria($conn, $id, $nombre)
{
    $sql = "INSERT INTO categoria (id_categoria, nombre) VALUES (:id, :nombre)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ":id" => $id,
        ":nombre" => $nombre
    ]);
}

/*********************************************************************************************************************************************/

// Genera un nuevo ID para un producto
function generarNuevoId_producto($conn)
{
    $sql = "SELECT id_producto FROM producto ORDER BY id_producto DESC LIMIT 1";
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

// Inserta un nuevo producto en la base de datos
function insertarProducto($conn, $id, $nombre, $precio, $id_categoria)
{
    $sql = "INSERT INTO producto (id_producto, nombre, precio, id_categoria) VALUES (:id, :nombre, :precio, :id_categoria)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ":id" => $id,
        ":nombre" => $nombre,
        ":precio" => $precio,
        ":id_categoria" => $id_categoria
    ]);
}


// Obtiene todas las categorías para el desplegable
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
    $sql = "INSERT INTO almacen (num_almacen, localidad) VALUES (:num_almacen, :localidad)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ":num_almacen" => $num_almacen,
        ":localidad" => $localidad
    ]);
}

// Genera un nuevo ID para un almacén
function generarNuevoId_almacen($conn)
{
    $sql = "SELECT num_almacen FROM almacen ORDER BY num_almacen DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $ultimo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($ultimo) {
        return $ultimo["num_almacen"] + 1;
    } else {
        return 1; // si no hay registros
    }
}
