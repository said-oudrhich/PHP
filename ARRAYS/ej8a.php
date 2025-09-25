<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ej8a</title>
</head>

<body>
    <p>Programa ej8a.php crear un array asociativo con los nombres de 5 alumnos y la nota de cada uno de
        ellos en Bases Datos. Se pide:
        a. Mostrar el alumno con mayor nota.
        b. Mostrar el alumno con menor nota.
        c. Media notas obtenidas por los alumnos</p>
    <hr>
    <?php
    $alumnos = array("Said" => 7, "Juan" => 1, "Miguel" => 9, "Alex" => 6, "Roberto" => 8);
    $max = max($alumnos);
    $min = min($alumnos);
    $suma = array_sum($alumnos);
    $media = $suma / count($alumnos);

    foreach ($alumnos as $indice => $nota) {
        if ($nota == $max) {
            echo ("El alumno con mayor nota es <strong>$indice</strong> con un <strong>$nota</strong><br>");
        }
        if ($nota == $min) {
            echo ("El alumno con menor nota es <strong>$indice</strong> con un <strong>$nota</strong><br>");
        }
    }
    echo ("La media de las notas es <strong>$media</strong><br>");
    ?>
</body>

</html>