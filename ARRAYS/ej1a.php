<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ej1a</title>
</head>

<body>
    <p>Programa ej1a.php: definir un array y almacenar los 20 primeros números impares. Mostrar en la salida
        una tabla como la de la figura</p>
    <hr>
    <?php

    $impares = array();
    $suma = 0;

    for ($i = 0; $i < 20; $i++) {
        $impares[$i] = 2 * $i + 1;
    }

    echo "<table border='1'>";
    echo "<tr><th>INDICE</th><th>VALOR</th><th>SUMA</th></tr>";
    for ($i = 0; $i < count($impares); $i++) {
        $suma += $impares[$i];
        echo "<tr>
            <td>$i</td>
            <td>$impares[$i]</td>
            <td>$suma</td>
        </tr>";
    }
    echo "</table>";
    ?>

</body>

</html>