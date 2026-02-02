<?php
require_once("funciones.php");

if (!isset($_COOKIE['NOMBRE'])) {
    header("Location: pe_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include("header.php"); ?>

    <div class="wp-container">
        <h1>Panel de Inicio</h1>
        <p>Bienvenido al sistema de pedidos.</p>

        <fieldset>
            <legend>Opciones Disponibles</legend>
            <ul>
                <li><a class="wp-link" href="pe_altaped.php">Nuevo Pedido</a></li>
                <li><a class="wp-link" href="pe_consped.php">Consulta de Pedidos</a></li>
                <li><a class="wp-link" href="pe_consprodstock.php">Consulta de Stock de Productos</a></li>
                <li><a class="wp-link" href="pe_constock.php">Consulta de Stock de linea de producto</a></li>
                <li><a class="wp-link" href="pe_topprod.php">Consulta de productos vendidos entre fechas</a></li>
                <li><a class="wp-link" href="pe_conspago.php">Consulta de pagos de un cliente entre fechas</a></li>
            </ul>
        </fieldset>
    </div>
</body>
</html>
