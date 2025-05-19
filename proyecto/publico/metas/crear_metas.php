<?php
// Archivo: publico/metas/crear_metas.php

session_start();

// Verificar si inició sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../includes/conexion.php';

$id_usuario = $_SESSION['id_usuario'];

// Obtener los hábitos activos del usuario para asignar una meta
$stmt = $pdo->prepare("SELECT id_habito, nombre_habito FROM habitos WHERE id_usuario = ? AND estatus = 'activo'");
$stmt->execute([$id_usuario]);
$habitos = $stmt->fetchAll();

// Si no hay hábitos activos, el usuario debe crear uno primero
if (empty($habitos)) {
    echo "<p>No tienes hábitos activos. Crea uno antes de establecer una meta.</p>";
    echo '<a href="../habitos/crear_habitos.php">Crear hábito</a>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Meta - Be Better</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles_metas/styles_crear_metas.css">
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
                
            </div>
        </div>
    </nav>

    <main>
        <section class="section">
            <div class="container">
                <!-- Mensajes de error que pueden venir por la URL -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="notification is-danger is-light">
                        <button class="delete"></button>
                        <?php
                        // Muestra mensajes  según el tipo de error detectado
                        switch ($_GET['error']) {
                            case 'faltan_datos':
                                echo "Todos los campos son obligatorios. Por favor, completa el formulario.";
                                break;
                            case 'fecha_invalida':
                                echo "La fecha de inicio no puede ser posterior a la fecha de fin.";
                                break;
                            default:
                                echo "Ocurrió un error desconocido. Intenta de nuevo.";
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <a href="index.php" class="back-arrow">
                    <i class="fas fa-arrow-left"></i>
                </a>

                <div class="column is-half is-offset-one-quarter">
                    <div class="box">
                        <h1 class="title has-text-centered" style="color: #4ECDC4;">Crear nueva meta</h1>

                        <!-- Formulario para registrar una nueva meta -->
                        <form action="almacenar_metas.php" method="POST">
                            <div class="field">
                                <label class="label">Hábito relacionado</label>
                                <div class="control">
                                    <div class="select is-fullwidth">
                                        <select name="id_habito" required>
                                            <?php foreach ($habitos as $habito): ?>
                                                <option value="<?= $habito['id_habito'] ?>">
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
                                    <input class="input" type="text" name="descripcion" placeholder="Ej: Meditar 10 veces" required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Cantidad objetivo</label>
                                <div class="control">
                                    <input class="input" type="number" name="cantidad_objetivo" min="1" required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Fecha de inicio</label>
                                <div class="control">
                                    <input class="input" type="date" name="fecha_inicio" required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Fecha de fin</label>
                                <div class="control">
                                    <input class="input" type="date" name="fecha_fin" required>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="field is-grouped is-grouped-centered">
                                <div class="control">
                                    <button type="submit" class="button is-success">Guardar meta</button>
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
