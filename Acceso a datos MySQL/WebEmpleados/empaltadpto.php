<?php
require("funciones.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = limpiar($_POST['nombre']);

    try {
        $conexion = conectarBD();
        // Lanzar excepciones ante errores
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Iniciar transacción
        $conexion->beginTransaction();

        if (!departamentoExiste($conexion, $nombre)) {
            $cod_dpto = generarNuevoCod_dpto($conexion);

            insertarDepartamento($conexion, $nombre, $cod_dpto);

            $conexion->commit();

            $mensaje = "Departamento \"$nombre\" insertado correctamente con código: \"$cod_dpto\"";
        } else {
            $conexion->rollBack();
            $mensaje = "El departamento \"$nombre\" ya existe.";
        }
    } catch (Exception $e) {
        $conexion->rollBack();
        $mensaje = "Error: " . $e->getMessage();
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
    <title>Insertar Departamento</title>
</head>

<body>
    <form action="empaltadpto.php" method="post">
        <label for="nombre">Nombre del Departamento:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>
        <input type="submit" value="Insertar Departamento">
    </form>
    <p><?php echo $mensaje; ?></p>
</body>

</html>