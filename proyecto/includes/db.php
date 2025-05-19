<?php
// Archivo: includes/db.php

// Bloqueo de acceso directo
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    http_response_code(403);
    exit('Acceso prohibido');
}

// Datos de conexión a la base de datos local
$host = 'sql113.infinityfree.com';
$db   = 'if0_39018320_bebetter_db'; // Nombre de la base de datos de infinityfree
$user = 'if0_39018320';        // Usuario por defecto de infinityfree
$pass = 'FdZD4tgzmWIzOl';    // Contraseña de infinityfree
$charset = 'utf8mb4';  // Codificación recomendada para soportar caracteres especiales y emojis

// DSN para la conexión PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Configuraciones de PDO para mejor manejo de errores y resultados
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Esto lanza excepciones si hay errores SQL
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Obtendf resultados como arrays asociativos
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desactivar emulación para que se usen consultas preparadas reales
];

try {
    // Crear instancia de PDO para usarla en todo el sistema
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // En caso de fallo
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>
