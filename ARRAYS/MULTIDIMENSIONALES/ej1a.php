<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ej1am</title>
</head>

<body>
    <P>Programa ej1am.php crear una matriz de 3x3 con los sucesivos múltiplos de 2. Mostrar el contenido de la
        matriz por filas tal y como se indica en la figura.</P>
    <hr>

    <?php
    $matriz = array();
    $num = 2;

    echo "<table border='1'>";
    for ($i = 0; $i < 3; $i++) {
        echo "<tr>";
        for ($j = 0; $j < 3; $j++) {
            $matriz[$i][$j] = $num;
            echo "<td>" . $matriz[$i][$j] . "</td>";
            $num += 2;
        }
        echo "</tr>";
    }
    echo "</table>";
    ?>

</body>

</html>