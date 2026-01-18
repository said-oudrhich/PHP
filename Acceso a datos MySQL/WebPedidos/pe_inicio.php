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
    <title>Inicio</title>
</head>
<body>
    <?php include("header.php"); ?>
    
    <h1>Panel de Inicio</h1>
    <p>Bienvenido al sistema de pedidos.</p>
    
    <ul>
        <li><a href="pe_altaped.php">Nuevo Pedido</a></li>
        <li><a href="pe_consped.php">Consulta de Pedidos</a></li>
        <li><a href="pe_consprodstock.php">Consulta de Stock de Productos</a></li>
        <li><a href="pe_constock.php">Consulta de Stock de linea de producto</a></li>
        <li><a href="pe_topprod.php">Consulta de productos vendidos entre fechas</a></li>
        <li><a href="pe_conspago.php">Consulta de pagos de un cliente entre fechas</a></li>
    </ul>
</body>
</html>
