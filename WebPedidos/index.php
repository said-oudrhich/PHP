<?php
// index.php - Página de entrada
// Redirige al login si no hay cookie 'NOMBRE', si existe va a pe_inicio.php
// Nota sobre el login:
// - El 'username' es el `customerNumber` de la tabla `customers` (por ejemplo: 103)
// - La contraseña es el campo `contactLastName` para ese cliente (ej. para 103 es "Schmitt")
// Por tanto, para probar: user=103 y password=Schmitt

if (!isset($_COOKIE['NOMBRE'])) {
    header('Location: pe_login.php');
    exit;
}

// Si ya hay cookie, redirigimos al panel de inicio
header('Location: pe_inicio.php');
exit;

?>
