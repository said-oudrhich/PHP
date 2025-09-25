<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ej4a</title>
</head>

<body>
    <p>Programa ej4a.php a partir del array definido en el ejercicio anterior crear un nuevo array que almacene
        los números binarios en orden inverso.</p>
    <hr>
    <?php
    $binarios = array();
    $binarios_inverso = array();

    for ($i = 0; $i < 20; $i++) {
        $binarios[$i] = decbin($i);
    }
    $binarios_inverso = array_reverse($binarios);
    echo "<table border='1'>";
    echo "<tr><th>INDICE</th><th>BINARIO</th></tr>";
    for ($i = 0; $i < 20; $i++) {
        echo "<tr>
            <td>$i</td>
            <td>$binarios_inverso[$i]</td>
        </tr>";
    }
    ?>
</body>

</html>