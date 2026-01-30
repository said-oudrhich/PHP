<?php
$nombre = $_COOKIE['NOMBRE'] ?? null;
?>
<style>
    .wp-container{max-width:1000px;margin:18px auto;font-family:Arial,Helvetica,sans-serif}
    header.wp-header{display:flex;justify-content:space-between;align-items:center;padding:12px 0}
    .wp-brand{font-weight:700;font-size:18px}
    .wp-user{font-size:14px}
    hr.wp-sep{border:0;border-top:1px solid #ddd;margin:12px 0}
    .alert{padding:10px 14px;border-radius:4px;margin:10px 0}
    .alert-error{background:#fdecea;color:#611a15;border:1px solid #f5c6cb}
    .alert-success{background:#e6ffed;color:#1b5e20;border:1px solid #b7f5c9}
    .alert-info{background:#eef6ff;color:#0b4f8a;border:1px solid #cde5ff}
    table.wp-table{width:100%;border-collapse:collapse;margin-top:12px}
    table.wp-table th, table.wp-table td{border:1px solid #ddd;padding:8px;text-align:left}
    a.wp-link{color:#0b67a3;text-decoration:none}
    a.wp-link:hover{text-decoration:underline}
</style>

<div class="wp-container">
    <header class="wp-header">
        <div class="wp-brand"><a class="wp-link" href="index.php">WebPedidos</a></div>
        <div class="wp-user">
            <?php if ($nombre): ?>
                <span>Bienvenido, <?= htmlspecialchars($nombre) ?></span> |
            <?php endif; ?>
            <a class="wp-link" href="logout.php">Cerrar sesión</a>
        </div>
    </header>
    <hr class="wp-sep">
</div>