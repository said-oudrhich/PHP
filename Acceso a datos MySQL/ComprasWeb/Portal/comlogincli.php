<?php
require_once("../funciones.php");
require_once("../conexion.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = limpiar($_POST["nombre"]);
    $clave = limpiar($_POST["clave"]);

    try {
        $conexion = conectarBD();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (verificarCliente($conexion, $nombre, $clave)) {
            $_SESSION['usuario'] = $nombre;
            $mensaje = "Login exitoso. Bienvenido, $nombre.";
        } else {
            $mensaje = "Nombre de usuario o clave incorrectos.";
        }
    } catch (PDOException $e) {
        $mensaje = mostrarError($e, "Login de Cliente");
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
    <title>Login de Clientes</title>
</head>

<body>
    <h2>Login de Clientes</h2>

    <form action="comlogincli.php" method="POST">
        <label for="nombre">Nombre de Usuario:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="clave">Clave:</label>
        <input type="password" id="clave" name="clave" required><br><br>

        <input type="submit" value="Iniciar Sesión">
    </form>

    <?php
    if (isset($mensaje)) {
        echo "<p>$mensaje</p>";
    }
    ?>
</body>

</html>