<?php
require_once("funciones.php");
$mensaje = "";

try {
    $conexion = conectarBD();
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = limpiar($_POST["username"]);
        $password = limpiar($_POST["password"]);

        if (verificarCliente($conexion, $username, $password)) {
            setcookie('NOMBRE', $username, time() + 86400, '/');
            header("Location: pe_inicio.php");
        } else {
            // Login fallido
            $mensaje = "Nombre de usuario o contraseña incorrectos.";
        }
    }
} catch (PDOException $e) {
    $mensaje = "Error en la base de datos: " . $e->getMessage();
} catch (Exception $e) {
    $mensaje = "Error en el servidor: " . $e->getMessage();
} finally {
    $conexion = null;
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
    <h1>Login de Clientes</h1>
    <form action="pe_login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Iniciar Sesión">
        <?php if ($mensaje): ?>
            <p><?= $mensaje ?></p>
        <?php endif; ?>
    </form>

</body>

</html>