<?php
$nombre = $_COOKIE['NOMBRE'] ?? null;
?>

<header class="wp-header">
    <div class="wp-container">
        <div class="wp-brand"><a class="wp-link" href="index.php">WebPedidos</a></div>
        <div class="wp-user">
            <?php if ($nombre): ?>
                <span>Bienvenido, <?= htmlspecialchars($nombre) ?></span> |
            <?php endif; ?>
            <a class="wp-link" href="logout.php">Cerrar sesión</a>
        </div>
    </div>
</header>