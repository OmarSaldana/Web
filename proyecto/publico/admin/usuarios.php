<?php
// Archivo: publico/admin/usuarios.php

session_start();
require_once '../../includes/conexion.php';

// Solo dejar entrar a administradores
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) {
    header("Location: ../login.php");
    exit;
}

// Seleccionar los usuarios activos, junto con su rol
$stmt = $pdo->prepare("SELECT u.id_usuario, u.nombre, u.a_paterno, u.a_materno, u.correo, u.fecha_registro, r.nombre_rol
                       FROM usuarios u
                       JOIN roles r ON u.id_rol = r.id_rol
                       WHERE u.activo = 1
                       ORDER BY u.fecha_registro DESC");
$stmt->execute();
$usuarios = $stmt->fetchAll();

// Estos flags son para mostrar mensajes según la acción realizada
$actualizado = isset($_GET['actualizado']) && $_GET['actualizado'] == 1;
$eliminado = isset($_GET['eliminado']) && $_GET['eliminado'] == 1;
$error = isset($_GET['error']) && $_GET['error'] == 1;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Usuarios Registrados - Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../admin/styles_admin/styles_admin.css">
  <script>
    // Confirmación antes de eliminar un usuario
    function confirmarEliminacion(nombre, url) {
        if (confirm(`¿Estás seguro de eliminar a ${nombre}? Esta acción no se puede deshacer.`)) {
            window.location.href = url;
        }
    }
  </script>
</head>
<body>

  <nav class="navbar custom-header">
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

      <h1 class="title has-text-centered" style="color: #4ECDC4;">Usuarios Registrados</h1>

      <!-- Alertas dependiendo de la acción -->
      <?php if ($actualizado): ?>
        <div class="notification is-success">Usuario actualizado correctamente.</div>
      <?php endif; ?>

      <?php if ($eliminado): ?>
        <div class="notification is-success">Usuario eliminado correctamente.</div>
      <?php endif; ?>

      <?php if ($error): ?>
        <div class="notification is-danger">No se pudo eliminar el usuario.</div>
      <?php endif; ?>

      <!-- Mostrar mensaje si no hay usuarios -->
      <?php if (empty($usuarios)): ?>
        <div class="notification is-warning">No hay usuarios registrados.</div>
      <?php else: ?>
        <!-- Tabla con todos los usuarios -->
        <table class="table is-fullwidth is-striped">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Correo</th>
              <th>Rol</th>
              <th>Fecha de registro</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($usuarios as $usuario): ?>
              <tr>
                <td><?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['a_paterno'] . ' ' . $usuario['a_materno']) ?></td>
                <td><?= htmlspecialchars($usuario['correo']) ?></td>
                <td><?= htmlspecialchars($usuario['nombre_rol']) ?></td>
                <td><?= date('d/m/Y', strtotime($usuario['fecha_registro'])) ?></td>
                <td class="buttons are-small">
                  <!-- Acciones disponibles por cada usuario -->
                  <a href="habitos_usuario.php?id_usuario=<?= $usuario['id_usuario'] ?>" class="button is-info is-light">Ver hábitos</a>
                  <a href="metas_usuario.php?id_usuario=<?= $usuario['id_usuario'] ?>" class="button is-link is-light">Ver metas</a>
                  <a href="estadisticas_usuario.php?id_usuario=<?= $usuario['id_usuario'] ?>" class="button is-success is-light">Estadísticas</a>
                  <a href="editar_usuario.php?id_usuario=<?= $usuario['id_usuario'] ?>" class="button is-warning is-light">Editar</a>

                  <?php if ($_SESSION['id_usuario'] != $usuario['id_usuario']): ?>
                    <!-- No permitir que se elimine a sí mismo -->
                    <button class="button is-danger is-light" onclick="confirmarEliminacion('<?= htmlspecialchars($usuario['nombre']) ?>', 'eliminar_usuario.php?id_usuario=<?= $usuario['id_usuario'] ?>')">
                      Eliminar
                    </button>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </section>

  <!-- Footer  -->
  <footer class="footer">
    <div class="content has-text-centered">
      <p><strong>Be Better</strong> - Panel de administración.</p>
      <p>© 2025 Be Better. Todos los derechos reservados.</p>
    </div>
  </footer>
</body>
</html>
