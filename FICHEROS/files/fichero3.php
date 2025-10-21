<?php
$fichero = fopen('alumnos1.txt', 'r') or die('No se pudo abrir el fichero.');

$contador_filas = 0;
echo '<table border="1">';
echo '<tr><th>Nombre</th><th>Apellido1</th><th>Apellido2</th><th>Fecha Nacimiento</th><th>Localidad</th></tr>';

// Leer el contenido del fichero línea a línea
while (($linea = fgets($fichero)) !== false) {
    // Extraer los campos según las posiciones especificadas
    $nombre = trim(substr($linea, 0, 40));
    $apellido1 = trim(substr($linea, 40, 41));
    $apellido2 = trim(substr($linea, 81, 41));
    $fecha_nacimiento = trim(substr($linea, 122, 10));
    $localidad = trim(substr($linea, 133, 27));

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
