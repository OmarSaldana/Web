<?php
// Archivo: publico/habitos/reporte_semanal.php

session_start();
require_once '../../includes/conexion.php';

// Verificar si inició sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener habitos para mostrar
$stmt = $pdo->prepare("SELECT id_habito, nombre_habito FROM habitos WHERE id_usuario = ? AND estatus = 'activo'");
$stmt->execute([$id_usuario]);
$habitos = $stmt->fetchAll();

// Obtener historial de la semana actual
$historial = [];
foreach ($habitos as $habito) {
    $stmt = $pdo->prepare("SELECT fecha, completado FROM historial_habito
        WHERE id_habito = ? AND YEARWEEK(fecha, 1) = YEARWEEK(CURDATE(), 1)
        ORDER BY fecha");
    $stmt->execute([$habito['id_habito']]);
    $historial[$habito['id_habito']] = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reporte Semanal - Be Better</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="styles_habits/styles_reporte_semanal.css">
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

    <a href="../dashboard.php" class="back-arrow">
      <i class="fas fa-arrow-left"></i>
    </a>

    <h1 class="title has-text-centered" style="color: #4ECDC4;">Reporte Semanal de Hábitos</h1>

    <?php if (empty($habitos)): ?>
      <!-- Si no hay hábitos activos -->
      <div class="notification is-warning">No tienes hábitos activos registrados.</div>
    <?php else: ?>
      <!-- Recorrer cada hábito activo y motrar su historial de la semana -->
      <?php foreach ($habitos as $habito): ?>
        <div class="box">
          <h2 class="subtitle"><strong><?= htmlspecialchars($habito['nombre_habito']) ?></strong></h2>
          <table class="table is-fullwidth is-bordered is-striped">
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $dias_registrados = $historial[$habito['id_habito']] ?? [];
                if (empty($dias_registrados)) {
                    echo '<tr><td colspan="2">No hay registros esta semana.</td></tr>';
                } else {
                    foreach ($dias_registrados as $registro) {
                        echo '<tr>';
                        echo '<td>' . date('d/m/Y', strtotime($registro['fecha'])) . '</td>';
                        echo '<td><span class="tag ' . ($registro['completado'] ? 'is-success' : 'is-danger') . '">' . ($registro['completado'] ? 'Cumplido' : 'Pendiente') . '</span></td>';
                        echo '</tr>';
                    }
                }
              ?>
            </tbody>
          </table>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</section>

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
