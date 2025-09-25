<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ej6a</title>
</head>

<body>
    <p>Programa ej6a.php mostrar el array resultante del ejercicio anterior en orden inverso. Previamente, se
        deberá borrar el módulo mecanizado que no se imparte en el ciclo DAW.</p>
    <hr>
    <?php
    $asignaturas = array("Bases Datos", "Entornos Desarrollo", "Programación", "Sistemas Informáticos", "FOL", "Mecanizado", "Desarrollo Web ES", "Desarrollo Web EC", "Despliegue", "Desarrollo Interfaces", "Inglés");

    // Borrar el módulo "Mecanizado"
    $clave = array_search("Mecanizado", $asignaturas);

    unset($asignaturas[$clave]);


    // Mostrar el array en orden inverso
    echo "<br>Array en orden inverso:<br>";
    $asignaturas = array_reverse($asignaturas);
    foreach ($asignaturas as $i => $asignatura) {
        echo "Índice: $i - Asignatura: $asignatura<br>";
    }

    ?>
</body>

</html>