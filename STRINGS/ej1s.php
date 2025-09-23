<HTML>

<HEAD>
    <TITLE> EJ1-Conversion IP Decimal a Binario </TITLE>
</HEAD>

<BODY>
    <?php
    $ip = "192.18.16.204";
    $octetos = explode('.', $ip);
    printf(
        "IP %s en binario es %08b %08b %08b %08b",
        $ip,
        $octetos[0],
        $octetos[1],
        $octetos[2],
        $octetos[3]
    );
    ?>
</BODY>

</HTML>