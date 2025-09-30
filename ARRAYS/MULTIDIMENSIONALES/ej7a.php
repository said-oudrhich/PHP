<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ej7am</title>
</head>

<body>
    <p>Programa ej7am.php definir una matriz que permita almacenar la nota de 10 alumnos en 4 asignaturas
        diferentes. Se pide:</p>
    <ul>
        <li><strong>a.</strong> Mostrar por pantalla el <span style="color: #1a7f37;">alumno con mayor nota</span> en una asignatura determinada.</li>
        <li><strong>b.</strong> Mostrar por pantalla el <span style="color: #c0392b;">alumno con menor nota</span> en una asignatura determinada.</li>
        <li><strong>c.</strong> Para un alumno, mostrar en qué materia tiene su <span style="color: #c0392b;">nota más baja</span>.</li>
        <li><strong>d.</strong> Para un alumno, mostrar en qué materia tiene su <span style="color: #1a7f37;">nota más alta</span>.</li>
        <li><strong>e.</strong> Mostrar la <span style="color: #2a5d84;">media por materia</span> de todos los alumnos.</li>
        <li><strong>f.</strong> Mostrar la <span style="color: #2a5d84;">media por alumno</span> para todas las materias.</li>
    </ul>

    <hr>
    <?php
    $alumnos = array("Ana", "Luis", "Carlos", "Marta", "Sofía", "Javier", "Lucía", "David", "Elena", "Miguel");
    $asignaturas = array("Programación", "Base de datos", "Matemáticas", "Lenguaje de marcas");
    $notas = array();
    // Rellenar la matriz con notas aleatorias
    for ($i = 0; $i < count($alumnos); $i++)
        for ($j = 0; $j < count($asignaturas); $j++)
            $notas[$i][$j] = rand(1, 10);

    // Mostrar la matriz de notas
    echo "<h3>Matriz de Notas</h3>";
    echo "<table border='1'><tr><th>Alumno</th>";
    foreach ($asignaturas as $asignatura) {
        echo "<th>" . $asignatura . "</th>";
    }
    echo "</tr>";
    for ($i = 0; $i < count($alumnos); $i++) {
        echo "<tr><td>" . $alumnos[$i] . "</td>";
        for ($j = 0; $j < count($asignaturas); $j++) {
            echo "<td>" . $notas[$i][$j] . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";

    $asignaturaIndex = 0; // Programación
    $mayorNota = 0;
    $alumnoMayorNota = "";
    for ($i = 0; $i < count($alumnos); $i++) {
        if ($notas[$i][$asignaturaIndex] > $mayorNota) {
            $mayorNota = $notas[$i][$asignaturaIndex];
            $alumnoMayorNota = $alumnos[$i];
        }
    }
    echo "<p>El alumno con mayor nota en " . $asignaturas[$asignaturaIndex] . " es " . $alumnoMayorNota . " con " . $mayorNota . " puntos.</p>";

    $menorNota = 11;
    $alumnoMenorNota = "";
    for ($i = 0; $i < count($alumnos); $i++)
        if ($notas[$i][$asignaturaIndex] < $menorNota) {
            $menorNota = $notas[$i][$asignaturaIndex];
            $alumnoMenorNota = $alumnos[$i];
        }
    echo "<p>El alumno con menor nota en " . $asignaturas[$asignaturaIndex] . " es " . $alumnoMenorNota . " con " . $menorNota . " puntos.</p>";

    $alumnoIndex = 0; // Ana
    $notaMasBaja = 11;
    $materiaNotaMasBaja = "";
    for ($j = 0; $j < count($asignaturas); $j++)
        if ($notas[$alumnoIndex][$j] < $notaMasBaja) {
            $notaMasBaja = $notas[$alumnoIndex][$j];
            $materiaNotaMasBaja = $asignaturas[$j];
        }
    echo "<p>La materia en la que " . $alumnos[$alumnoIndex] . " tiene su nota más baja es " . $materiaNotaMasBaja . " con " . $notaMasBaja . " puntos.</p>";

    $notaMasAlta = 0;
    $materiaNotaMasAlta = "";
    for ($j = 0; $j < count($asignaturas); $j++)
        if ($notas[$alumnoIndex][$j] > $notaMasAlta) {
            $notaMasAlta = $notas[$alumnoIndex][$j];
            $materiaNotaMasAlta = $asignaturas[$j];
        }
    echo "<p>La materia en la que " . $alumnos[$alumnoIndex] . " tiene su nota más alta es " . $materiaNotaMasAlta . " con " . $notaMasAlta . " puntos.</p>";

    echo "<h3>Media por materia de todos los alumnos</h3>";
    echo "<ul>";
    for ($j = 0; $j < count($asignaturas); $j++) {
        $sumaNotas = 0;
        for ($i = 0; $i < count($alumnos); $i++)
            $sumaNotas += $notas[$i][$j];
        $media = $sumaNotas / count($alumnos);
        echo "<li>" . $asignaturas[$j] . ": " . number_format($media, 2) . "</li>";
    }
    echo "</ul>";

    echo "<h3>Media por alumno para todas las materias</h3>";
    echo "<ul>";
    for ($i = 0; $i < count($alumnos); $i++) {
        $sumaNotas = 0;
        for ($j = 0; $j < count($asignaturas); $j++)
            $sumaNotas += $notas[$i][$j];
        $media = $sumaNotas / count($asignaturas);
        echo "<li>" . $alumnos[$i] . ": " . number_format($media, 2) . "</li>";
    }
    echo "</ul>";
    ?>
</body>

</html>