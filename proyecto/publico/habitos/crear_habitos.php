<?php
// Archivo: publico/habitos/crear_habitos.php

session_start();
// Validar si inició sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../includes/conexion.php';

// Obtener categorías y frecuencias
$categorias = $pdo->query("SELECT id_categoria, nombre_categoria FROM categorias")->fetchAll();
$frecuencias = $pdo->query("SELECT id_frecuencia, descripcion FROM frecuencias")->fetchAll();

// Error por si biene por GET
$hay_error = isset($_GET['error']) && $_GET['error'] === 'campos_obligatorios';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nuevo Hábito - Be Better</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="styles_habits/styles_crear_habitos.css">
</head>
<body>
<section class="section">
  <div class="container">
    <a href="index.php" class="back-arrow">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="column is-half is-offset-one-quarter">
      <div class="box">
        <h1 class="title has-text-centered" style="color: #4ECDC4;">Crear nuevo hábito</h1>

        <?php if ($hay_error): ?>
          <!-- Error si faltan campos -->
          <div class="notification is-danger is-light">
            Por favor, completa todos los campos obligatorios.
          </div>
        <?php endif; ?>

        <!-- Formulario para crear un nuevo hábito -->
        <form action="almacenar_habitos.php" method="POST">
          <div class="field">
            <label class="label">Nombre del hábito</label>
            <div class="control">
              <input class="input" type="text" name="nombre_habito" required>
            </div>
          </div>

          <div class="field">
            <label class="label">Descripción</label>
            <div class="control">
              <textarea class="textarea" name="descripcion"></textarea>
            </div>
          </div>

          <div class="field">
            <label class="label">Categoría</label>
            <div class="control">
              <div class="select is-fullwidth">
                <select name="id_categoria" required>
                  <option value="">Seleccione una categoría</option>
                  <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id_categoria'] ?>"><?= htmlspecialchars($cat['nombre_categoria']) ?></option>
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
                  <option value="">Seleccione una frecuencia</option>
                  <?php foreach ($frecuencias as $freq): ?>
                    <option value="<?= $freq['id_frecuencia'] ?>"><?= htmlspecialchars($freq['descripcion']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <!-- Botones de acción -->
          <div class="field is-grouped is-grouped-centered">
            <div class="control">
              <button type="submit" class="button is-primary">Guardar hábito</button>
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
