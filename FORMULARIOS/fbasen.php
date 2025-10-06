<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio de Base</title>
</head>

<body>
    <h1>Cambio de Base</h1>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="numero">Número (formato número/base):</label>
        <input type="text" name="numero" required>
        <br><br>
        <label for="base">Base destino:</label>
        <input type="number" name="base" min="2" max="36" required>
        <br><br>
        <input type="submit" value="Convertir">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $numero_base = limpiar($_POST['numero']);
        $base_destino = (int)limpiar($_POST['base']);

        $resultado = cambiarBase($numero_base, $base_destino);
        echo "<br/>";
        echo "<h2>Resultado:</h2>";
        echo "<p>$resultado</p>";
    }

    function cambiarBase($numero, $base_destino)
    {
        $partes = explode("/", $numero);
        $num = trim($partes[0]);
        $base_origen = (int)trim($partes[1]);

        $resultado = base_convert($num, $base_origen, $base_destino);

        return "Número $num en base $base_origen es $resultado en base $base_destino";
    }

    function limpiar($dato)
    {
        return htmlspecialchars(stripslashes(trim($dato)));
    }
    ?>
</body>

</html>