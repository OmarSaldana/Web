<?php
// Archivo: publico/dashboard.php

session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$nombre = $_SESSION['nombre'];
$id_rol = $_SESSION['id_rol'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Be Better</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="styles_public/styles_dashboard.css">
</head>
<body>

    <nav class="navbar custom-header" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <span class="navbar-item">
                <img src="assets/img/logo.png" alt="Logo de Be Better - plataforma de hábitos" style="max-height: 60px;">
            </span>
        </div>

        <div class="navbar-end">
            <div class="navbar-item">
                <a class="button is-light" href="logout.php">Cerrar sesión</a>
            </div>
        </div>
    </nav>

    <main>
        <section class="section">
            <div class="container">
                <h1 class="title has-text-primary">¡Hola, <?= htmlspecialchars($nombre) ?>!</h1>
                <p class="subtitle">Bienvenido a tu panel personal.</p>

                <?php if ($id_rol == 1): ?>
                    <p><strong>Rol:</strong> Administrador</p>
                    <div class="buttons mt-4">
                        <a href="admin/usuarios.php" class="button is-primary">Panel de Administración</a>
                    </div>
                <?php else: ?>
                    <p><strong>Rol:</strong> Usuario</p>
                <?php endif; ?>

                <div class="buttons mt-4">
                    <a href="habitos/index.php" class="button is-link">Mis Hábitos</a>
                    <a href="metas/index.php" class="button is-info">Mis Metas</a>
                    <a href="habitos/historial_habitos.php" class="button is-success">Historial de Hábitos</a>
                    <a href="habitos/reporte_semanal.php" class="button is-warning">Reporte Semanal</a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="content has-text-centered">
            <p>
                <strong>Be Better</strong> - Tu compañero en el camino hacia el éxito personal.
                <br>
                © 2025 Be Better. Todos los derechos reservados.
            </p>
        </div>
    </footer>
</body>
</html>
