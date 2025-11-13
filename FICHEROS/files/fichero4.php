<?php
$alumnos = [];
$contador_filas = 0;

if (file_exists('alumnos2.txt')) {
    $fichero = fopen('alumnos2.txt', 'r');

    while (($linea = fgets($fichero)) !== false) {
        $linea = trim($linea);
        $datos = explode("##", $linea);

        // Solo añadimos si hay al menos 5 campos
        if (count($datos) >= 5) {
            $alumnos[] = $datos;
            $contador_filas++;
        }
    }

    fclose($fichero);
} else {
    die('No se pudo abrir el fichero.');
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Listado de Alumnos</title>
</head>

<body>

    <h1>Listado de Alumnos</h1>

    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Apellido1</th>
            <th>Apellido2</th>
            <th>Fecha Nacimiento</th>
            <th>Localidad</th>
        </tr>

        <?php foreach ($alumnos as $fila): ?>
            <tr>
                <?php foreach ($fila as $dato): ?>
                    <td><?= htmlspecialchars(trim($dato)) ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>

    <p>Número de filas leídas: <?= $contador_filas ?></p>

</body>

</html>