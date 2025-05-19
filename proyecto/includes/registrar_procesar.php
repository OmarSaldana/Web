<?php
// Archivo: includes/registrar_procesar.php

require_once 'conexion.php'; // Conexión a la base de datos

// Verificar que el formulario haya sido enviado por POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar que todos los campos estén presentes
    if (
        isset($_POST['nombre']) && isset($_POST['a_paterno']) && isset($_POST['a_materno']) &&
        isset($_POST['correo']) && isset($_POST['password'])
    ) {
        // Limpiar y proteger los datos del formulario
        $nombre = trim(htmlspecialchars($_POST['nombre']));
        $a_paterno = trim(htmlspecialchars($_POST['a_paterno']));
        $a_materno = trim(htmlspecialchars($_POST['a_materno']));
        $correo = trim($_POST['correo']);
        $password = $_POST['password'];

        // Revisar que el nombre y apellidos no sean solo números
        $soloNumeros = '/^\d+$/';
        if (
            preg_match($soloNumeros, $nombre) ||
            preg_match($soloNumeros, $a_paterno) ||
            preg_match($soloNumeros, $a_materno)
        ) {
            die("<p>Nombre y apellidos no pueden contener solo números. <a href='../publico/registrar.php'>Volver</a></p>");
        }

        // Revisar que el correo tenga un formato válido
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            die("<p>Correo inválido. <a href='../publico/registrar.php'>Volver</a></p>");
        }

        // Revisar si el correo ya está registrado
        $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        if ($stmt->fetch()) {
            die("<p>El correo ya está registrado. <a href='../publico/registrar.php'>Volver</a></p>");
        }

        // Encriptar la contraseña con password_hash
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insertar el nuevo usuario con rol 2 (usuario normal)
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, a_paterno, a_materno, correo, password, fecha_registro, id_rol)
                               VALUES (?, ?, ?, ?, ?, CURDATE(), 2)");

        try {
            $stmt->execute([$nombre, $a_paterno, $a_materno, $correo, $passwordHash]);
            // Redirigir al login
            header("Location: ../public/login.php");
            exit;
        } catch (PDOException $e) {
            // Si ocurre un error al insertar
            die("<p>Error al registrar: " . $e->getMessage() . " <a href='../publico/registrar.php'>Volver</a></p>");
        }

    } else {
        // Si faltan campos en el formulario
        die("<p>Faltan campos requeridos. <a href='../publico/registrar.php'>Volver</a></p>");
    }
} else {
    // Si alguien accede directamente al archivo sin POST, mandar a
    header("Location: ../publico/registrar.php");
    exit;
}
?>

