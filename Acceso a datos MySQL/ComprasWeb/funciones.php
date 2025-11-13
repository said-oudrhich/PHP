<?php
function limpiar($dato)
{
    return htmlspecialchars(stripslashes(trim($dato)));
}

/*********************************************************************************************************************************************/
/*

*/
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
/*
Alta de Productos (comaltapro.php): dar de alta productos. Para seleccionar la categoría del
producto, se utilizará una lista de valores con los nombres de las categorías. El id_producto
será un campo con el formato Pxxxx donde xxxx será un número secuencial que comienza en
1 completándose con 0 hasta completar el formato (este campo será calculado desde PHP).
*/

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

    return "P-" . str_pad($num, 4, "0", STR_PAD_LEFT);
}

function insertarProducto($conn, $id, $nombre)
{
    $sql = "INSERT INTO categoria (id_producto, nombre) VALUES (:id, :nombre)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ":id" => $id,
        ":nombre" => $nombre
    ]);
}
