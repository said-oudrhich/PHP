<?php
require("funciones.php");
require("errores.php");

$mensaje = "";
$conexion = null;

try {
    $conexion = conectarBD();
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener todos los empleados con su salario
    $empleados = obtenerEmpleados($conexion);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $dni = isset($_POST['dni']) ? limpiar($_POST['dni']) : '';
        $porcentaje = isset($_POST['porcentaje']) ? limpiar($_POST['porcentaje']) : '';

        $conexion->beginTransaction();

        // Obtener salario actual del empleado
        $empleado = obtenerEmpleadoConSalario($conexion, $dni);

        if (!$empleado || !isset($empleado['salario'])) {
            $conexion->rollBack();
            $mensaje = "Empleado no encontrado o sin salario.";
        }

        $salario_actual = floatval($empleado['salario']);
        $nuevo_salario = $salario_actual * (1 + $porcentaje / 100);

        // Actualizar salario
        actualizarSalarioEmpleado($conexion, $dni, $nuevo_salario);

        $conexion->commit();
        $mensaje = "Salario actualizado correctamente a " . $nuevo_salario . " €.";
    }
} catch (Exception $e) {
    $mensaje = mostrarError($e, ['tipo' => 'empleado', 'valor' => $dni, 'columna' => 'salario']);
} finally {
    $conexion = null;
}
?>

<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio de salario Empleado</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h1>Empleados y salarios</h1>

    <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="dni">Seleccione empleado:</label>
        <select name="dni" id="dni" required>
            <?php foreach ($empleados as $emp): ?>
                <option value="<?= htmlspecialchars($emp['dni']) ?>">
                    <?= htmlspecialchars($emp['nombre']) ?>
                    <?php if (isset($emp['salario'])): ?>
                        (Salario: €<?= number_format(floatval($emp['salario']), 2) ?>)
                    <?php endif; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="porcentaje">Porcentaje de cambio (+/-):</label>
        <input type="number" name="porcentaje" id="porcentaje" step="0.01" required>

        <button type="submit">Actualizar salario</button>
    </form>

    <?php if (!empty($mensaje)): ?>
        <p class="mensaje"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>


</body>

</html>