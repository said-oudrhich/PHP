<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <p>Programa ej2am.php modificar el ejercicio anterior para mostrar la suma de los elementos por filas y por
        columnas. Los valores se almacenarán en dos arrays.</p>
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

    $sumaFilas = array();
    $sumaColumnas = array();
    for ($i = 0; $i < 3; $i++) {
        $sumaFilas[$i] = 0;
    }
    for ($j = 0; $j < 3; $j++) {
        $sumaColumnas[$j] = 0;
    }

    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            $sumaFilas[$i] += $matriz[$i][$j];
            $sumaColumnas[$j] += $matriz[$i][$j];
        }
    }

    echo "<h3>Suma de filas:</h3>";
    echo "<table border='1'><tr>";
    for ($i = 0; $i < 3; $i++) {
        echo "<td>Fila $i: " . $sumaFilas[$i] . "</td>";
    }
    echo "</tr></table>";

    echo "<h3>Suma de columnas:</h3>";
    echo "<table border='1'>";
    for ($j = 0; $j < 3; $j++) {
        echo "<tr><td>Col $j: " . $sumaColumnas[$j] . "</td></tr>";
    }
    echo "</table>";

    ?>
</body>

</html>