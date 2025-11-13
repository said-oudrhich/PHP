<?php
function leerAlumnosFijo($archivo)
{
    $alumnos = [];
    if (!file_exists($archivo)) {
        die("El fichero no existe.");
    }

    $fichero = fopen($archivo, 'r') or die('No se pudo abrir el fichero.');
    while (($linea = fgets($fichero)) !== false) {
        $nombre = trim(substr($linea, 0, 40));
        $apellido1 = trim(substr($linea, 40, 41));
        $apellido2 = trim(substr($linea, 81, 41));
        $fecha_nacimiento = trim(substr($linea, 122, 10));
        $localidad = trim(substr($linea, 133, 27));

        $alumnos[] = [
            'nombre' => $nombre,
            'apellido1' => $apellido1,
            'apellido2' => $apellido2,
            'fecha_nacimiento' => $fecha_nacimiento,
            'localidad' => $localidad
        ];
    }
    fclose($fichero);
    return $alumnos;
}

// Función para mostrar la tabla HTML
function mostrarTablaAlumnos($alumnos)
{
    echo '<table border="1">';
    echo '<tr><th>Nombre</th><th>Apellido1</th><th>Apellido2</th><th>Fecha Nacimiento</th><th>Localidad</th></tr>';

    foreach ($alumnos as $alumno) {
        echo '<tr>';
        foreach ($alumno as $campo) {
            echo '<td>' . htmlspecialchars($campo) . '</td>';
        }
        echo '</tr>';
    }

    echo '</table>';
    echo '<p>Número de filas leídas: ' . count($alumnos) . '</p>';
}

// --- Programa principal ---
$archivo = 'alumnos1.txt';
$alumnos = leerAlumnosFijo($archivo);
mostrarTablaAlumnos($alumnos);
