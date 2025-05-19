<?php
// Archivo: public/habits/historial.php

session_start();
require_once '../../includes/db.php';

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login.php');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$tipo = $_GET['tipo'] ?? 'dia'; // Por defecto muostrarr el historial del día

// Según el tipo de filtro seleccionado (día, semana o mes), ajustar la consulta
$whereClause = '';
switch ($tipo) {
    case 'semana':
        // Año y semana actual
        $whereClause = "AND YEARWEEK(fecha, 1) = YEARWEEK(CURDATE(), 1)";
        break;
    case 'mes':
        // Año y mes actual
        $whereClause = "AND YEAR(fecha) = YEAR(CURDATE()) AND MONTH(fecha) = MONTH(CURDATE())";
        break;
    default:
        // Solo el día actual
        $whereClause = "AND fecha = CURDATE()";
        break;
}

// Consulta para traer los hábitos completados (o no) del usuario según el rango seleccionado
$stmt = $pdo->prepare("SELECT h.nombre_habito, hh.fecha, hh.completado
    FROM historial_habito hh
    JOIN habitos h ON hh.id_habito = h.id_habito
    WHERE h.id_usuario = ? $whereClause
    ORDER BY hh.fecha DESC");
$stmt->execute([$id_usuario]);
$historial = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Historial de Hábitos</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="styles_habits/styles_historial.css">
</head>
<body>

  <!-- Navbar superior con el logo -->
  <nav class="navbar custom-header" role="navigation">
    <div class="navbar-brand">
      <span class="navbar-item">
        <img src="../assets/img/logo.png" alt="Logo de Be Better - plataforma de hábitos" style="max-height: 60px;">
      </span>
    </div>
  </nav>

  <!-- Contenido principal -->
  <main>
    <section class="section">
      <div class="container">
        <!-- Enlace para regresar al dashboard -->
        <a href="../dashboard.php" class="back-arrow">
          <i class="fas fa-arrow-left"></i>
        </a>

        <div class="title-wrapper">
          <h1 class="title has-text-centered" style="color: #4ECDC4;">Historial de Hábitos</h1>
        </div>

        <!-- Filtros por periodo: día, semana, mes -->
        <div class="buttons is-centered mb-5">
          <a href="?tipo=dia" class="button <?= $tipo == 'dia' ? 'is-info' : '' ?>">Hoy</a>
          <a href="?tipo=semana" class="button <?= $tipo == 'semana' ? 'is-info' : '' ?>">Esta semana</a>
          <a href="?tipo=mes" class="button <?= $tipo == 'mes' ? 'is-info' : '' ?>">Este mes</a>
        </div>

        <!-- Tabla de historial -->
        <?php if (empty($historial)): ?>
          <div class="notification is-warning has-text-centered">No hay registros en este periodo.</div>
        <?php else: ?>
          <table class="table is-fullwidth is-striped">
            <thead>
              <tr>
                <th>Hábito</th>
                <th>Fecha</th>
                <th>Completado</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($historial as $registro): ?>
                <tr>
                  <td><?= htmlspecialchars($registro['nombre_habito']) ?></td>
                  <td><?= date('d/m/Y', strtotime($registro['fecha'])) ?></td>
                  <td>
                    <span class="tag <?= $registro['completado'] ? 'is-success' : 'is-danger' ?>">
                      <?= $registro['completado'] ? 'Sí' : 'No' ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
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


