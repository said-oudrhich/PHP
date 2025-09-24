<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <p>Programa ej2a.php: modificar el ejemplo anterior para que calcule la media de los valores que están en
        las posiciones pares y las posiciones impares.</p>
    <?php
    $impares = array();
    $suma = 0;

    // Para medias
    $suma_pares = 0;
    $suma_impares = 0;
    $cont_pares = 0;
    $cont_impares = 0;

    for ($i = 0; $i < 20; $i++) {
        $impares[$i] = 2 * $i + 1;
    }

    echo "<table border='1'>";
    echo "<tr><th>INDICE</th><th>VALOR</th><th>SUMA</th></tr>";
    for ($i = 0; $i < count($impares); $i++) {
        $suma += $impares[$i];
        //  suma y conteo para pares e impares
        if ($i % 2 == 0) {
            $suma_pares += $impares[$i];
            $cont_pares++;
        } else {
            $suma_impares += $impares[$i];
            $cont_impares++;
        }
        echo "<tr>
            <td>$i</td>
            <td>$impares[$i]</td>
            <td>$suma</td>
        </tr>";
    }
    echo "</table>";

    // medias
    $media_pares = $cont_pares > 0 ? $suma_pares / $cont_pares : 0;
    $media_impares = $cont_impares > 0 ? $suma_impares / $cont_impares : 0;

    echo "<p>Media de valores en posiciones pares: <strong>$media_pares</strong></p>";
    echo "<p>Media de valores en posiciones impares: <strong>$media_impares</strong></p>";
    ?>

</body>

</html>