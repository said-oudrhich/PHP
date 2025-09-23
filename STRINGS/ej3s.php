<?php
/*
- Se separa la IP y el prefijo (por ejemplo "192.168.16.100" y "16").
- Se convierte la IP en número (para poder hacer operaciones de bits).
- Se crea la máscara numérica desplazando bits a la izquierda.
- Se obtiene la red haciendo una operación AND entre IP y máscara.
- Se obtiene el broadcast haciendo una operación OR con el complemento de la máscara.
- Se suman/restan 1 para conseguir la primera y última IP utilizable.
- Por último, se muestran los resultados en pantalla.
*/

$ip_completa = "192.168.16.100/16";

// 1. Dividir IP y máscara
list($ip, $prefijo) = explode("/", $ip_completa);

// 2. Pasar IP a número para poder hacer cálculos
$ip_num = ip2long($ip);

// 3. Crear máscara de red de forma fácil
$mask_num = (0xFFFFFFFF << (32 - $prefijo)) & 0xFFFFFFFF;
$mask_ip = long2ip($mask_num);

// 4. Dirección de red (pone en 0 la parte de host)
$red_num = $ip_num & $mask_num;
$red_ip = long2ip($red_num);

// 5. Dirección de broadcast (pone en 1 la parte de host)
$broadcast_num = $red_num | (~$mask_num & 0xFFFFFFFF);
$broadcast_ip = long2ip($broadcast_num);

// 6. Primera y última IP del rango
$primera_ip = long2ip($red_num + 1);
$ultima_ip = long2ip($broadcast_num - 1);

// 7. Mostrar todo de forma sencilla
echo "<h2>Resultados para $ip_completa</h2>";
echo "Máscara: /$prefijo ($mask_ip)<br>";
echo "Red: $red_ip<br>";
echo "Broadcast: $broadcast_ip<br>";
echo "Rango: $primera_ip - $ultima_ip<br>";
