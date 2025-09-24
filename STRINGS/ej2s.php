<HTML>

<HEAD>
    <TITLE> EJ1-Conversion IP Decimal a Binario </TITLE>
</HEAD>

<BODY>
    <?php
    $ip = "192.18.16.204";
    $p1 = strpos($ip, '.');
    $oct1 = substr($ip, 0, $p1);

    $p2 = strpos($ip, '.', $p1 + 1);
    $oct2 = substr($ip, $p1 + 1, $p2 - $p1 - 1);

    $p3 = strpos($ip, '.', $p2 + 1);
    $oct3 = substr($ip, $p2 + 1, $p3 - $p2 - 1);

    $oct4 = substr($ip, $p3 + 1);

    echo "IP $ip en binario es "
        . str_pad(decbin($oct1), 8, '0', STR_PAD_LEFT) . " "
        . str_pad(decbin($oct2), 8, '0', STR_PAD_LEFT) . " "
        . str_pad(decbin($oct3), 8, '0', STR_PAD_LEFT) . " "
        . str_pad(decbin($oct4), 8, '0', STR_PAD_LEFT);
    ?>
</BODY>

</HTML>