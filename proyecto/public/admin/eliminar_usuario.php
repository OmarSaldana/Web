<?php
// Archivo: public/admin/eliminar_usuario.php

session_start();
require_once '../../includes/db.php';

// Verificar que el usuario sea un administrador antes de permitir cualquier acción
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) {
    header("Location: ../login.php");
    exit;
}

// ObtenerID desde la URL
$id_usuario = $_GET['id_usuario'] ?? null;

// Validar que el ID sea un número entero válido y que no intente eliminarse a sí mismo
if (!filter_var($id_usuario, FILTER_VALIDATE_INT) || $id_usuario == $_SESSION['id_usuario']) {
    header("Location: usuarios.php?error=1");
    exit;
}

// Consultar si el usuario realmente existe en la base de datos
$stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);

if (!$stmt->fetch()) {
    // Si no existe, mandar un error diferente
    header("Location: usuarios.php?error=2");
    exit;
}

try {
    // En lugar de borrar el usuario físicamente, lo marco como inactivo
    // Esto ayuda a conservar los datos relacionados como hábitos o metas
    $update = $pdo->prepare("UPDATE usuarios SET activo = 0 WHERE id_usuario = ?");
    $update->execute([$id_usuario]);

    // Redirigir con mensaje de éxito
    header("Location: usuarios.php?eliminado=1");
    exit;
} catch (PDOException $e) {
    // Si falla por alguna razón (p. ej. integridad referencial), redirijo con error general
    header("Location: usuarios.php?error=3");
    exit;
}
?>

