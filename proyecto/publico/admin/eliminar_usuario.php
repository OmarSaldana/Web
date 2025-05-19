<?php
// Archivo: publico/admin/eliminar_usuario.php

session_start();
require_once '../../includes/conexion.php';

// Verificar que el usuario sea un administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) {
    header("Location: ../login.php");
    exit;
}

// ObtenerID desde la URL
$id_usuario = $_GET['id_usuario'] ?? null;

// Validar que el ID sea un número entero válido
if (!filter_var($id_usuario, FILTER_VALIDATE_INT) || $id_usuario == $_SESSION['id_usuario']) {
    header("Location: usuarios.php?error=1");
    exit;
}

// Consultar si el usuario existe
$stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);

if (!$stmt->fetch()) {
    // Si no existe, mandar un error diferente
    header("Location: usuarios.php?error=2");
    exit;
}

try {
    // Marcar commo inactivo
    $update = $pdo->prepare("UPDATE usuarios SET activo = 0 WHERE id_usuario = ?");
    $update->execute([$id_usuario]);

    // Redirigir con mensaje de éxito
    header("Location: usuarios.php?eliminado=1");
    exit;
} catch (PDOException $e) {
    // Si falla
    header("Location: usuarios.php?error=3");
    exit;
}
?>

