<html>

<head>
    <title>EJ5B – Tabla multiplicar con TD</title>
</head>

<body>

    <?php
    $num = 8;

    echo "<table border='1'>";
    for ($i = 1; $i <= 10; $i++) {
        echo "<tr><td>$num x $i</td><td>" . ($num * $i) . "</td></tr>";
    }
    echo "</table>";
    ?>

</body>

</html>