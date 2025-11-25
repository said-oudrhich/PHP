<?php
require("funciones.php");
require("errores.php");

$mensaje = "";
$conexion = conectarBD();

$empleados = obtenerEmpleados($conexion);
$departamentos = obtenerDepartamentos($conexion);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = limpiar($_POST['dni']);
    $cod_dpto = limpiar($_POST['cod_dpto']);

    try {
        // Lanzar excepciones ante errores
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Iniciar transacción
        $conexion->beginTransaction();
        // Cambiar departamento del empleado
        cambiarDepartamentoEmpleado($conexion, $dni, $cod_dpto);

        $conexion->commit();

        $mensaje = "Empleado con DNI '$dni' cambiado al departamento '$cod_dpto' correctamente.";
    } catch (Exception $e) {
        $conexion->rollBack();
        $mensaje = mostrarError($e, ['tipo' => 'emple_depart', 'valor' => $cod_dpto, 'columna' => 'cod_dpto']);
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
    <title>Cambio de departamento de empleado:</title>
</head>

<body>
    <form action="empcambiodpto.php" method="post">
        <label for="dni">Seleccione el DNI del empleado:</label>
        <select name="dni" id="dni" required>
            <?php foreach ($empleados as $empleado) : ?>
                <option value="<?php echo $empleado['dni']; ?>">
                    <?php echo $empleado['apellidos'] . ", " . $empleado['nombre'] . " (" . $empleado['dni'] . ")"; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="cod_dpto">Seleccione el nuevo departamento:</label>
        <select name="cod_dpto" id="cod_dpto" required>
            <?php foreach ($departamentos as $departamento) : ?>
                <option value="<?php echo $departamento['cod_dpto']; ?>">
                    <?php echo $departamento['nombre_dpto'] . " (" . $departamento['cod_dpto'] . ")"; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <input type="submit" value="Cambiar Departamento">

    </form>
    <p><?php echo $mensaje; ?></p>
</body>

</html>