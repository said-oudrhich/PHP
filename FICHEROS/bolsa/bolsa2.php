Programa bolsa2.php: formulario que nos permita introducir el nombre de un valor bursátil y muestre por
pantalla todos los datos de cotización de dicho valor.

<?php
require 'bolsa1.php';
require 'bolsa2.php';
?>

/**
* Muestra los datos de la bolsa en formato tabla HTML
* @param array $datos Array bidimensional con los datos de la bolsa
*/
function mostrarDatosTabla($datos)
{
if (empty($datos)) {
echo "<p>No hay datos para mostrar.</p>";
return;
}
echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
    echo "<thead>";
        echo "<tr style='background-color: #4CAF50; color: white;'>";
            echo "<th>Valor</th>";
            echo "<th>Último</th>";
            echo "<th>Var. %</th>";
            echo "<th>Var.</th>";
            echo "<th>Ac.% año</th>";