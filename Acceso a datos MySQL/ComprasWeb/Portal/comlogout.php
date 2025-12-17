<?php
session_start();

// Destruir la sesión actual
session_unset();
session_destroy();

setcookie("PHPSESSID", "", time() - 3600, "/");
setcookie("NOMBRE", "", time() - 3600, "/");

// Redirigir al formulario de login
header('Location: comlogincli.php');
exit();
