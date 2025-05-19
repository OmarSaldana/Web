<?php
// Archivo: publico/admin/editar_usuario.php

session_start();
require_once '../../includes/conexion.php';

// Asegurar que solo un administrador pueda acceder a esta vista
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) {
    header("Location: ../login.php");
    exit;
}

// Obtener ID del usuario desde la URL
$id_usuario = $_GET['id_usuario'] ?? null;
if (!$id_usuario) {
    // Si no se proporciona un ID válido, regresa a la lista
    header("Location: usuarios.php");
    exit;
}

// Consultar los datos del usuario para mostrar su nombre y rol actual
$stmt = $pdo->prepare("SELECT nombre, a_paterno, a_materno, id_rol FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuario no encontrado.";
    exit;
}

// Si el formulario fue enviado, procesar la actualización del rol
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_rol = $_POST['id_rol'] ?? 2;

    // Solo permitir los roles válidos (1 = admin, 2 = usuario)
    if (!in_array($nuevo_rol, ['1', '2'])) {
        header("Location: usuarios.php?error=rol_invalido");
        exit;
    }

    // Actualizar el rol en la base de datos
    $update = $pdo->prepare("UPDATE usuarios SET id_rol = ? WHERE id_usuario = ?");
    $update->execute([$nuevo_rol, $id_usuario]);

    // Redirigir con mensaje de éxito
    header("Location: usuarios.php?actualizado=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../admin/styles_admin/styles_admin.css">
</head>
<body>
<!-- Encabezado con el logo -->
<nav class="navbar custom-header" role="navigation">
    <div class="navbar-brand">
        <span class="navbar-item">
            <img src="../assets/img/logo.png" alt="Logo de Be Better - plataforma de hábitos" style="max-height: 60px;">
        </span>
    </div>
</nav>

<section class="section">
    <div class="container">
        <!-- Botón para volver atrás -->
        <a href="usuarios.php" class="back-arrow">
            <i class="fas fa-arrow-left"></i>
        </a>

        <h1 class="title has-text-centered" style="color: #4ECDC4;">Editar Rol de Usuario</h1>

        <form method="POST">
            <div class="field">
                <label class="label">Nombre</label>
                <div class="control">
                    <input class="input" type="text" 
                        value="<?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['a_paterno'] . ' ' . $usuario['a_materno']) ?>" 
                        readonly>
                </div>
            </div>

            <!-- Selector de rol: admin o usuario -->
            <div class="field">
                <label class="label">Rol</label>
                <div class="control">
                    <div class="select">
                        <select name="id_rol" required>
                            <option value="1" <?= $usuario['id_rol'] == 1 ? 'selected' : '' ?>>Administrador</option>
                            <option value="2" <?= $usuario['id_rol'] == 2 ? 'selected' : '' ?>>Usuario</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Botones para guardar o cancelar -->
            <div class="field is-grouped mt-4">
                <div class="control">
                    <button type="submit" class="button is-primary">Guardar cambios</button>
                </div>
                <div class="control">
                    <a href="usuarios.php" class="button is-light">Cancelar</a>
                </div>
            </div>
        </form>
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
