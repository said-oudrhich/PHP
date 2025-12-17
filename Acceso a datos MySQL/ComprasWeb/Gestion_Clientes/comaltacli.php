<?php
require_once("../funciones.php");
require_once("../conexion.php");
session_start();

if (!isset($_SESSION['NIF'])) {
    header("Location: ../Portal/comlogincli.php");
    exit();
}

$mensaje = "";

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

        if (!comprobarNif($nif)) {
            $mensaje = "El NIF $nif no es válido.";
        } elseif (nifExiste($conexion, $nif)) {
            $mensaje = "El NIF $nif ya está registrado.";
        } else {
            $clave = generarClave($apellido);
            registrarCliente($conexion, $nif, $nombre, $apellido, $cp, $direccion, $ciudad, $clave);
            $mensaje = "Cliente registrado correctamente. Su usuario es '$nombre' y su clave es '$clave'.";
        }
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
    <title>Alta de Clientes</title>
</head>

<body>
    <h2>Alta de Clientes</h2>
    <form method="POST" action="comaltacli.php">
        <label>NIF:</label><br>
        <input type="text" name="nif" required><br><br>
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>
        <label>Apellido:</label><br>
        <input type="text" name="apellido" required><br><br>
        <label>CP:</label><br>
        <input type="text" name="cp" required><br><br>
        <label>Dirección:</label><br>
        <input type="text" name="direccion" required><br><br>
        <label>Ciudad:</label><br>
        <input type="text" name="ciudad" required><br><br>
        <input type="submit" value="Alta">
    </form>

    <?php
    if ($mensaje) {
        echo "<p>" . htmlspecialchars($mensaje) . "</p>";
    }
    ?>

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

</body>

</html>