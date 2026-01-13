<?php
// Borrar cookies
setcookie('NOMBRE', '', time() - 3600, '/');
setcookie('carrito', '', time() - 3600, '/');

// Redirigir al login
header("Location: pe_login.php");
exit;
?>
