<?php
// Archivo: publico/habitos/borrar_habitos.php

session_start();
require_once '../../includes/conexion.php';

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_habito = $_GET['id'] ?? null;

// Validar que se haya recibido un ID válido por GET
if (!$id_habito || !is_numeric($id_habito)) {
    header("Location: index.php?error=missing_id");
    exit;
}

try {
    // Verificar si el habito es del usuario
    $stmt = $pdo->prepare("SELECT id_habito FROM habitos WHERE id_habito = ? AND id_usuario = ?");
    $stmt->execute([$id_habito, $id_usuario]);
    $habito = $stmt->fetch();

    if (!$habito) {
        // Si no es de el o no existe
        header("Location: index.php?error=not_found");
        exit;
    }

    // Eliminar historial
    $stmt = $pdo->prepare("DELETE FROM historial_habito WHERE id_habito = ?");
    $stmt->execute([$id_habito]);

    // Eliminar metas
    $stmt = $pdo->prepare("DELETE FROM metas WHERE id_habito = ?");
    $stmt->execute([$id_habito]);

    // Eliminar hábito
    $stmt = $pdo->prepare("DELETE FROM habitos WHERE id_habito = ?");
    $stmt->execute([$id_habito]);

    // Regresar
    header("Location: index.php?deleted=1");
    exit;

} catch (PDOException $e) {
    // Si hay error
    echo "Error al eliminar el hábito: " . $e->getMessage();
    exit;
}
