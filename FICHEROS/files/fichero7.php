<?php
function limpiar($dato)
{
    return htmlspecialchars(stripslashes(trim($dato)));
}

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $origen = limpiar($_POST['origen']);
    $destino = limpiar($_POST['destino']);
    $operacion = limpiar($_POST['operacion']);

    switch ($operacion) {
        case 'copiar':
            $directorio_destino = dirname($destino);
            if (!is_dir($directorio_destino)) {
                mkdir($directorio_destino, 0777, true);
            }
            $mensaje = copy($origen, $destino)
                ? "El fichero {$origen} se ha copiado a {$destino}."
                : "Error al copiar el fichero. Asegúrese de que el origen existe.";
            break;

        case 'renombrar':
            $directorio_destino = dirname($destino);
            if (!is_dir($directorio_destino)) {
                mkdir($directorio_destino, 0777, true);
            }
            $mensaje = rename($origen, $destino)
                ? "El fichero {$origen} se ha renombrado a {$destino}."
                : "Error al renombrar el fichero. Asegúrese de que el origen existe.";
            break;

        case 'borrar':
            $mensaje = unlink($origen)
                ? "El fichero {$origen} ha sido borrado."
                : "Error al borrar el fichero. Asegúrese de que el origen existe.";
            break;

        default:
            $mensaje = "La operación seleccionada no es válida.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Operaciones Sistemas Ficheros</title>
</head>

<body>

    <h1>Operaciones Sistemas Ficheros</h1>

    <form method="POST" action="">
        <label for="origen">Fichero Origen (Path/nombre):</label>
        <input type="text" id="origen" name="origen" size="40" placeholder="Ej: /ruta/fichero.txt" required><br><br>

        <label for="destino">Fichero Destino (Path/nombre):</label>
        <input type="text" id="destino" name="destino" size="40" placeholder="Ej: /ruta/nuevo_fichero.txt"><br><br>

        <label>
            <input type="radio" name="operacion" value="copiar" checked> Copiar Fichero
        </label><br>
        <label>
            <input type="radio" name="operacion" value="renombrar"> Renombrar Fichero
        </label><br>
        <label>
            <input type="radio" name="operacion" value="borrar"> Borrar Fichero
        </label><br><br>

        <input type="submit" value="Ejecutar Operación">
        <input type="reset" value="Borrar">
    </form>

    <?php if ($mensaje !== ''): ?>
        <p>Resultado de la operación:</p>
        <p><?= $mensaje ?></p>
    <?php endif; ?>

</body>

</html>