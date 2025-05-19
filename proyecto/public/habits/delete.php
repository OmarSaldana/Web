<?php
// Archivo: public/habits/delete.php

session_start();
require_once '../../includes/db.php';

// Verificar que el usuario haya iniciado sesión antes de permitir la eliminación
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
    // Verificar que el hábito realmente le pertenezca al usuario actual
    $stmt = $pdo->prepare("SELECT id_habito FROM habitos WHERE id_habito = ? AND id_usuario = ?");
    $stmt->execute([$id_habito, $id_usuario]);
    $habito = $stmt->fetch();

    if (!$habito) {
        // Si el hábito no pertenece al usuario o no existe, no se permite seguir
        header("Location: index.php?error=not_found");
        exit;
    }

    // Eliminar primero el historial del hábito para evitar errores por restricciones de clave foránea
    $stmt = $pdo->prepare("DELETE FROM historial_habito WHERE id_habito = ?");
    $stmt->execute([$id_habito]);

    // Luego eliminar metas relacionadas con ese hábito (si existen)
    $stmt = $pdo->prepare("DELETE FROM metas WHERE id_habito = ?");
    $stmt->execute([$id_habito]);

    // Finalmente eliminar el hábito en sí
    $stmt = $pdo->prepare("DELETE FROM habitos WHERE id_habito = ?");
    $stmt->execute([$id_habito]);

    // Redirigir de vuelta al listado con mensaje de éxito
    header("Location: index.php?deleted=1");
    exit;

} catch (PDOException $e) {
    // En caso de error de base de datos
    echo "Error al eliminar el hábito: " . $e->getMessage();
    exit;
}
