<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Be Better</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles_public/styles_registrar.css">
</head>
<body>
<section class="hero is-fullheight" style="background-color: #F7F9FC;">
    <div class="hero-body">
        <div class="container">
            <div class="columns is-centered">
                <!-- Logo -->
                <div class="column is-two-fifths">
                    <div class="box logo-container" style="display: flex; align-items: center; justify-content: center; min-height: 500px; background-color: white;">
                        <a href="index.php" class="back-arrow">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <figure class="image">
                            <img src="assets/img/logo.png" alt="Logo Be Better" style="max-width: 100%; height: auto;">
                        </figure>
                    </div>
                </div>

                <!-- Formulario -->
                <div class="column is-two-fifths">
                    <div class="box">
                        <h1 class="title has-text-centered" style="color: #4ECDC4;">Be Better</h1>
                        <h2 class="subtitle has-text-centered" style="color: #6C757D;">Registro de nueva cuenta</h2>

                        <form id="form-registro" action="../includes/registrar_procesar.php" method="POST">
                            <!-- Nombre -->
                            <div class="field">
                                <label class="label">Nombre</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="text" name="nombre" placeholder="Tu nombre" required>
                                    <span class="icon is-small is-left"><i class="fas fa-user"></i></span>
                                </div>
                            </div>

                            <!-- Apellido paterno -->
                            <div class="field">
                                <label class="label">Apellido Paterno</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="text" name="a_paterno" placeholder="Apellido paterno" required>
                                    <span class="icon is-small is-left"><i class="fas fa-user"></i></span>
                                </div>
                            </div>

                            <!-- Apellido materno -->
                            <div class="field">
                                <label class="label">Apellido Materno</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="text" name="a_materno" placeholder="Apellido materno" required>
                                    <span class="icon is-small is-left"><i class="fas fa-user"></i></span>
                                </div>
                            </div>

                            <!-- Correo -->
                            <div class="field">
                                <label class="label">Correo Electrónico</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="email" name="correo" placeholder="ejemplo@correo.com" required>
                                    <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                                </div>
                            </div>

                            <!-- Contraseña -->
                            <div class="field">
                                <label class="label">Contraseña</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="password" name="password" placeholder="Tu contraseña" required minlength="6">
                                    <span class="icon is-small is-left"><i class="fas fa-lock"></i></span>
                                </div>
                            </div>

                            <!-- Botón -->
                            <div class="field">
                                <div class="control">
                                    <button type="submit" class="button is-fullwidth" style="background-color: #5B8BF7; color: white;">
                                        Crear cuenta
                                    </button>
                                </div>
                            </div>

                            <!-- Enlace a login -->
                            <div class="has-text-centered mt-4">
                                <a href="login.php" style="color: #6C757D;">¿Ya tienes cuenta? Inicia sesión</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JS de validación -->
<script src="/assets/js/formulario.js"></script>
</body>
</html>

