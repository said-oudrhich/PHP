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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wp-container">
        <h1>Login de Clientes</h1>

        <div class="alert alert-info">
            <strong>Nota para el profesor:</strong><br>
            El login utiliza datos de la tabla <code>customers</code>:<br>
            - <strong>Username:</strong> <code>customerNumber</code> (Ej: 103)<br>
            - <strong>Contraseña:</strong> <code>contactLastName</code> (Ej: Schmitt)<br>
            Prueba con: <strong>user=103</strong> | <strong>pass=Schmitt</strong>
        </div>

        <?php if (!empty($mensaje)) echo renderMessage($mensaje, 'error'); ?>

        <fieldset>
            <legend>Acceder al Sistema</legend>
            <form method="post">
                <div class="wp-form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="wp-form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button class="wp-button wp-button-primary wp-button-full" type="submit">Entrar</button>
            </form>
        </fieldset>
    </div>
</body>
</html>