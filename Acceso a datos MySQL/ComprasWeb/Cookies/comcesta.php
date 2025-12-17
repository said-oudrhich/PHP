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
        $ID_PRODUCTO = limpiar($_POST['producto']);
        $UNIDADES = (int)limpiar($_POST['cantidad']);

        // Inicializar la cesta si no existe
        if (!isset($_SESSION['cesta'])) {
            $_SESSION['cesta'] = [];
        }

        // Incrementar si ya existe, o agregar nuevo
        if (isset($_SESSION['cesta'][$ID_PRODUCTO])) {
            $_SESSION['cesta'][$ID_PRODUCTO]['UNIDADES'] += $UNIDADES;
        } else {
            $_SESSION['cesta'][$ID_PRODUCTO] = [
                'ID_PRODUCTO' => $ID_PRODUCTO,
                'UNIDADES' => $UNIDADES
            ];
        }
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
    <?php require_once __DIR__ . '/../header.php'; ?>
    <h1>Agregar Producto a la Cesta</h1>
    <form method="post">
        <select name="producto">
            <?php foreach ($productos as $p): ?>
                <option value="<?php echo $p['ID_PRODUCTO']; ?>">
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