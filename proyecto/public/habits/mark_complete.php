<?php
// Archivo: public/habits/mark_complete.php

session_start();

// Si el usuario no ha iniciado sesión, mandar al login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../includes/db.php';

$id_usuario = $_SESSION['id_usuario'];
$id_habito = $_GET['id'] ?? null;
$fecha_hoy = date('Y-m-d');

// Si no se proporcionó ID, redirigir a la lista
if (!$id_habito) {
    header("Location: index.php");
    exit;
}

try {
    // Primero revisar que el hábito pertenezca al usuario y esté activo
    $stmt = $pdo->prepare("SELECT id_habito FROM habitos WHERE id_habito = ? AND id_usuario = ? AND estatus = 'activo'");
    $stmt->execute([$id_habito, $id_usuario]);
    $habito = $stmt->fetch();

    // Si no existe o no le pertenece, redirigir
    if (!$habito) {
        header("Location: index.php?error=notfound");
        exit;
    }

    // Revisar si ya marcó este hábito como completado hoy
    $stmt = $pdo->prepare("SELECT id_historial FROM historial_habito WHERE id_habito = ? AND fecha = ?");
    $stmt->execute([$id_habito, $fecha_hoy]);
    $existe = $stmt->fetch();

    // Si ya existe una entrada para hoy, redirigir y evitar duplicados
    if ($existe) {
        header("Location: index.php?already_done=1");
        exit;
    }

    // Si no existe, entonces insertar una entrada marcándolo como completado
    $stmt = $pdo->prepare("INSERT INTO historial_habito (fecha, completado, id_habito) VALUES (?, 1, ?)");
    $stmt->execute([$fecha_hoy, $id_habito]);

    // Redirigir con mensaje de éxito
    header("Location: index.php?done=1");
    exit;

} catch (PDOException $e) {
    // En caso de error con la BD
    echo "Error al marcar hábito como completado: " . $e->getMessage();
    exit;
}
