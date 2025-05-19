<?php
// Archivo: includes/login_procesar.php

session_start();
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar que se hayan enviado ambos campos
    if (isset($_POST['correo']) && isset($_POST['password'])) {
        $correo = trim($_POST['correo']); // Quitar espacios
        $password = $_POST['password'];

        // Consultar al usuario por su correo y verificar que esté activo
        $stmt = $pdo->prepare("SELECT id_usuario, nombre, password, id_rol FROM usuarios WHERE correo = ? AND activo = 1");
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch();

        // Verificar si se encontró el usuario y la contraseña coincide
        if ($usuario && password_verify($password, $usuario['password'])) {
            // Iniciar la sesión con los datos necesarios
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['id_rol'] = $usuario['id_rol'];

            // Mandar al panel principal del usuario
            header("Location: ../publico/dashboard.php");
            exit;
        } else {
            // Si la contraseña no coincide o el usuario está inactivo
            echo "<p>Correo o contraseña incorrectos. <a href='../publico/login.php'>Volver</a></p>";
        }
    } else {
        // Si no llegaron todos los campos desde el formulario
        echo "<p>Faltan campos requeridos. <a href='../publico/login.php'>Volver</a></p>";
    }
} else {
    // Si acceden directamente sin usar el método POST, regresar al login
    header("Location: ../publico/login.php");
    exit;
}
?>

