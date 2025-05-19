<?php
// Archivo: public/admin/metas.php

session_start();
require_once '../../includes/db.php';

// Solo permitir el acceso si el usuario tiene una sesión activa y es administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) {
    header("Location: ../login.php");
    exit;
}

// Validar que venga el ID del usuario por GET
$id_usuario = $_GET['id_usuario'] ?? null;
if (!$id_usuario) {
    header("Location: usuarios.php");
    exit;
}

// Recuperar los datos del usuario para mostrar su nombre en el título
$stmt = $pdo->prepare("SELECT nombre, a_paterno, a_materno FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuario no encontrado.";
    exit;
}

// Obtener todas las metas que ha registrado el usuario y las asocio con su hábito
$stmt = $pdo->prepare("SELECT m.*, h.nombre_habito 
                       FROM metas m
                       JOIN habitos h ON m.id_habito = h.id_habito
                       WHERE m.id_usuario = ?
                       ORDER BY m.fecha_inicio DESC");
$stmt->execute([$id_usuario]);
$metas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Guardar la fecha de hoy para comparar con la fecha de fin de cada meta
$hoy = date('Y-m-d');
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Metas del Usuario - Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <!-- Font Awesome para íconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../admin/styles_admin/styles_admin.css">
</head>
<body>
  <!-- Encabezado con el logo -->
  <nav class="navbar custom-header" role="navigation">
    <div class="navbar-brand">
      <span class="navbar-item">
        <img src="../assets/img/logo.png" alt="Logo de Be Better - plataforma de hábitos" style="max-height: 60px;">
      </span>
    </div>
  </nav>

  <section class="section">
    <div class="container">
      <!-- Flecha de regreso -->
      <a href="usuarios.php" class="back-arrow">
        <i class="fas fa-arrow-left"></i>
      </a>

      <!-- Título dinámico con el nombre del usuario -->
      <h1 class="title has-text-centered" style="color: #4ECDC4;">
        Metas de: <?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['a_paterno'] . ' ' . $usuario['a_materno']) ?>
      </h1>

      <!-- Mostrar mensaje si no hay metas -->
      <?php if (empty($metas)): ?>
        <div class="notification is-warning">Este usuario aún no ha registrado metas.</div>
      <?php else: ?>
        <!-- Tabla con metas registradas -->
        <table class="table is-fullwidth is-striped">
          <thead>
            <tr>
              <th>Meta</th>
              <th>Hábito Asociado</th>
              <th>Inicio</th>
              <th>Fin</th>
              <th>Objetivo</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($metas as $meta): ?>
              <tr>
                <td><?= htmlspecialchars($meta['descripcion']) ?></td>
                <td><?= htmlspecialchars($meta['nombre_habito']) ?></td>
                <td><?= date('d/m/Y', strtotime($meta['fecha_inicio'])) ?></td>
                <td><?= date('d/m/Y', strtotime($meta['fecha_fin'])) ?></td>
                <td><?= htmlspecialchars($meta['cantidad_objetivo']) ?> veces</td>
                <td>
                  <!-- Dependiendo de si la meta aún está activa o no, cambiar el color del tag -->
                  <span class="tag is-<?= ($meta['fecha_fin'] >= $hoy) ? 'success' : 'danger' ?>">
                    <?= ($meta['fecha_fin'] >= $hoy) ? 'Activa' : 'Finalizada' ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </section>

  <!-- Footer institucional -->
  <footer class="footer">
    <div class="content has-text-centered">
      <p><strong>Be Better</strong> - Administración de hábitos del usuario.</p>
      <p>© 2025 Be Better. Todos los derechos reservados.</p>
    </div>
  </footer>
</body>
</html>

