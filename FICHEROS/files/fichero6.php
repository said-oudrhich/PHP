<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Operaciones Ficheros</h1>
    <form action="" method="post">
        <label for="fichero">Fichero (Path/nombre):</label>
        <input type="text" name="fichero" id="fichero" required />
        <br /><br />
        <input type="submit" value="Ver datos Fichero" />
        <input type="reset" value="Borrar" />
    </form>

    <?php

    function limpiar($dato)
    {
        return htmlspecialchars(stripslashes(trim($dato)));
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fichero = limpiar($_POST["fichero"]);

        if (file_exists($fichero)) {
            echo "<h2>Información del Fichero:</h2>";
            echo "<p><strong>Nombre del Fichero:</strong> " . basename($fichero) . "</p>";
            echo "<p><strong>Ruta Absoluta:</strong> " . realpath($fichero) . "</p>";

            echo "<p><strong>Tamaño:</strong> " . filesize($fichero) . " bytes</p>";
            echo "<p><strong>Última Modificación:</strong> " . date("d-m-Y H:i:s", filemtime($fichero)) . "</p>";
        } else {
            echo "<p style='color:red;'>El fichero '$fichero' no existe. Verifica la ruta y el nombre.</p>";
        }
    }
    ?>

</body>

</html>