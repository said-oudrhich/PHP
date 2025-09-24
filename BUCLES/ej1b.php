<!DOCTYPE html>
<html>

<head>
  <title>EJ1B – Conversor decimal a binario</title>
</head>

<body>
  <?php
  $num = 168;
  $bin = "";

  while ($num > 0) {
    $bin = $num % 2 . $bin;
    $num = (int)($num / 2);
  }
  echo "El número 168 en binario es $bin";
  ?>
</body>

</html>