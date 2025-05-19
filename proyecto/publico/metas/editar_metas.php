<?php
// Archivo: publico/metas/editar_metas.php

session_start();

// Verificar si inició sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../includes/conexion.php';

$id_usuario = $_SESSION['id_usuario'];
$id_meta = $_GET['id'] ?? null;

// Si no hay ID, regresar al índice
if (!$id_meta) {
    header("Location: index.php");
    exit;
}

// Consultar la meta para asegurar de que le pertenece al usuario
$stmt = $pdo->prepare("SELECT * FROM metas WHERE id_meta = ? AND id_usuario = ?");
$stmt->execute([$id_meta, $id_usuario]);
$meta = $stmt->fetch();

// Si no se encuentra la meta o no pertenece al usuario
if (!$meta) {
    echo "Meta no encontrada o sin permisos.";
    exit;
}

// Obtener todos los hábitos activos del usuario
$stmt = $pdo->prepare("SELECT id_habito, nombre_habito FROM habitos WHERE id_usuario = ? AND estatus = 'activo'");
$stmt->execute([$id_usuario]);
$habitos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Meta - Be Better</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<section class="section">
    <div class="container">
        <!-- Enlace para volver al listado de metas -->
        <a href="index.php" class="button is-light mb-4">
            <span class="icon"><i class="fas fa-arrow-left"></i></span>
            <span>Volver</span>
        </a>

        <div class="column is-half is-offset-one-quarter">
            <div class="box">
                <h1 class="title has-text-centered" style="color: #4ECDC4;">Editar Meta</h1>

                <!-- Formulario con los datos precargados -->
                <form action="actualizar_metas.php" method="POST">
                    <input type="hidden" name="id_meta" value="<?= $meta['id_meta'] ?>">

                    <div class="field">
                        <label class="label">Hábito relacionado</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="id_habito" required>
                                    <?php foreach ($habitos as $habito): ?>
                                        <option value="<?= $habito['id_habito'] ?>" <?= $habito['id_habito'] == $meta['id_habito'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($habito['nombre_habito']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Descripción</label>
                        <div class="control">
                            <input class="input" type="text" name="descripcion" value="<?= htmlspecialchars($meta['descripcion']) ?>" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Cantidad objetivo</label>
                        <div class="control">
                            <input class="input" type="number" name="cantidad_objetivo" value="<?= $meta['cantidad_objetivo'] ?>" min="1" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Fecha de inicio</label>
                        <div class="control">
                            <input class="input" type="date" name="fecha_inicio" value="<?= $meta['fecha_inicio'] ?>" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Fecha de fin</label>
                        <div class="control">
                            <input class="input" type="date" name="fecha_fin" value="<?= $meta['fecha_fin'] ?>" required>
                        </div>
                    </div>

                    <div class="field is-grouped is-grouped-centered">
                        <div class="control">
                            <button type="submit" class="button is-success">Actualizar Meta</button>
                        </div>
                        <div class="control">
                            <a href="index.php" class="button is-light">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
</body>
</html>
