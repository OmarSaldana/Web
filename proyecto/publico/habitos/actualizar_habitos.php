<?php
// Archivo: publico/habitos/actualizar_habitos.php

session_start();

// Si no hay sesión activa, mandar al login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../includes/conexion.php';

// Solo aceptar peticiones POST desde el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario    = $_SESSION['id_usuario'];
    $id_habito     = $_POST['id_habito'] ?? null;
    $nombre_habito = trim($_POST['nombre_habito'] ?? '');
    $descripcion   = trim($_POST['descripcion'] ?? '');
    $id_categoria  = $_POST['id_categoria'] ?? null;
    $id_frecuencia = $_POST['id_frecuencia'] ?? null;
    $estatus       = $_POST['estatus'] ?? 'activo';

    // Validar antes de guardar cambios
    if (!$id_habito || !$nombre_habito || !$id_categoria || !$id_frecuencia) {
        header("Location: editar_habitos.php?id=$id_habito&error=campos_obligatorios");
        exit;
    }

    try {
        // Verificar que el hábito sea al usuario que está logueado
        $stmt = $pdo->prepare("SELECT id_habito FROM habitos WHERE id_habito = ? AND id_usuario = ?");
        $stmt->execute([$id_habito, $id_usuario]);

        if (!$stmt->fetch()) {
            // Si no lo encuentra
            header("Location: index.php?error=notfound");
            exit;
        }

        // Actualizar hábito
        $stmt = $pdo->prepare("UPDATE habitos 
                               SET nombre_habito = ?, descripcion = ?, id_categoria = ?, id_frecuencia = ?, estatus = ? 
                               WHERE id_habito = ?");
        $stmt->execute([
            $nombre_habito,
            $descripcion,
            $id_categoria,
            $id_frecuencia,
            $estatus,
            $id_habito
        ]);

        // Regresar
        header("Location: index.php?updated=1");
        exit;

    } catch (PDOException $e) {
        echo "Error al actualizar el hábito: " . $e->getMessage();
        exit;
    }
} else {
    // Si entran aquí sin enviar POST, regresar
    header("Location: index.php");
    exit;
}
