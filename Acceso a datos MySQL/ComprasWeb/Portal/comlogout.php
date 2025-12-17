<?php
session_start();

session_unset();
session_destroy();

// Redirigir al formulario de login
header('Location: comlogincli.php');
exit();
