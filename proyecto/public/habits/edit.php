<?php
// Archivo: public/habits/edit.php

session_start();
// Asegurar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../includes/db.php';

$id_usuario = $_SESSION['id_usuario'];
$id_habito = $_GET['id'] ?? null;

// Si no viene el ID del hábito, regresar a la lista
if (!$id_habito) {
    header("Location: index.php");
    exit;
}

// Buscar el hábito que pertenece al usuario
$stmt = $pdo->prepare("SELECT * FROM habitos WHERE id_habito = ? AND id_usuario = ?");
$stmt->execute([$id_habito, $id_usuario]);
$habito = $stmt->fetch();

// Si no existe o no es del usuario, no dejar continuar
if (!$habito) {
    echo "Hábito no encontrado o no tienes permiso para editarlo.";
    exit;
}

// Cargar las categorías y frecuencias para llenar el formulario
$categorias = $pdo->query("SELECT id_categoria, nombre_categoria FROM categorias")->fetchAll();
$frecuencias = $pdo->query("SELECT id_frecuencia, descripcion FROM frecuencias")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Hábito - Be Better</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<section class="section">
    <div class="container">
        <!-- Botón para volver a la lista -->
        <a href="index.php" class="button is-light mb-4">
            <span class="icon"><i class="fas fa-arrow-left"></i></span>
            <span>Volver</span>
        </a>

        <div class="column is-half is-offset-one-quarter">
            <div class="box">
                <h1 class="title has-text-centered" style="color: #4ECDC4;">Editar hábito</h1>

                <!-- Formulario para editar el hábito -->
                <form action="update.php" method="POST">
                    <!-- Enviar el ID como campo oculto para saber qué hábito actualizar -->
                    <input type="hidden" name="id_habito" value="<?= htmlspecialchars($habito['id_habito']) ?>">

                    <div class="field">
                        <label class="label">Nombre del hábito</label>
                        <div class="control">
                            <input class="input" type="text" name="nombre_habito" value="<?= htmlspecialchars($habito['nombre_habito']) ?>" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Descripción</label>
                        <div class="control">
                            <textarea class="textarea" name="descripcion"><?= htmlspecialchars($habito['descripcion']) ?></textarea>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Categoría</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="id_categoria" required>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?= $cat['id_categoria'] ?>" <?= $cat['id_categoria'] == $habito['id_categoria'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['nombre_categoria']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Frecuencia</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="id_frecuencia" required>
                                    <?php foreach ($frecuencias as $freq): ?>
                                        <option value="<?= $freq['id_frecuencia'] ?>" <?= $freq['id_frecuencia'] == $habito['id_frecuencia'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($freq['descripcion']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Estatus</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="estatus" required>
                                    <option value="activo" <?= $habito['estatus'] === 'activo' ? 'selected' : '' ?>>Activo</option>
                                    <option value="inactivo" <?= $habito['estatus'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="field is-grouped is-grouped-centered mt-4">
                        <div class="control">
                            <button type="submit" class="button is-success">
                                <span class="icon"><i class="fas fa-save"></i></span>
                                <span>Actualizar hábito</span>
                            </button>
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
