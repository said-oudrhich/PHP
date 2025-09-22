<HTML>

<HEAD>
    <TITLE> EJ6B – Factorial</TITLE>
</HEAD>

<BODY>
    <?php
    $num = "8";
    $cadena = "";
    for ($i = $num, $factorial = 1; $i >= 1; $i--) {
        $factorial *= $i;
        $cadena .= $i . ($i > 1 ? " x " : "");
    }
    echo "El factorial de $num es $cadena = $factorial";
    ?>

</BODY>

</HTML>