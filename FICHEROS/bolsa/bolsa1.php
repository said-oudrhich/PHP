<?php
function leerArchivo($archivo)
{
    $alumnos = [];
    if (!file_exists($archivo)) {
        die("El fichero no existe.");
    }

    $fichero = fopen($archivo, 'r') or die('No se pudo abrir el fichero.');
    while (($linea = fgets($fichero)) !== false) {
        $valor = str_pad(trim(substr($linea, 0, 23)), 23);
        $ultimo = str_pad(trim(substr($linea, 20, 9)), 9);
        $var_porcentaje = str_pad(trim(substr($linea, 30, 9)), 8);
        $var = str_pad(trim(substr($linea, 37, 8)), 8);
        $ac_anio = str_pad(trim(substr($linea, 45, 10)), 10);
        $max = str_pad(trim(substr($linea, 55, 8)), 8);
        $min = str_pad(trim(substr($linea, 63, 8)), 8);
        $volumen = str_pad(trim(substr($linea, 70, 12)), 12);
        $capitalizacion = str_pad(trim(substr($linea, 82, 10)), 10);
        $hora = str_pad(trim(substr($linea, 92, 6)), 6);

        $accion[] = [
            'valor' => $valor,
            'ultimo' => $ultimo,
            'var_porcentaje' => $var_porcentaje,
            'var' => $var,
            'ac_anio' => $ac_anio,
            'max' => $max,
            'min' => $min,
            'volumen' => $volumen,
            'capitalizacion' => $capitalizacion,
            'hora' => $hora
        ];
    }
    fclose($fichero);
    return $accion;
}

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
    echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; width: 80%; margin: auto;'>";
    echo "<tr>
    <th>Valor</th>
    <th>Último</th>
    <th>Var. %</th>
    <th>Var.</th>
    <th>Ac. % año</th>
    <th>Máx.</th>
    <th>Mín.</th>
    <th>Vol.</th>
    <th>Capit.</th>
    <th>Hora</th>
    </tr>";
    foreach ($datos as $fila) {
        echo "<tr>";
        foreach ($fila as $campo) {
            echo "<td>" . htmlspecialchars($campo) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $datos = leerArchivo('ibex35.txt');
    mostrarDatosTabla($datos);
    ?>
</body>

</html>