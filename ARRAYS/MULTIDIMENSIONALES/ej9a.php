<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ej9am</title>
</head>

<body>
    <p>9. Programa ej9am.php definir una matrices de 3x4::
        c. La matriz traspuesta <a href="https://www.superprof.es/diccionario/matematicas/algebralineal/matriz-traspuesta.html" target="_blank" rel="noopener noreferrer">(Ayuda: Traspuesta Matriz)</a></p>
    <hr>
    <?php
    $matriz = array(
        array(1, 2, 3, 4),
        array(5, 6, 7, 8),
        array(9, 10, 11, 12)
    );

    // Matriz traspuesta
    $traspuesta = array();
    for ($i = 0; $i < 4; $i++) {
        for ($j = 0; $j < 3; $j++) {
            $traspuesta[$i][$j] = $matriz[$j][$i];
        }
    }

    echo "<h3>Matriz Original</h3>";
    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 4; $j++) {
            echo $matriz[$i][$j] . " ";
        }
        echo "<br>";
    }

    echo "<h3>Matriz Traspuesta</h3>";
    for ($i = 0; $i < 4; $i++) {
        for ($j = 0; $j < 3; $j++) {
            echo $traspuesta[$i][$j] . " ";
        }
        echo "<br>";
    }
    ?>
</body>

</html>