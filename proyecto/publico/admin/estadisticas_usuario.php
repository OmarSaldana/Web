<?php
// Archivo: publico/admin/estadisticas_usuario.php

session_start();
require_once '../../includes/conexion.php';

// Verificar que solo un administrador pueda acceder a esta vista
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) {
    header("Location: ../login.php");
    exit;
}

// Obtener ID del usuario a consultar desde la URL
$id_usuario = $_GET['id_usuario'] ?? null;
if (!$id_usuario) {
    // Si no hay ID válido, regresar a la lista de usuarios
    header("Location: usuarios.php");
    exit;
}

// Obtener los datos del usuario para mostrar su nombre en pantalla
$stmt = $pdo->prepare("SELECT nombre, a_paterno, a_materno FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch();
if (!$usuario) {
    echo "Usuario no encontrado.";
    exit;
}

// Consultar cuántos hábitos tiene el usuario registrados
$totalHabitos = $pdo->prepare("SELECT COUNT(*) FROM habitos WHERE id_usuario = ?");
$totalHabitos->execute([$id_usuario]);
$total_habitos = $totalHabitos->fetchColumn();

// Consultar cuántas metas ha creado
$totalMetas = $pdo->prepare("SELECT COUNT(*) FROM metas WHERE id_usuario = ?");
$totalMetas->execute([$id_usuario]);
$total_metas = $totalMetas->fetchColumn();

// Calcular cuántas veces ha marcado hábitos como completados en su historial
$totalCompletados = $pdo->prepare("SELECT COUNT(*) FROM historial_habito h
                                    JOIN habitos hb ON h.id_habito = hb.id_habito
                                    WHERE hb.id_usuario = ? AND h.completado = 1");
$totalCompletados->execute([$id_usuario]);
$total_completados = $totalCompletados->fetchColumn();

// Obtener el total de registros en su historial
$totalHistorial = $pdo->prepare("SELECT COUNT(*) FROM historial_habito h
                                  JOIN habitos hb ON h.id_habito = hb.id_habito
                                  WHERE hb.id_usuario = ?");
$totalHistorial->execute([$id_usuario]);
$total_historial = $totalHistorial->fetchColumn();

// Calcular el porcentaje de cumplimiento
$porcentaje_cumplimiento = ($total_historial > 0)
    ? round(($total_completados / $total_historial) * 100, 1)
    : 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Estadísticas del Usuario</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../admin/styles_admin/styles_admin.css">
</head>
<body>
  <nav class="navbar custom-header" role="navigation">
    <div class="navbar-brand">
      <span class="navbar-item">
        <img src="../assets/img/logo.png" alt="Logo de Be Better - plataforma de hábitos" style="max-height: 60px;">
      </span>
    </div>
  </nav>

  <section class="section">
    <div class="container">
      <!-- Flecha de regreso a la lista de usuarios -->
      <a href="usuarios.php" class="back-arrow">
        <i class="fas fa-arrow-left"></i>
      </a>

      <h1 class="title has-text-centered" style="color: #4ECDC4;">
        Estadísticas de: <?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['a_paterno'] . ' ' . $usuario['a_materno']) ?>
      </h1>

      <!-- Muestra de estadísticas -->
      <div class="box has-background-light">
        <p><strong>Total de Hábitos:</strong> <?= $total_habitos ?></p>
        <p><strong>Total de Metas:</strong> <?= $total_metas ?></p>
        <p><strong>Total de Completados:</strong> <?= $total_completados ?></p>
        <p><strong>Porcentaje de Cumplimiento:</strong> <?= $porcentaje_cumplimiento ?>%</p>
      </div>
    </div>
  </section>

  <footer class="footer">
    <div class="content has-text-centered">
      <p><strong>Be Better</strong> - Análisis de rendimiento del usuario.</p>
      <p>© 2025 Be Better. Todos los derechos reservados.</p>
    </div>
  </footer>
</body>
</html>
