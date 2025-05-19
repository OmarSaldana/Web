<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Be Better</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles_public/styles_login.css">
</head>
<body>
    <section class="hero is-fullheight" style="background-color: #F7F9FC;">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-centered">
                    <!-- Columna de imagen -->
                    <div class="column is-two-fifths">
                        <div class="box logo-container" style="height: 100%; display: flex; align-items: center; justify-content: center; min-height: 500px; background-color: white;">
                            <a href="index.php" class="back-arrow">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <figure class="image">
                                <img src="assets/img/logo.png" alt="Be Better Logo" style="max-width: 100%; height: auto;">
                            </figure>
                        </div>
                    </div>

                    <!-- Columna del formulario -->
                    <div class="column is-two-fifths">
                        <!-- Tarjeta del formulario -->
                        <div class="box">
                            <!-- Encabezado del formulario -->
                            <h1 class="title has-text-centered" style="color: #4ECDC4;">
                                Be Better
                            </h1>
                            <h2 class="subtitle has-text-centered" style="color: #6C757D;">
                                Iniciar Sesión
                            </h2>

                            <!-- Formulario de login -->
                            <form id="formulario" action="../includes/login_procesar.php" method="POST">
                                
                                <div class="field">
                                    <label class="label">Correo Electrónico</label>
                                    <div class="control has-icons-left">
                                        <input class="input" type="email" name="correo" placeholder="ejemplo@correo.com" required>
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="label">Contraseña</label>
                                    <div class="control has-icons-left">
                                        <input class="input" type="password" name="password" placeholder="Tu contraseña" required>
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="field">
                                    <div class="control">
                                        <button type="submit" class="button is-fullwidth" style="background-color: #4ECDC4; color: white;">
                                            Iniciar Sesión
                                        </button>
                                    </div>
                                </div>

                                <div class="has-text-centered mt-4">
                                    <a href="registrar.php" style="color: #6C757D;">
                                        ¿No tienes cuenta? Regístrate
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="assets/js/formulario.js"></script>
</body>
</html>
