<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ej4am</title>
</head>

<body>
    <p>Programa ej4am.php a partir de la matriz del ejercicio anterior mostrar la fila y columna del elemento
        mayor. <br>
        Elemento Mayor 88 – fila 3 columna 3</p>
    <hr>
    <?php
    $matriz = [
        array(1, 2, 3, 4, 5),
        array(6, 7, 8, 9, 10),
        array(11, 12, 13, 14, 15)
    ];
    $mayor = $matriz[0][0];
    $fila = 0;
    $columna = 0;
    for ($i = 0; $i < count($matriz); $i++) {
        for ($j = 0; $j < count($matriz[$i]); $j++) {
            if ($matriz[$i][$j] > $mayor) {
                $mayor = $matriz[$i][$j];
                $fila = $i;
                $columna = $j;
            }
        }
    }
    echo "El elemento mayor es $mayor, que se encuentra en la fila " . ($fila + 1) . " y columna " . ($columna + 1);
    ?>
</body>

</html>