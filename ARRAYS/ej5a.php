<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ej5a</title>
</head>

<body>
    <p>
        Programa <strong>ej5a.php</strong>: Definir tres arrays con los siguientes valores relativos a módulos que se imparten en el ciclo DAW:
    <ul>
        <li>“Bases Datos”, “Entornos Desarrollo”, “Programación”</li>
        <li>“Sistemas Informáticos”, “FOL”, “Mecanizado”</li>
        <li>“Desarrollo Web ES”, “Desarrollo Web EC”, “Despliegue”, “Desarrollo Interfaces”, “Inglés”</li>
    </ul>
    Se pide:
    <ol>
        <li>Unir los 3 arrays en uno único <strong>sin utilizar funciones de arrays</strong></li>
        <li>Unir los 3 arrays en uno único usando la función <strong>array_merge()</strong></li>
        <li>Unir los 3 arrays en uno único usando la función <strong>array_push()</strong></li>
    </ol>
    </p>
    <hr>

    <?php
    $asignaturas1 = array("Bases Datos", "Entornos Desarrollo", "Programación");
    $asignaturas2 = array("Sistemas Informáticos", "FOL", "Mecanizado");
    $asignaturas3 = array("Desarrollo Web ES", "Desarrollo Web EC", "Despliegue", "Desarrollo Interfaces", "Inglés");

    $asignaturas = array();
    foreach ($asignaturas1 as $asignatura) {
        $asignaturas[] = $asignatura;
    }
    foreach ($asignaturas2 as $asignatura) {
        $asignaturas[] = $asignatura;
    }
    foreach ($asignaturas3 as $asignatura) {
        $asignaturas[] = $asignatura;
    }
    echo "Usando bucles<br>";
    foreach ($asignaturas as $indice => $asignatura) {
        echo "Índice: $indice - Asignatura: $asignatura<br>";
    }

    // Usando array_merge()
    $asignaturas = array_merge($asignaturas1, $asignaturas2, $asignaturas3);

    echo "<br><br>Usando array_merge()<br>";
    foreach ($asignaturas as $indice => $asignatura) {
        echo "Índice: $indice - Asignatura: $asignatura<br>";
    }

    //Unir los 3 arrays en uno único usando la función array_push()
    $asignaturas = array();
    array_push($asignaturas, ...$asignaturas1, ...$asignaturas2, ...$asignaturas3);
    echo "<br><br>Usando array_push()<br>";
    foreach ($asignaturas as $indice => $asignatura) {
        echo "Índice: $indice - Asignatura: $asignatura<br>";
    }
    ?>
</body>

</html>