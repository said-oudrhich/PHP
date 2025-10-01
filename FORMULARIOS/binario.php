<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero = test_input($_POST['numero']);
    $resultado = decbin($numero);
}
echo "<h1>Conversor de Decimal a Binario</h1>";
echo "número decimal: <input type='text' value='$numero' readonly><br><br>";
echo "número binario: <input type='text' value='$resultado' readonly><br><br>";
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
