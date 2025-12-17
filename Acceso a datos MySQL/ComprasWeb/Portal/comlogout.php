<?php
session_start();

$_SESSION = [];
// Destruir la sesión
session_destroy();

// Redirigir al formulario de login
header('Location: comlogincli.php');
exit();
