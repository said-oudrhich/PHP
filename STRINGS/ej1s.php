<HTML>

<HEAD>
    <TITLE> EJ1-Conversion IP Decimal a Binario </TITLE>
</HEAD>

<BODY>
    <?php
    $ip = "192.18.16.204";
    $p1 = substr($ip, 0, strpos($ip, '.'));
    $rest = substr($ip, strpos($ip, '.') + 1);
    $p2 = substr($rest, 0, strpos($rest, '.'));
    $rest2 = substr($rest, strpos($rest, '.') + 1);
    $p3 = substr($rest2, 0, strpos($rest2, '.'));
    $p4 = substr($rest2, strpos($rest2, '.') + 1);

    printf(
        "IP %s en binario es %08b %08b %08b %08b",
        $ip,
        $p1,
        $p2,
        $p3,
        $p4
    );
    ?>
</BODY>

</HTML>