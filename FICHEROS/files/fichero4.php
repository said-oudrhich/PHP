<?php

$fichero = fopen('alumnos2.txt', 'r') or die('No se pudo abrir el fichero.');
$contador_filas = 0;

echo '<table border="1">';
echo '<tr><th>Nombre</th><th>Apellido1</th><th>Apellido2</th><th>Fecha Nacimiento</th><th>Localidad</th></tr>';

while (($linea = fgets($fichero)) !== false) {
    $nombre = trim(substr($linea, 0, strpos($linea, "##")));
    $resto = substr($linea, strpos($linea, "##") + 2);
    $apellido1 = trim(substr($resto, 0, strpos($resto, "##")));
    $resto = substr($resto, strpos($resto, "##") + 2);
    $apellido2 = trim(substr($resto, 0, strpos($resto, "##")));
    $resto = substr($resto, strpos($resto, "##") + 2);
    $fecha_nacimiento = trim(substr($resto, 0, strpos($resto, "##")));
    $resto = substr($resto, strpos($resto, "##") + 2);
    $localidad = trim(substr($resto, 0, strpos($resto, "\n")));
    echo '<tr>';
    echo '<td>' . $nombre . '</td>';
    echo '<td>' . $apellido1 . '</td>';
    echo '<td>' . $apellido2 . '</td>';
    echo '<td>' . $fecha_nacimiento . '</td>';
    echo '<td>' . $localidad . '</td>';
    echo '</tr>';

    $contador_filas++;
}

fclose($fichero);

echo '</table>';
echo '<p>Número de filas leídas: ' . $contador_filas . '</p>';
