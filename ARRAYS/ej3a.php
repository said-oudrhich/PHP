<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <P>Programa ej3a.php definir un array y almacenar los 20 primeros números binarios. Mostrar en la salida
        una tabla como la de la figura</P>

    <?php
    $binarios = array();

    echo "<table border='1'>";
    echo "<tr><th>INDICE</th><th>BINARIO</th><th>OCTAL</th></tr>";
    for ($i = 0; $i < 20; $i++) {
        $binarios[$i] = decbin($i);
        echo "<tr>
            <td>$i</td>
            <td>$binarios[$i]</td>
            <td>" . decoct($i) . "</td>
        </tr>";
    }
    echo "</table>";
    ?>
</body>

</html>