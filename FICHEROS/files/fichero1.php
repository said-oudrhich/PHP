<?php
function limpiar($dato)
{
    return htmlspecialchars(stripslashes(trim($dato)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = str_pad(limpiar($_POST['nombre']), 40, " ");
    $apellido1 = str_pad(limpiar($_POST['apellido1']), 41, " ");
    $apellido2 = str_pad(limpiar($_POST['apellido2']), 42, " ");
    $fecha_nacimiento = str_pad(limpiar($_POST['fecha_nacimiento']), 10, " ");
    $localidad = str_pad(limpiar($_POST['localidad']), 27, " ");

    // Crear la línea a escribir en el fichero
    $linea = $nombre . $apellido1 . $apellido2 . $fecha_nacimiento . $localidad . "\n";

    // Escribir en el fichero
    $fichero = fopen("alumnos1.txt", "a") or die("No se puede abrir el fichero.");
    fwrite($fichero, $linea);
    fclose($fichero);

    echo "Datos guardados correctamente.";
}
