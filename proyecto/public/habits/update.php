<?php
// Archivo: public/habits/update.php

session_start();

// Si no hay sesión activa, redirigir al login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../includes/db.php';

// Solo aceptar peticiones POST desde el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario    = $_SESSION['id_usuario'];
    $id_habito     = $_POST['id_habito'] ?? null;
    $nombre_habito = trim($_POST['nombre_habito'] ?? '');
    $descripcion   = trim($_POST['descripcion'] ?? '');
    $id_categoria  = $_POST['id_categoria'] ?? null;
    $id_frecuencia = $_POST['id_frecuencia'] ?? null;
    $estatus       = $_POST['estatus'] ?? 'activo'; // Valor por defecto

    // Validación básica antes de guardar los cambios
    if (!$id_habito || !$nombre_habito || !$id_categoria || !$id_frecuencia) {
        header("Location: edit.php?id=$id_habito&error=campos_obligatorios");
        exit;
    }

    try {
        // Verificar que el hábito le pertenezca al usuario que está logueado
        $stmt = $pdo->prepare("SELECT id_habito FROM habitos WHERE id_habito = ? AND id_usuario = ?");
        $stmt->execute([$id_habito, $id_usuario]);

        if (!$stmt->fetch()) {
            // Si no lo encuentra o no es suyo, regresarlo
            header("Location: index.php?error=notfound");
            exit;
        }

        // Todo bien, actualizar el hábito
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

        // Redirigir con mensaje de éxito
        header("Location: index.php?updated=1");
        exit;

    } catch (PDOException $e) {
        // Solo durante desarrollo
        echo "Error al actualizar el hábito: " . $e->getMessage();
        exit;
    }
} else {
    // Si alguien entra aquí sin enviar POST, redirigirlo
    header("Location: index.php");
    exit;
}
