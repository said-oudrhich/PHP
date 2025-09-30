<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ej5am</title>
</head>

<body>
    <p>Programa ej5am.php definir una matriz de 5x3 tal que en cada posición contenga el número
        que resulta de sumar el número que identifica la columna con el número que identifica la fila del mismo,
        imprimir los elementos de la matriz por columnas.</p>
    <hr>
    <?php
    $matriz = array();
    for ($i = 0; $i < 5; $i++) {
        for ($j = 0; $j < 3; $j++) {
            $matriz[$i][$j] = $i + $j;
        }
    }

    for ($j = 0; $j < 3; $j++) {
        echo "<p><b>Columna $j: </b> ";
        for ($i = 0; $i < 5; $i++) {
            echo $matriz[$i][$j] . " ";
        }
        echo "</p>";
    }
    ?>
</body>

</html>