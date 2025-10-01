<?php
echo "<h1>CONVERSOR NUMERICO</h1>";

function aBinario($num)
{
    return decbin($num);
}
function aOctal($num)
{
    return decoct($num);
}
function aHexadecimal($num)
{
    return dechex($num);
}
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


if (isset($_POST['numero']) && is_numeric($_POST['numero'])) {
    $numero = (int) test_input($_POST['numero']);
    $opcion = test_input($_POST['opcion']);

    echo "<p>Número Decimal: <input type='text' value='$numero' readonly></p>";

    echo "<table border='1' cellpadding='5'>";
    if ($opcion == "binario") {
        echo "<tr><td>Binario</td><td>" . aBinario($numero) . "</td></tr>";
    } elseif ($opcion == "octal") {
        echo "<tr><td>Octal</td><td>" . aOctal($numero) . "</td></tr>";
    } elseif ($opcion == "hexadecimal") {
        echo "<tr><td>Hexadecimal</td><td>" . aHexadecimal($numero) . "</td></tr>";
    } else { // todos
        echo "<tr><td>Binario</td><td>" . aBinario($numero) . "</td></tr>";
        echo "<tr><td>Octal</td><td>" . aOctal($numero) . "</td></tr>";
        echo "<tr><td>Hexadecimal</td><td>" . aHexadecimal($numero) . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red;'>Por favor, introduce un número decimal válido.</p>";
}
