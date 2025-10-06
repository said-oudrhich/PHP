<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORMULARIO DE DATOS</title>
</head>

<body>
    <h1>Datos Alumnos</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required>
        <br><br>
        <label for="apellido1">Apellido1:</label>
        <input type="text" name="apellido1">
        <br><br>
        <label for="apellido2">Apellido2:</label>
        <input type="text" name="apellido2">
        <br><br>
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br><br>
        <label for="sexo">Sexo:</label>
        <input type="radio" name="sexo" value="Hombre" required>Hombre
        <input type="radio" name="sexo" value="Mujer" required>Mujer
        <br><br>
        <input type="submit" value="Enviar">
        <input type="reset" value="Borrar">
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = limpiar($_POST['nombre']);
        $apellido1 = limpiar($_POST['apellido1']);
        $apellido2 = limpiar($_POST['apellido2']);
        $email = limpiar($_POST['email']);
        $sexo = limpiar($_POST['sexo']);

        echo "<br><table border='1' cellpadding='5'>";
        echo "<tr>
            <td>Nombre</td>
            <td>Apellidos</td>
            <td>Email</td>
            <td>Sexo</td>
        </tr>";
        echo "<tr>
            <td>{$nombre}</td>
            <td>{$apellido1} {$apellido2}</td>
            <td>{$email}</td>
            <td>" . ($sexo == "Hombre" ? "H" : "M") . "</td>
        </tr>";
        echo "</table>";
    }

    function limpiar($dato)
    {
        return htmlspecialchars(stripslashes(trim($dato)));
    }
    ?>
</body>

</html>