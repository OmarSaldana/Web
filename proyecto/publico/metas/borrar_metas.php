<?php
// Archivo: publico/metas/borrar_metas.php

session_start();

// Verificar si inició sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../includes/conexion.php';

$id_usuario = $_SESSION['id_usuario'];
$id_meta = $_GET['id'] ?? null;

// Si no se proporciona un ID válido, regresar 
if (!$id_meta) {
    header("Location: index.php");
    exit;
}

try {
    // Verificar que la meta que se quiere eliminar le pertenezca al usuario
    $stmt = $pdo->prepare("SELECT * FROM metas WHERE id_meta = ? AND id_usuario = ?");
    $stmt->execute([$id_meta, $id_usuario]);
    $meta = $stmt->fetch();

    // Si no existe la meta o no pertenece al usuario
    if (!$meta) {
        header("Location: index.php?error=notfound");
        exit;
    }

    // Eliminar la meta de la base de datos
    $stmt = $pdo->prepare("DELETE FROM metas WHERE id_meta = ? AND id_usuario = ?");
    $stmt->execute([$id_meta, $id_usuario]);

    // Regresar con mensaje de éxito
    header("Location: index.php?deleted=1");
    exit;

} catch (PDOException $e) {
    // Si hay error de base de datos
    echo "Error al eliminar la meta: " . $e->getMessage();
    exit;
}
