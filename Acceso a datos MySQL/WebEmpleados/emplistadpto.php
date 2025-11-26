<?php
require("funciones.php");
require("errores.php");
$mensaje = "";

$conexion = conectarBD();
$departamentos = obtenerDepartamentos($conexion);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cod_dpto = limpiar($_POST['cod_dpto']);

    try {
        // Lanzar excepciones ante errores
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Cambiar departamento del empleado
        $empleados = obtenerEmpleadosPorDepartamento($conexion, $cod_dpto);

        $mensaje = "Empleados del departamento '$cod_dpto' obtenidos correctamente.";
    } catch (Exception $e) {
        $mensaje = mostrarError($e, ['tipo' => 'emplistadpto', 'valor' => $cod_dpto, 'columna' => 'cod_dpto']);
    } finally {
        $conexion = null;
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados en un departamento</title>
</head>

<body>

    <form action="emplistadpto.php" method="post">
        <label for="cod_dpto">Seleccione el departamento:</label>
        <select name="cod_dpto" id="cod_dpto" required>
            <?php foreach ($departamentos as $departamento) : ?>
                <option value="<?php echo $departamento['cod_dpto']; ?>">
                    <?php echo $departamento['nombre_dpto'] . " (" . $departamento['cod_dpto'] . ")"; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <input type="submit" value="Ver empleados">
    </form>

    <?php
    if (!empty($mensaje)) {
        echo "<p>$mensaje</p>";
    }

    if (count($empleados) > 0) {
        echo "<h2>Empleados en el departamento '$cod_dpto':</h2>";
        echo "<ul>";
        foreach ($empleados as $empleado) {
            echo "<li>" . $empleado['apellidos'] . ", " . $empleado['nombre'] . " (DNI: " . $empleado['dni'] . ")</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No hay empleados en el departamento '$cod_dpto'.</p>";
    }
    ?>