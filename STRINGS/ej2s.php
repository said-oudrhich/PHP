<HTML>

<HEAD>
    <TITLE> EJ1-Conversion IP Decimal a Binario </TITLE>
</HEAD>

<BODY>
    <?php
    $ip = "192.18.16.204";
    $octetos = explode('.', $ip);
    echo "IP $ip en binario es "
        . str_pad(decbin($octetos[0]), 8, '0', STR_PAD_LEFT) . " "
        . str_pad(decbin($octetos[1]), 8, '0', STR_PAD_LEFT) . " "
        . str_pad(decbin($octetos[2]), 8, '0', STR_PAD_LEFT) . " "
        . str_pad(decbin($octetos[3]), 8, '0', STR_PAD_LEFT);
    ?>
</BODY>

</HTML>