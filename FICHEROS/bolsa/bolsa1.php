<?php
fopen("ibex35.txt", "r") or die("No se puede abrir el archivo!");
$archivo = fopen("ibex35.txt", "r");
while (!feof($archivo)) {
    $linea = fgets($archivo);
    echo $linea . "<br>";
}
fclose($archivo);
