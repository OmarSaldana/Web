<?php
// Archivo: publico/habitos/almacenar_habitos.php

session_start();
require_once '../../includes/conexion.php';

// Si el usuario no está logueado, mandar al login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

// Verificar que la petición venga del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario     = $_SESSION['id_usuario'];
    $nombre_habito  = trim($_POST['nombre_habito'] ?? '');
    $descripcion    = trim($_POST['descripcion'] ?? '');
    $id_categoria   = $_POST['id_categoria'] ?? '';
    $id_frecuencia  = $_POST['id_frecuencia'] ?? '';
    $estatus        = 'activo'; // Todos los hábitos se registran como activos por defecto
    $fecha_registro = date('Y-m-d');

    // Validaciones para NO campos vacíos
    if (
        empty($nombre_habito) || 
        !is_numeric($id_categoria) || 
        !is_numeric($id_frecuencia)
    ) {
        // Si algo falla
        header("Location: crear_habitos.php?error=campos_obligatorios");
        exit;
    }

    try {
        // Preparar e insertar el nuevo hábito en la base de datos
        $stmt = $pdo->prepare("INSERT INTO habitos 
            (nombre_habito, descripcion, estatus, fecha_registro, id_usuario, id_categoria, id_frecuencia)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $nombre_habito,
            $descripcion,
            $estatus,
            $fecha_registro,
            $id_usuario,
            $id_categoria,
            $id_frecuencia
        ]);

        // si sale bien
        header("Location: index.php?success=1");
        exit;

    } catch (PDOException $e) {
        echo "Error al guardar el hábito: " . $e->getMessage();
        exit;
    }
} else {
    // Si entran sin mandar formulario
    header("Location: crear_habitos.php");
    exit;
}
