<?php
// Archivo: public/metas/update.php

session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id_usuario'];
    $id_meta = $_POST['id_meta'] ?? null;
    $id_habito = $_POST['id_habito'] ?? null;
    $descripcion = trim($_POST['descripcion'] ?? '');
    $cantidad_objetivo = $_POST['cantidad_objetivo'] ?? null;
    $fecha_inicio = $_POST['fecha_inicio'] ?? null;
    $fecha_fin = $_POST['fecha_fin'] ?? null;

    if (!$id_meta || !$id_habito || !$descripcion || !$cantidad_objetivo || !$fecha_inicio || !$fecha_fin) {
        header("Location: edit.php?id=$id_meta&error=datos_invalidos");
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE metas SET descripcion = ?, fecha_inicio = ?, fecha_fin = ?, cantidad_objetivo = ?, id_habito = ? 
                               WHERE id_meta = ? AND id_usuario = ?");
        $stmt->execute([
            $descripcion,
            $fecha_inicio,
            $fecha_fin,
            $cantidad_objetivo,
            $id_habito,
            $id_meta,
            $id_usuario
        ]);

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
