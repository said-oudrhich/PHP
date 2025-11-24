<?php
require("funciones.php");

$mensaje = "";
$conexion = conectarBD();

// Obtener categorías para el desplegable
$departamentos = obtenerDepartamentos($conexion);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = limpiar($_POST['dni']);
    $nombre = limpiar($_POST['nombre']);
    $apellidos = limpiar($_POST['apellidos']);
    $fecha_nac = limpiar($_POST['fecha_nac']);
    $salario = limpiar($_POST['salario']);
    $cod_dpto = limpiar($_POST['cod_dpto']);

    try {
        insertarEmpleado($conexion, $dni, $nombre, $apellidos, $fecha_nac, $salario, $cod_dpto);
        $mensaje = "Empleado '$nombre $apellidos' dado de alta correctamente.";
    } catch (PDOException $e) {
        $mensaje = "Error en la base de datos: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Empleado</title>
</head>

<form action="empaltaemp.php" method="post">

    <label for="dni">DNI:</label><br>
    <input type="text" id="dni" name="dni" required>
    <br><br>

    <label for="nombre">Nombre:</label><br>
    <input type="text" id="nombre" name="nombre" required>
    <br><br>

    <label for="apellidos">Apellidos:</label><br>
    <input type="text" id="apellidos" name="apellidos" required>
    <br><br>

    <label for="fecha_nac">Fecha de nacimiento:</label><br>
    <input type="date" id="fecha_nac" name="fecha_nac" required>
    <br><br>

    <label for="salario">Salario:</label><br>
    <input type="number" id="salario" name="salario" required>
    <br><br>

    <label for="cod_dpto">Departamento:</label><br>
    <select id="cod_dpto" name="cod_dpto" required>
        <?php
        foreach ($departamentos as $departamento) {
            echo "<option value='" . $departamento['cod_dpto'] . "'>" . $departamento['nombre_dpto'] . "</option>";
        }
        ?>
    </select>
    <br><br>

    <input type="submit" value="Alta Empleado">
</form>
<p><?php echo $mensaje; ?></p>
</body>

</html>