<?php
require_once("funciones.php");
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = limpiar($_POST["username"]);
    $password = limpiar($_POST["password"]);

    try{
        $conexion = conectarBD();
        
        if (verificarCliente($conexion, $username, $password)) {
            setcookie('NOMBRE', $username, time() + 86400, '/');
            header("Location: pe_inicio.php");
        } else {
            $mensaje = "Usuario o contraseña incorrectos.";
        }
    } catch (Exception $e) {
        $mensaje = "Error: " . $e->getMessage();
    }finally{
        $conexion = null;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Login de Clientes</h1>
    <form method="post">
        <label>Username:</label>
        <input type="text" name="username" required><br><br>
        <label>Password:</label>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Entrar">
    </form>
    <?php if ($mensaje): ?>
        <p style="color:red;"><?= $mensaje ?></p>
    <?php endif; ?>
</body>
</html>