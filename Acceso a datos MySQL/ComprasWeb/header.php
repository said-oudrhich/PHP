<?php
// Header común: muestra saludo (desde cookie 'NOMBRE' o sesión) y botón de logout
if (session_status() !== PHP_SESSION_ACTIVE) {
    @session_start();
}

$nombre = $_COOKIE['NOMBRE'] ?? ($_SESSION['NOMBRE'] ?? null);

// Construir URL absoluta relativa al path actual para apuntar a Portal/comlogout.php
$script = $_SERVER['SCRIPT_NAME'] ?? '';
$logoutUrl = '../Portal/comlogout.php';
if ($script !== '') {
    $pos = strpos($script, '/ComprasWeb');
    if ($pos !== false) {
        $base = substr($script, 0, $pos + strlen('/ComprasWeb'));
        $logoutUrl = $base . '/Portal/comlogout.php';
    }
}
?>
<style>
    .site-header {
        position: fixed;
        top: 10px;
        right: 10px;
        z-index: 9999;
        font-family: Arial, Helvetica, sans-serif
    }

    .site-header .greet {
        display: inline-block;
        margin-right: 8px;
        background: #f2f2f2;
        padding: 6px 8px;
        border-radius: 4px
    }

    .site-header .logout a {
        display: inline-block;
        padding: 6px 10px;
        background: #c00;
        color: #fff;
        text-decoration: none;
        border-radius: 4px;
        font-weight: 600
    }
</style>
<div class="site-header">
    <?php if ($nombre): ?>
        <span class="greet">Bienvenido, <?php echo htmlspecialchars($nombre); ?></span>
    <?php endif; ?>
    <span class="logout"><a href="<?php echo htmlspecialchars($logoutUrl); ?>">Cerrar sesión</a></span>
</div>