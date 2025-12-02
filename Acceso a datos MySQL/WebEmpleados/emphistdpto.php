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
        $historicoEmpleados = obtenerHistoricoEmpleadosPorDepartamento($conexion, $cod_dpto);

        $mensaje = "Se ha obtenido el historial de empleados para el departamento '" . $cod_dpto . "'.";
    } catch (Exception $e) {
        $mensaje = mostrarError($e, ['tipo' => 'emp_hist_dept', 'valor' => $cod_dpto, 'columna' => 'cod_dpto']);
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
    <title>Historial de departamentos de empleados</title>
</head>

<body>
    <form action="emphistdpto.php" method="post">
        <label for="cod_dpto">Seleccione el departamento:</label>
        <select name="cod_dpto" id="cod_dpto" required>
            <?php foreach ($departamentos as $departamento) : ?>
                <option value="<?php echo $departamento['cod_dpto']; ?>">
                    <?php echo $departamento['nombre_dpto'] . " (" . $departamento['cod_dpto'] . ")"; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <input type="submit" value="Ver Historial">
    </form>


    <?php if ($mensaje) : ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <?php if (!empty($historicoEmpleados)) : ?>
        <table border="1">
            <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
            </tr>
            <?php foreach ($historicoEmpleados as $registro) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($registro['dni']); ?></td>
                    <td><?php echo htmlspecialchars($registro['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($registro['apellidos']); ?></td>
                    <td><?php echo htmlspecialchars($registro['fecha_ini']); ?></td>
                    <td><?php echo htmlspecialchars($registro['fecha_fin']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST") : ?>
        <p>No hay empleados históricos en este departamento.</p>
    <?php endif; ?>


</body>

</html>