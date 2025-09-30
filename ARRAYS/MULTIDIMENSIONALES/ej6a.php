<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ej6am</title>
</head>

<body>
    <p>Programa ej6am.php definir una matriz de 3x3 con números aleatorios. Generar un array que contenga
        los valores máximos de cada fila y otro que contenga los promedios de la mismas. Mostrar el contenido
        de ambos arrays por pantalla.</p>
    <hr>
    <?php

    $matriz = array();

    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            $matriz[$i][$j] = rand(1, 100);
        }
    }
    echo "<h3>Matriz 3x3</h3>";
    echo "<table border='1'>";
    for ($i = 0; $i < 3; $i++) {
        echo "<tr>";
        for ($j = 0; $j < 3; $j++) {
            echo "<td>" . $matriz[$i][$j] . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";

    $maximos = array();
    $promedios = array();
    for ($i = 0; $i < 3; $i++) {
        $maximos[$i] = max($matriz[$i]);
        $promedios[$i] = array_sum($matriz[$i]) / count($matriz[$i]);
    }

    echo "<h3>Valores máximos de cada fila</h3>";
    echo "<ul>";
    foreach ($maximos as $max) {
        echo "<li>" . $max . "</li>";
    }
    echo "</ul>";

    echo "<h3>Promedios de cada fila</h3>";
    echo "<ul>";
    foreach ($promedios as $prom) {
        echo "<li>" . number_format($prom, 2) . "</li>";
    }
    echo "</ul>";
    ?>
</body>

</html>