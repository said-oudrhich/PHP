<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Operaciones Ficheros</h2>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <label for="fichero">Fichero (Path/nombre):</label>
        <input type="text" name="fichero" id="fichero" required />
        <br /><br />

        <p>Operaciones:</p>

        <label>
            <input type="radio" name="opcion" value="mostrar_todo" />
            Mostrar Fichero
        </label>
        <br />

        <label>
            <input type="radio" name="opcion" value="mostrar_linea" />
            Mostrar línea
        </label>
        <input type="number" name="num_linea" min="1" />
        del fichero
        <br />

        <label>
            <input type="radio" name="opcion" value="primeras_lineas" checked />
            Mostrar
        </label>
        <input type="number" name="num_primeras" min="1" />
        primeras líneas
        <br /><br />

        <input type="submit" value="Enviar" />
        <input type="reset" value="Borrar" />
    </form>

    <?php

    function limpiar($dato)
    {
        return htmlspecialchars(stripslashes(trim($dato)));
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fichero = limpiar($_POST["fichero"]);
        $opcion = limpiar($_POST["opcion"]);
        $num_linea = limpiar($_POST["num_linea"]);
        $num_primeras = limpiar($_POST["num_primeras"]);

        $lineas = file($fichero, FILE_IGNORE_NEW_LINES) or die("<p>No se pudo abrir el fichero '$fichero'. Verifica que la ruta y el nombre son correctos.</p>");

        echo "<h2>Resultado:</h2>";

        switch ($opcion) {
            case "mostrar_todo":
                echo "<pre>" . implode("\n", $lineas) . "</pre>";
                break;

            case "mostrar_linea":
                if ($num_linea < 1 || $num_linea > count($lineas)) {
                    echo "<p> La línea solicitada no existe (máximo " . count($lineas) . ").</p>";
                } else {
                    echo "<pre>" . $lineas[$num_linea - 1] . "</pre>";
                }
                break;

            case "primeras_lineas":
                $primeras = array_slice($lineas, 0, $num_primeras);
                echo "<pre>" . implode("\n", $primeras) . "</pre>";
                break;

            default:
                echo "<p>Debes seleccionar una opción.</p>";
        }
    }
    ?>
</body>

</html>