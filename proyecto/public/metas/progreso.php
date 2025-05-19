<?php
// Archivo: public/metas/progreso.php

// Iniciar sesión para validar acceso del usuario
session_start();
require_once '../../includes/db.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
// Obtener el ID de la meta desde el parámetro GET
$id_meta = isset($_GET['id_meta']) ? (int)$_GET['id_meta'] : 0;
if ($id_meta <= 0) {
    header("Location: index.php");
    exit;
}

// Consultar la información de la meta y el hábito relacionado
$stmt = $pdo->prepare("SELECT m.*, h.nombre_habito FROM metas m
                       JOIN habitos h ON m.id_habito = h.id_habito
                       WHERE m.id_meta = ? AND m.id_usuario = ?");
$stmt->execute([$id_meta, $id_usuario]);
$meta = $stmt->fetch();

// Si no se encontró la meta, mostrar error
if (!$meta) {
    echo "Meta no encontrada.";
    exit;
}

// Consultar cuántas veces se ha completado el hábito dentro del rango de fechas de la meta
$completados = $pdo->prepare("SELECT COUNT(*) FROM historial_habito
                               WHERE id_habito = ? AND completado = 1
                               AND fecha BETWEEN ? AND ?");
$completados->execute([$meta['id_habito'], $meta['fecha_inicio'], $meta['fecha_fin']]);
$total_completado = $completados->fetchColumn();

// Calcular el porcentaje de cumplimiento, con un máximo de 100%
$porcentaje = min(100, round(($total_completado / $meta['cantidad_objetivo']) * 100));
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Progreso de la Meta</title>
  <!-- Estilos de Bulma y FontAwesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="styles_metas/styles_index.css">
</head>
<body>

<!-- Encabezado con logo -->
<nav class="navbar custom-header" role="navigation">
  <div class="navbar-brand">
    <span class="navbar-item">
      <img src="../assets/img/logo.png" alt="Logo de Be Better - plataforma de hábitos" style="max-height: 60px;">
    </span>
  </div>
</nav>

<section class="section">
  <div class="container">

    <!-- Flecha para volver -->
    <a href="index.php" class="back-arrow">
      <i class="fas fa-arrow-left"></i>
    </a>

    <h1 class="title has-text-centered" style="color: #4ECDC4;">Progreso de la Meta</h1>

    <!-- Información de la meta -->
    <div class="box">
      <p><strong>Hábito:</strong> <?= htmlspecialchars($meta['nombre_habito']) ?></p>
      <p><strong>Descripción:</strong> <?= htmlspecialchars($meta['descripcion']) ?></p>
      <p><strong>Fecha Inicio:</strong> <?= htmlspecialchars($meta['fecha_inicio']) ?></p>
      <p><strong>Fecha Fin:</strong> <?= htmlspecialchars($meta['fecha_fin']) ?></p>
      <p><strong>Meta Objetivo:</strong> <?= $meta['cantidad_objetivo'] ?> veces</p>
      <p><strong>Completado:</strong> <?= $total_completado ?> veces</p>

      <!-- Barra de progreso -->
      <p class="mt-3"><strong>Progreso:</strong></p>
      <progress class="progress is-primary" value="<?= $porcentaje ?>" max="100"><?= $porcentaje ?>%</progress>
      <p><?= $porcentaje ?>% completado</p>
    </div>
  </div>
</section>

<!-- Pie de página -->
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
