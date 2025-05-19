<?php
// Archivo: publico/habitos/index.php

session_start();
require_once '../../includes/conexion.php';

// Si no inició sesión, mandar al login
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login.php');
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Traer habitos del usuario
$query = "SELECT h.*, c.nombre_categoria AS categoria_nombre, f.descripcion AS frecuencia 
          FROM habitos h 
          LEFT JOIN categorias c ON h.id_categoria = c.id_categoria 
          LEFT JOIN frecuencias f ON h.id_frecuencia = f.id_frecuencia 
          WHERE h.id_usuario = ? 
          ORDER BY h.fecha_registro DESC";

$stmt = $pdo->prepare($query);
$stmt->execute([$id_usuario]);
$habitos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verificar cuáles hábitos ya fueron completados el día de hoy
$hoy = date('Y-m-d');
$stmt_hist = $pdo->prepare("SELECT id_habito FROM historial_habito 
                            WHERE fecha = ? AND id_habito IN (
                                SELECT id_habito FROM habitos WHERE id_usuario = ?
                            )");
$stmt_hist->execute([$hoy, $id_usuario]);

// Guardar los IDs de los hábitos completados hoy para usarlos en los botones
$completados_hoy = [];
while ($row = $stmt_hist->fetch(PDO::FETCH_ASSOC)) {
    $completados_hoy[] = $row['id_habito'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Hábitos - Be Better</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles_habits/styles_index.css">
</head>
<body>
    <nav class="navbar custom-header" role="navigation">
        <div class="navbar-brand">
            <span class="navbar-item">
                <img src="../assets/img/logo.png" alt="Logo de Be Better - plataforma de hábitos" style="max-height: 60px;">
            </span>
        </div>
        <div class="navbar-end">
            <div class="navbar-item">
                <div class="buttons">
                    <a href="crear_habitos.php" class="button is-primary">
                        <span class="icon"><i class="fas fa-plus"></i></span>
                        <span>Nuevo Hábito</span>
                    </a>
                    <a href="../logout.php" class="button is-light">
                        <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                        <span>Cerrar Sesión</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section class="section">
        <div class="container">
            <a href="../dashboard.php" class="back-arrow">
                <i class="fas fa-arrow-left"></i>
            </a>

            <h1 class="title has-text-centered" style="color: #4ECDC4;">Mis Hábitos</h1>

            <!-- Mensajes de las acciones -->
            <?php if (isset($_GET['success'])): ?>
                <div class="notification is-success is-light">
                    <button class="delete"></button>
                    ¡Hábito creado exitosamente!
                </div>
            <?php elseif (isset($_GET['updated'])): ?>
                <div class="notification is-info is-light">
                    <button class="delete"></button>
                    Hábito actualizado correctamente.
                </div>
            <?php elseif (isset($_GET['deleted'])): ?>
                <div class="notification is-success is-light">
                    <button class="delete"></button>
                    Hábito eliminado correctamente.
                </div>
            <?php elseif (isset($_GET['error']) && $_GET['error'] === 'not_found'): ?>
                <div class="notification is-danger is-light">
                    <button class="delete"></button>
                    El hábito no fue encontrado o no tienes permisos para eliminarlo.
                </div>
            <?php elseif (isset($_GET['error']) && $_GET['error'] === 'missing_id'): ?>
                <div class="notification is-warning is-light">
                    <button class="delete"></button>
                    No se proporcionó un ID de hábito válido.
                </div>
            <?php endif; ?>

            <!-- Si no hay hábitos registrados -->
            <?php if (empty($habitos)): ?>
                <div class="no-habits">
                    <p class="is-size-4 mb-4">¡Aún no tienes hábitos registrados!</p>
                    <p class="mb-4">Comienza a crear buenos hábitos para mejorar tu vida.</p>
                    <a href="crear_habitos.php" class="button is-primary is-medium">
                        <span class="icon"><i class="fas fa-plus"></i></span>
                        <span>Crear mi primer hábito</span>
                    </a>
                </div>
            <?php else: ?>
                <!-- Recorrer hábitos y mostrar en cuaadrados -->
                <div class="columns is-multiline">
                    <?php foreach ($habitos as $habito): ?>
                        <div class="column is-one-third">
                            <div class="card">
                                <div class="card-content">
                                    <div class="content">
                                        <h3 class="title is-4"><?= htmlspecialchars($habito['nombre_habito']); ?></h3>

                                        <?php if (!empty($habito['descripcion'])): ?>
                                            <p><?= htmlspecialchars($habito['descripcion']); ?></p>
                                        <?php endif; ?>

                                        <div class="field">
                                            <label class="label is-small">Categoría</label>
                                            <p class="is-size-6"><?= htmlspecialchars($habito['categoria_nombre']); ?></p>
                                        </div>

                                        <div class="field">
                                            <label class="label is-small">Frecuencia</label>
                                            <p class="is-size-6"><?= htmlspecialchars($habito['frecuencia']); ?></p>
                                        </div>

                                        <div class="field">
                                            <label class="label is-small">Estado</label>
                                            <p class="is-size-6 <?= $habito['estatus'] === 'activo' ? 'status-active' : 'status-inactive'; ?>">
                                                <span class="icon"><i class="fas <?= $habito['estatus'] === 'activo' ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i></span>
                                                <?= $habito['estatus'] === 'activo' ? 'Activo' : 'Inactivo'; ?>
                                            </p>
                                        </div>

                                        <div class="field">
                                            <label class="label is-small">Fecha de registro</label>
                                            <p class="is-size-6"><?= date('d/m/Y', strtotime($habito['fecha_registro'])); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Botones de acciones -->
                                <footer class="card-footer">
                                    <div class="card-footer-item habit-actions">
                                        <a href="editar_habitos.php?id=<?= $habito['id_habito']; ?>" class="button is-info is-small">
                                            <span class="icon"><i class="fas fa-edit"></i></span>
                                            <span>Editar</span>
                                        </a>
                                        <a href="borrar_habitos.php?id=<?= $habito['id_habito']; ?>" class="button is-danger is-small"
                                           onclick="return confirm('¿Estás seguro de que deseas eliminar este hábito?')">
                                            <span class="icon"><i class="fas fa-trash"></i></span>
                                            <span>Eliminar</span>
                                        </a>
                                        <!-- botón para marcar como completado si no se ha hecho hoy -->
                                        <?php if (!in_array($habito['id_habito'], $completados_hoy)): ?>
                                            <a href="marcar_completo.php?id=<?= $habito['id_habito']; ?>" class="button is-success is-small">
                                                <span class="icon"><i class="fas fa-check"></i></span>
                                                <span>Marcar como completado</span>
                                            </a>
                                        <?php else: ?>
                                            <span class="tag is-success is-light">Completado hoy</span>
                                        <?php endif; ?>
                                    </div>
                                </footer>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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
