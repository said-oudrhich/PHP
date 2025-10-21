<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido1 = $_POST['apellido1'];
    $apellido2 = $_POST['apellido2'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $localidad = $_POST['localidad'];

    $linea = $nombre . "##" . $apellido1 . "##" . $apellido2 . "##" . $fecha_nacimiento . "##" . $localidad . "\n";

    $fichero = fopen("alumnos2.txt", "a") or die("No se puede abrir el fichero.");
    fwrite($fichero, $linea);
    fclose($fichero);

    echo "Datos guardados correctamente.";
}
