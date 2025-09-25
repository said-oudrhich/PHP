<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ej7a</title>
</head>

<body>
    <h2>Programa ej7a.php</h2>
    <p>
        Crear un array asociativo con los nombres de 5 alumnos y la edad de cada uno de ellos.<br>
        Se pide:<br>
        a. Mostrar el contenido del array utilizando bucles.<br>
        b. Sitúa el puntero en la segunda posición del array y muestra su valor<br>
        c. Avanza una posición y muestra el valor<br>
        d. Coloca el puntero en la última posición y muestra el valor<br>
        e. Ordena el array por orden de edad (de menor a mayor) y muestra la primera posición del array y la última.<br>
    </p>
    <hr>
    <?php
    $alumnos = array("Said" => "20", "Juan" => "12", "Miguel" => "32", "Alex" => "75", "Roberto" => "64");

    foreach ($alumnos as $indice => $edad) {
        echo ("El alumno <strong>$indice</strong> tiene <strong>$edad</strong> años<br>");
    }

    echo "<br>El segundo alumno es <strong>" . next($alumnos) . "</strong><br>";
    echo "<br>El tercer alumno es <strong>" . next($alumnos) . "</strong><br>";
    end($alumnos);
    echo "<br>El último alumno es <strong>" . current($alumnos) . "</strong><br>";

    sort($alumnos);
    echo "<br>El alumno más joven tiene <strong>" . reset($alumnos) . "</strong> años<br>";
    echo "<br>El alumno más mayor tiene <strong>" . end($alumnos) . "</strong> años<br>";
    ?>

</body>

</html>