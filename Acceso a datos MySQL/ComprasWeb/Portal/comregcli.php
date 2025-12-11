<?php
require_once("../funciones.php");
require_once("../conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nif = limpiar($_POST["nif"]);
    $nombre = limpiar($_POST["nombre"]);
    $apellido = limpiar($_POST["apellido"]);
    $cp = limpiar($_POST["cp"]);
    $direccion = limpiar($_POST["direccion"]);
    $ciudad = limpiar($_POST["ciudad"]);

    try {
        $conexion = conectarBD();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $clave = generarClave($apellido);
        registrarCliente($conexion, $nif, $nombre, $apellido, $cp, $direccion, $ciudad, $clave);

        $mensaje = "Cliente registrado correctamente. Su usuario es '$nombre' y su clave es '$clave'.";
    } catch (PDOException $e) {
        $mensaje = mostrarError($e, "Registro de Cliente");
    } finally {
        $conexion = null;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Clientes</title>
</head>

<body>
    <h2>Registro de Nuevo Cliente</h2>

    <form action="comregcli.php" method="POST">
        <label for="nif">NIF:</label>
        <input type="text" id="nif" name="nif" required><br><br>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" required><br><br>

        <label for="cp">Código Postal:</label>
        <input type="text" id="cp" name="cp" required><br><br>

        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required><br><br>

        <label for="ciudad">Ciudad:</label>
        <input type="text" id="ciudad" name="ciudad" required><br><br>

        <input type="submit" value="Registrar">
    </form>

    <?php
    if (isset($mensaje)) {
        echo "<p>$mensaje</p>";
    }
    ?>

</body>

</html>