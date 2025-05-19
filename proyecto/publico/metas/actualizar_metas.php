<?php
// Archivo: publico/metas/actualizar_metas.php

// Verificar si inició sesión
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../includes/conexion.php';

// Verificar que se mandó con POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id_usuario'];
    $id_meta = $_POST['id_meta'] ?? null;
    $id_habito = $_POST['id_habito'] ?? null;
    $descripcion = trim($_POST['descripcion'] ?? '');
    $cantidad_objetivo = $_POST['cantidad_objetivo'] ?? null;
    $fecha_inicio = $_POST['fecha_inicio'] ?? null;
    $fecha_fin = $_POST['fecha_fin'] ?? null;

    // Verificar que todos los campos estén llenos
    if (!$id_meta || !$id_habito || !$descripcion || !$cantidad_objetivo || !$fecha_inicio || !$fecha_fin) {
        header("Location: editar_metas.php?id=$id_meta&error=datos_invalidos");
        exit;
    }

    try {
        // Preparara para actualizar la meta
        $stmt = $pdo->prepare("UPDATE metas SET descripcion = ?, fecha_inicio = ?, fecha_fin = ?, cantidad_objetivo = ?, id_habito = ? 
                               WHERE id_meta = ? AND id_usuario = ?");
        // Consulta con valores del formulario
        $stmt->execute([
            $descripcion,
            $fecha_inicio,
            $fecha_fin,
            $cantidad_objetivo,
            $id_habito,
            $id_meta,
            $id_usuario
        ]);

        // Regresar si hay éxito
        header("Location: index.php?updated=1");
        exit;

    } catch (PDOException $e) {
        echo "Error al actualizar la meta: " . $e->getMessage();
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
