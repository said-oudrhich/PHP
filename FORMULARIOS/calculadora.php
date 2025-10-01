<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero1 = test_input($_POST['num1']);
    $numero2 = test_input($_POST['num2']);
    $operacion = test_input($_POST['operacion']);
    $resultado = 0;

    switch ($operacion) {
        case 'sumar':
            $resultado = $numero1 + $numero2;
            break;
        case 'restar':
            $resultado = $numero1 - $numero2;
            break;
        case 'multiplicar':
            $resultado = $numero1 * $numero2;
            break;
        case 'dividir':
            if ($numero2 != 0) {
                $resultado = $numero1 / $numero2;
            } else {
                $resultado = "Error: División por cero";
            }
            break;
        default:
            $resultado = "Operación no válida";
    }
}
echo "<h1>Calculadora</h1>";
echo "Resultado de la operación: $numero1 $operacion $numero2 = $resultado";

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
