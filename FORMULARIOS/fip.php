<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversión de IP decimal a binaria</title>
</head>

<body>
    <h1>Conversión de IP decimal a binaria</h1>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <label for="ip">IP notación decimal:</label>
        <input type="text" name="ip" id="ip" required>
        <br><br>
        <input type="submit" value="Notación binaria">
        <input type="reset" value="Borrar">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $ip_decimal = limpiar($_POST['ip']);
        $ip_binaria = decimalABinario($ip_decimal);
        echo "<br/>";
        echo "<h2>Resultado:</h2>";

        echo "<label for=ip_decimal>IP notación decimal:</label>";
        echo "<input type=text value=$ip_decimal size=35 readonly><br><br>";

        echo "<label for=ip_binaria>IP notación binaria:</label>";
        echo "<input type=text value=$ip_binaria size=40 readonly>";
    }

    function limpiar($dato)
    {
        return htmlspecialchars(stripslashes(trim($dato)));
    }

    function decimalABinario($decimal)
    {
        $partes = explode(".", $decimal);
        foreach ($partes as $parte) {
            $binario_partes[] = str_pad(decbin($parte), 8, "0", STR_PAD_LEFT);
        }
        $binario = implode(".", $binario_partes);
        return $binario;
    }
    ?>
</body>

</html>