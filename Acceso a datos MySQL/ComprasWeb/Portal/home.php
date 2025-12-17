<?php
session_start();

if (!isset($_SESSION['NIF'])) {
    header("Location: comlogincli.php");
    exit();
}

$NIF = $_SESSION['NIF'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Portal de Clientes</title>
</head>

<body>
    <h1>Bienvenido al Portal de Clientes</h1>
    <?php require_once __DIR__ . '/../header.php'; ?>

    <h2>Opciones disponibles:</h2>
    <ul>
        <li><a href="../Gestion_Clientes/compro.php">Compra de productos</a></li>
        <li><a href="../Gestion_General/comconscom.php">Consulta de compras</a></li>
        <li><a href="../Cookies/comcesta.php">Coockies de cesta de productos</a></li>
        <li><a href="comlogout.php">Cerrar sesión</a></li>
    </ul>

</body>

</html>