<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Operaciones Sistemas Ficheros</title>
</head>

<body>

    <h1>Operaciones Sistemas Ficheros</h1>



    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

        <label for="origen">Fichero Origen (Path/nombre):</label>
        <input type="text" id="origen" name="origen" size="40" placeholder="Ej: /ruta/fichero.txt">
        <br><br>

        <label for="destino">Fichero Destino (Path/nombre):</label>
        <input type="text" id="destino" name="destino" size="40" placeholder="Ej: /ruta/nuevo_fichero.txt">
        <br><br>

        <label>
            <input type="radio" name="operacion" value="copiar" checked>
            Copiar Fichero
        </label>
        <br>

        <label>
            <input type="radio" name="operacion" value="renombrar">
            Renombrar Fichero
        </label>
        <br>

        <label>
            <input type="radio" name="operacion" value="borrar">
            Borrar Fichero
        </label>
        <br><br>

        <input type="submit" value="Ejecutar Operación">
        <input type="reset" value="Borrar">

    </form>

    <?php
    function limpiar($dato)
    {
        return htmlspecialchars(stripslashes(trim($dato)));
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $origen = limpiar($_POST['origen']);
        $destino = limpiar($_POST['destino']);
        $operacion = limpiar($_POST['operacion']);

        $mensaje = "";

        switch ($operacion) {
            case 'copiar':

                $directorio_destino = dirname($destino);
                if (!is_dir($directorio_destino)) {
                    mkdir($directorio_destino);
                }
                if (copy($origen, $destino)) {
                    $mensaje = "El fichero {$origen} se ha copiado a {$destino}.";
                } else {
                    $mensaje = "<span>Error al copiar el fichero. Asegúrese de que el origen existe.</span>";
                }
                break;

            case 'renombrar':
                $directorio_destino = dirname($destino);
                if (!is_dir($directorio_destino)) {
                    mkdir($directorio_destino);
                }
                if (rename($origen, $destino)) {
                    $mensaje = "El fichero {$origen} se ha renombrado a {$destino}.";
                } else {
                    $mensaje = "<span>Error al renombrar el fichero. Asegúrese de que el origen existe.</span>";
                }
                break;

            case 'borrar':
                if (unlink($origen)) {
                    $mensaje = "El fichero {$origen} ha sido borrado.";
                } else {
                    $mensaje = "<span>Error al borrar el fichero. Asegúrese de que el origen existe.</span>";
                }
                break;

            default:
                $mensaje = "<span>La operación seleccionada no es válida.</span>";
        }

        echo "<p><strong>Resultado de la operación:</strong></p>";
        echo "<p>{$mensaje}</p>";
    }
    ?>
</body>

</html>