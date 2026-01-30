<?php
// logout.php - borrar cookies de sesión/carrito y volver al login
setcookie('NOMBRE', '', time() - 3600, '/');
setcookie('carrito', '', time() - 3600, '/');

// Opcional: destruir cualquier dato de sesión si se usara
if (session_status() === PHP_SESSION_ACTIVE) {
	session_unset();
	session_destroy();
}

// Redirigir al login
header("Location: pe_login.php");
exit;
?>
