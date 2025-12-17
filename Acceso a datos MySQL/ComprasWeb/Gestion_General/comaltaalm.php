<?php
session_start();

if (!isset($_SESSION['NIF'])) {
    header("Location: ../Portal/comlogincli.php");
    exit();
}

require_once("../conexion.php");
require_once("../funciones.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $localidad = limpiar($_POST["localidad"]);

    try {
        $conn = conectarBD();

        $num_almacen = generarNuevoId_almacen($conn);
        insertarAlmacen($conn, $num_almacen, $localidad);

        $mensaje = "Almacén '$localidad' creado con ID $num_almacen.";
    } catch (PDOException $e) {
        $mensaje = mostrarError($e, "Alta de Almacén");
    } finally {
        $conn = null;
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Alta de Almacenes</title>
</head>

<body>
    <?php require_once __DIR__ . '/../header.php'; ?>
    <h2>Alta de Almacenes</h2>
    <form method="POST" action="comaltaalm.php">
        <label>Nombre del almacén:</label><br>
        <input type="text" name="localidad" required>
        <br><br>
        <input type="submit" value="Dar de alta">
    </form>

    <?php if ($mensaje): ?>
        <p><?= $mensaje ?></p>
    <?php endif; ?>

</body>

</html>