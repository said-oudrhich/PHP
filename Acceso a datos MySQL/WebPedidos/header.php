<?php
$nombre = $_COOKIE['NOMBRE'] ?? null;
?>
<div style="text-align: right; padding: 10px;">
    <?php if ($nombre): ?>
        <span>Bienvenido, <?= htmlspecialchars($nombre) ?></span> |
    <?php endif; ?>
    <a href="logout.php">Cerrar sesión</a>
</div>
<hr>