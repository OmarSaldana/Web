<?php
// Archivo: public/admin/habitos.php

session_start();
require_once '../../includes/db.php';

// Solo permitir acceso si el usuario es administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) {
    header("Location: ../login.php");
    exit;
}

// Obtener ID del usuario para mostrar sus hábitos
$id_usuario = $_GET['id_usuario'] ?? null;
if (!$id_usuario) {
    header("Location: usuarios.php");
    exit;
}

// Consultar la información del usuario para personalizar el encabezado
$stmt = $pdo->prepare("SELECT nombre, a_paterno, a_materno FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch();

if (!$usuario) {
    echo "Usuario no encontrado.";
    exit;
}

// Recuperar todos los hábitos registrados por el usuario con sus respectivas categorías y frecuencias
$stmt = $pdo->prepare("SELECT h.*, c.nombre_categoria, f.descripcion AS frecuencia
                       FROM habitos h
                       JOIN categorias c ON h.id_categoria = c.id_categoria
                       JOIN frecuencias f ON h.id_frecuencia = f.id_frecuencia
                       WHERE h.id_usuario = ? ORDER BY h.fecha_registro DESC");
$stmt->execute([$id_usuario]);
$habitos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hábitos del Usuario - Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../admin/styles_admin/styles_admin.css">
</head>
<body>
  <!-- Header con logo institucional -->
  <nav class="navbar custom-header" role="navigation">
    <div class="navbar-brand">
      <span class="navbar-item">
        <img src="../assets/img/logo.png" alt="Logo de Be Better - plataforma de hábitos" style="max-height: 60px;">
      </span>
    </div>
  </nav>

  <!-- Contenido principal de la vista -->
  <section class="section">
    <div class="container">
      <!-- Enlace para volver a la lista de usuarios -->
      <a href="usuarios.php" class="back-arrow">
        <i class="fas fa-arrow-left"></i>
      </a>

      <!-- Título dinámico con nombre del usuario -->
      <h1 class="title has-text-centered" style="color: #4ECDC4;">
        Hábitos de: <?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['a_paterno'] . ' ' . $usuario['a_materno']) ?>
      </h1>

      <!-- Si no tiene hábitos, Mostrar una notificación -->
      <?php if (empty($habitos)): ?>
        <div class="notification is-warning">Este usuario aún no ha registrado hábitos.</div>
      <?php else: ?>
        <!-- Tabla con todos los hábitos registrados -->
        <table class="table is-fullwidth is-striped">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Categoría</th>
              <th>Frecuencia</th>
              <th>Estatus</th>
              <th>Fecha de Registro</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($habitos as $habito): ?>
              <tr>
                <td><?= htmlspecialchars($habito['nombre_habito']) ?></td>
                <td><?= htmlspecialchars($habito['nombre_categoria']) ?></td>
                <td><?= htmlspecialchars($habito['frecuencia']) ?></td>
                <td>
                  <span class="tag is-<?= $habito['estatus'] === 'activo' ? 'success' : 'danger' ?>">
                    <?= ucfirst($habito['estatus']) ?>
                  </span>
                </td>
                <td><?= date('d/m/Y', strtotime($habito['fecha_registro'])) ?></td>
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
