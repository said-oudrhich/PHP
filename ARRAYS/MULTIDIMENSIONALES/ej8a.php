<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ej8am</title>
</head>

<body>
    <p>Programa ej8am.php definir dos matrices de 3x3 y obtener: <br>
        a. La suma de ambas matrices <a href="https://www.superprof.es/diccionario/matematicas/algebralineal/suma-matrices.html#:~:text=Qué significa suma de matrices en Matemáticas&text=Si las matrices A%3D(,ocupan la misma misma posición." target="_blank" rel="noopener noreferrer">(Ayuda: Como sumar matrices)</a> <br>
        b. El producto de las mismas <a href="https://www.superprof.es/diccionario/matematicas/algebralineal/multiplicacion-matrices.html#:~:text=Dos matrices A y B,número de filas de B.&text=El elemento cij de,la matriz B y sumándolos." target="_blank" rel="noopener noreferrer">(Ayuda: Como multiplicar matrices)</a></p>
    <hr>
    <?php
    $matriz1 = array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 9)
    );
    $matriz2 = array(
        array(9, 8, 7),
        array(6, 5, 4),
        array(3, 2, 1)
    );

    echo "<h3>Matriz 1</h3>";
    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            echo $matriz1[$i][$j] . " ";
        }
        echo "<br>";
    }
    echo "<h3>Matriz 2</h3>";
    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            echo $matriz2[$i][$j] . " ";
        }
        echo "<br>";
    }

    // Suma de matrices
    $suma = array();
    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            $suma[$i][$j] = $matriz1[$i][$j] + $matriz2[$i][$j];
        }
    }
    echo "<h3>Suma de Matrices</h3>";
    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            echo $suma[$i][$j] . " ";
        }
        echo "<br>";
    }

    // Producto de matrices
    $producto = array();
    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            $producto[$i][$j] = 0;
            for ($k = 0; $k < 3; $k++) {
                $producto[$i][$j] += $matriz1[$i][$k] * $matriz2[$k][$j];
            }
        }
    }
    echo "<h3>Producto de Matrices</h3>";
    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            echo $producto[$i][$j] . " ";
        }
        echo "<br>";
    }
    ?>
</body>

</html>