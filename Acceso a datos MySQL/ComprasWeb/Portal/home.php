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
    <p>NIF del usuario: <?php echo htmlspecialchars($NIF); ?></p>

    <h2>Opciones disponibles:</h2>
    <ul>
        <li><a href="../Gestion_Clientes/compro.php">Compra de productos</a></li>
        <li><a href="../Gestion_General/comconscom.php">Consulta de compras</a></li>
        <li><a href="../Cookies/comcesta.php">Coockies de cesta de productos</a></li>
        <li><a href="comlogout.php">Cerrar sesión</a></li>
    </ul>
    <!-- Botón fijo de cerrar sesión -->
    <style>
        .logout-btn {
            position: fixed;
            top: 10px;
            right: 10px;
        }

        .logout-btn a {
            display: inline-block;
            padding: 6px 10px;
            background: #c00;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
    </style>
    <div class="logout-btn"><a href="comlogout.php">Cerrar sesión</a></div>
</body>

</html>