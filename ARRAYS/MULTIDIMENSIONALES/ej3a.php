<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ej3am</title>
</head>

<body>
    <p>Programa ej3am.php crear una matriz de 3x5 mostrarla por pantalla imprimiendo los elementos por fila
        en primer lugar y a continuación por columna.
        (1,1) = (elemento pos 1,1) - (1,2)= (elemento pos 1,2) …
        (1,1) = (elemento pos 1,1) - (2,1)= (elemento pos 2,1) …</p>
    <hr>
    <?php

    $matriz = array(
        array(1, 2, 3, 4, 5),
        array(6, 7, 8, 9, 10),
        array(11, 12, 13, 14, 15)
    );

    echo "<h3>Imprimiendo por filas</h3>";
    for ($i = 0; $i < count($matriz); $i++) {
        for ($j = 0; $j < count($matriz[$i]); $j++) {
            echo "($i,$j) = " . $matriz[$i][$j] . " - ";
        }
        echo "<br>";
    }

    echo "<h3>Imprimiendo por columnas</h3>";
    for ($j = 0; $j < count($matriz[0]); $j++) {
        for ($i = 0; $i < count($matriz); $i++) {
            echo "($i,$j) = " . $matriz[$i][$j] . " - ";
        }
        echo "<br>";
    }

    ?>
</body>

</html>