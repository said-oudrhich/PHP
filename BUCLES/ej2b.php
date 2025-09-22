<HTML>

<HEAD>
  <TITLE> EJ2B – Conversor Decimal a base n </TITLE>
</HEAD>

<BODY>
  <?php
  $num = 48;
  $base = 8;


  $n = $num;
  $resultado = "";

  while ($n > 0) {
    $resto = $n % $base;
    $resultado = $resto . $resultado;
    $n = (int)($n / $base);
  }

  echo "Numero $num en base $base = $resultado<br/>";

  ?>
</BODY>

</HTML>