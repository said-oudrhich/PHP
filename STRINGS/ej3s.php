<HTML>

<HEAD>
    <TITLE>EJ3 - Dirección de Red, Broadcast y Rango</TITLE>
</HEAD>

<BODY>
    <?php
    $ip_mascara = "192.168.16.100/16";

    // Separar IP y máscara
    $posicion_barra = strpos($ip_mascara, "/");
    $ip = substr($ip_mascara, 0, $posicion_barra);
    $mascara = substr($ip_mascara, $posicion_barra + 1);

    // Sacar octetos 
    $p1 = strpos($ip, ".");
    $o1 = substr($ip, 0, $p1);

    $p2 = strpos($ip, ".", $p1 + 1);
    $o2 = substr($ip, $p1 + 1, $p2 - $p1 - 1);

    $p3 = strpos($ip, ".", $p2 + 1);
    $o3 = substr($ip, $p2 + 1, $p3 - $p2 - 1);

    $o4 = substr($ip, $p3 + 1);

    // Pasar IP a binario
    $ip_bin = str_pad(decbin($o1), 8, "0", STR_PAD_LEFT)
        . str_pad(decbin($o2), 8, "0", STR_PAD_LEFT)
        . str_pad(decbin($o3), 8, "0", STR_PAD_LEFT)
        . str_pad(decbin($o4), 8, "0", STR_PAD_LEFT);

    // Máscara en binario
    $mask_bin = str_repeat("1", $mascara) . str_repeat("0", 32 - $mascara);

    // Dirección de red (AND bit a bit
    $red_bin = $ip_bin & $mask_bin;

    // Dirección de broadcast (OR con máscara invertida)
    $broadcast_bin = $red_bin | (~$mask_bin & str_repeat("1", 32));

    // Convertir a IP decimales
    $red_ip = bindec(substr($red_bin, 0, 8)) . "." .
        bindec(substr($red_bin, 8, 8)) . "." .
        bindec(substr($red_bin, 16, 8)) . "." .
        bindec(substr($red_bin, 24, 8));

    $broadcast_ip = bindec(substr($broadcast_bin, 0, 8)) . "." .
        bindec(substr($broadcast_bin, 8, 8)) . "." .
        bindec(substr($broadcast_bin, 16, 8)) . "." .
        bindec(substr($broadcast_bin, 24, 8));

    // Primera y última IP
    $primera_bin = str_pad(decbin(bindec($red_bin) + 1), 32, "0", STR_PAD_LEFT);
    $primera_ip = bindec(substr($primera_bin, 0, 8)) . "." .
        bindec(substr($primera_bin, 8, 8)) . "." .
        bindec(substr($primera_bin, 16, 8)) . "." .
        bindec(substr($primera_bin, 24, 8));

    $ultima_bin = str_pad(decbin(bindec($broadcast_bin) - 1), 32, "0", STR_PAD_LEFT);
    $ultima_ip = bindec(substr($ultima_bin, 0, 8)) . "." .
        bindec(substr($ultima_bin, 8, 8)) . "." .
        bindec(substr($ultima_bin, 16, 8)) . "." .
        bindec(substr($ultima_bin, 24, 8));

    echo "<h2>Resultados para $ip_mascara</h2>";
    echo "IP: $ip<br>";
    echo "Máscara: $mascara<br>";
    echo "Dirección de Red: $red_ip<br>";
    echo "Dirección de Broadcast: $broadcast_ip<br>";
    echo "Rango: $primera_ip a $ultima_ip<br>";
    ?>
</BODY>

</HTML>