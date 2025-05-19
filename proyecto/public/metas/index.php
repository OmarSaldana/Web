<?php
// Archivo: public/metas/index.php

session_start();
require_once '../../includes/db.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Traer todas las metas del usuario actual y su respectivo hábito (si existe)
$stmt = $pdo->prepare("SELECT m.*, h.nombre_habito FROM metas m
                       LEFT JOIN habitos h ON m.id_habito = h.id_habito
                       WHERE m.id_usuario = ?
                       ORDER BY m.fecha_inicio DESC");
$stmt->execute([$id_usuario]);
$metas = $stmt->fetchAll(); // Almacenar todas las metas
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis Metas - Be Better</title>
  <!-- Framework Bulma y FontAwesome para estilos -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="styles_metas/styles_index.css">
</head>
<body>

<!-- Navbar con logo -->
<nav class="navbar custom-header" role="navigation">
  <div class="navbar-brand">
    <span class="navbar-item">
      <img src="../assets/img/logo.png" alt="Logo de Be Better - plataforma de hábitos" style="max-height: 60px;">
    </span>
  </div>
</nav>

<section class="section">
  <div class="container">
    <!-- Botón para regresar al dashboard -->
    <a href="../dashboard.php" class="back-arrow">
      <i class="fas fa-arrow-left"></i>
    </a>

    <h1 class="title has-text-centered" style="color: #4ECDC4;">Mis Metas</h1>

    <?php if (empty($metas)): ?>
      <!-- Mostrar mensaje si el usuario no tiene metas -->
      <div class="notification is-warning">No has registrado metas aún.</div>
    <?php else: ?>
      <!-- Mostrar metas si existen -->
      <div class="columns is-multiline">
        <?php foreach ($metas as $meta): ?>
          <div class="column is-half">
            <div class="box">
              <p><strong>Hábito:</strong> <?= htmlspecialchars($meta['nombre_habito'] ?? 'Hábito eliminado') ?></p>
              <p><strong>Descripción:</strong> <?= htmlspecialchars($meta['descripcion']) ?></p>
              <p><strong>Fecha Inicio:</strong> <?= htmlspecialchars($meta['fecha_inicio']) ?></p>
              <p><strong>Fecha Fin:</strong> <?= htmlspecialchars($meta['fecha_fin']) ?></p>
              <p><strong>Objetivo:</strong> <?= $meta['cantidad_objetivo'] ?> veces</p>

              <div class="buttons mt-3">
                <!-- Botón para ver el progreso de la meta -->
                <a href="progreso.php?id_meta=<?= $meta['id_meta'] ?>" class="button is-primary is-small">Ver Progreso</a>
                <!-- Botón para editar la meta -->
                <a href="edit.php?id=<?= $meta['id_meta'] ?>" class="button is-info is-small">Editar</a>
                <!-- Botón para eliminar la meta con confirmación -->
                <a href="delete.php?id=<?= $meta['id_meta'] ?>" class="button is-danger is-small" onclick="return confirm('¿Estás seguro de eliminar esta meta?')">Eliminar</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Botón para crear una nueva meta -->
    <div class="has-text-centered mt-5">
      <a href="create.php" class="button is-success is-medium">
        <span class="icon"><i class="fas fa-plus"></i></span>
        <span>Crear nueva meta</span>
      </a>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="footer">
  <div class="content has-text-centered">
    <p>
      <strong>Be Better</strong> - Tu compañero en el camino hacia el éxito personal.<br>
      © 2025 Be Better. Todos los derechos reservados.
    </p>
  </div>
</footer>

</body>
</html>
