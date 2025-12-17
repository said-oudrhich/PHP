<?php
session_start();

if (!isset($_SESSION['NIF'])) {
    header("Location: ../Portal/comlogincli.php");
    exit();
}

require_once("../conexion.php");
require_once("../funciones.php");

$conn = conectarBD();
$productos = desplegableProducto($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['agregar'])) {
        $_SESSION['cesta'][] = [
            'nombre' => limpiar($_POST['producto']),
            'cantidad' => limpiar($_POST['cantidad'])
        ];
    }

    if (isset($_POST['limpiar'])) {
        unset($_SESSION['cesta']);
    }
}
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Compra</title>
</head>

<body>
    <h1>Agregar Producto a la Cesta</h1>
    <form method="post">
        <select name="producto">
            <?php foreach ($productos as $p): ?>
                <option value="<?php echo $p['NOMBRE']; ?>">
                    <?php echo $p['NOMBRE']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="number" name="cantidad" value="1" min="1">

        <input type="submit" name="agregar" value="Agregar">
        <input type="submit" name="limpiar" value="Limpiar">
    </form>

    <h2>Cesta</h2>
    <pre><?php var_dump($_SESSION['cesta'] ?? []); ?></pre>

    <h2>SESSION</h2>
    <pre><?php var_dump($_SESSION); ?></pre>

    <h2>COOKIES</h2>
    <pre><?php var_dump($_COOKIE); ?></pre>

</body>

</html>

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
<div class="logout-btn"><a href="../Portal/comlogout.php">Cerrar sesión</a></div>