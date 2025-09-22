<HTML>

<HEAD>
    <TITLE> EJ3B – Conversor Decimal a base 16</TITLE>
</HEAD>

<BODY>
    <?php
    $num = 222; // Cambia este valor para probar otros ejemplos
    $base = 16;

    $hexChars = '0123456789ABCDEF';
    $n = $num;
    $resultado = '';
    if ($n == 0) {
        $resultado = '0';
    } else {
        while ($n > 0) {
            $resto = $n % $base;
            $resultado = $hexChars[$resto] . $resultado;
            $n = (int)($n / $base);
        }
    }
    echo "Numero $num en base $base = $resultado<br/>";
    ?>
</BODY>

</HTML>