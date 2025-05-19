-- Archivo: sql/bebetter_db.sql

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS bebetter_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bebetter_db;

-- Tabla de roles
CREATE TABLE roles (
    id_rol TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(30) NOT NULL
) ENGINE=InnoDB;

INSERT INTO roles (nombre_rol) VALUES ('Administrador'), ('Usuario');

-- Tabla de usuarios
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    a_paterno VARCHAR(50),
    a_materno VARCHAR(50),
    correo VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fecha_registro DATE NOT NULL,
    id_rol TINYINT UNSIGNED NOT NULL DEFAULT 2,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (id_rol) REFERENCES roles(id_rol)
) ENGINE=InnoDB;

-- Usuario administrador pre-registrado
INSERT INTO usuarios (nombre, a_paterno, a_materno, correo, password, fecha_registro, id_rol)
VALUES (
  'Admin',
  'Principal',
  'Sistema',
  'admin@bebetter.com',
  '$2y$10$fKC00YWss5VX6c4fu4HMu.i9nrT4Q0F7HQUYZm1qu7vyJX4jPCT4q',
  CURDATE(),
  1
);

-- Tabla de categorías
CREATE TABLE categorias (
    id_categoria TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

INSERT INTO categorias (nombre_categoria) VALUES
('Salud'),
('Ejercicio'),
('Estudio'),
('Lectura'),
('Meditación'),
('Trabajo'),
('Alimentación'),
('Higiene personal');

-- Tabla de frecuencias
CREATE TABLE frecuencias (
    id_frecuencia TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

INSERT INTO frecuencias (descripcion) VALUES
('Diaria'),
('Semanal'),
('Personalizada');

-- Tabla de hábitos
CREATE TABLE habitos (
    id_habito INT AUTO_INCREMENT PRIMARY KEY,
    nombre_habito VARCHAR(100) NOT NULL,
    descripcion TEXT,
    estatus ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_registro DATE NOT NULL,
    id_usuario INT NOT NULL,
    id_categoria TINYINT UNSIGNED NOT NULL,
    id_frecuencia TINYINT UNSIGNED NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria),
    FOREIGN KEY (id_frecuencia) REFERENCES frecuencias(id_frecuencia)
) ENGINE=InnoDB;

-- Tabla de historial de hábitos
CREATE TABLE historial_habito (
    id_historial INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    completado BOOLEAN NOT NULL DEFAULT FALSE,
    id_habito INT NOT NULL,
    FOREIGN KEY (id_habito) REFERENCES habitos(id_habito)
) ENGINE=InnoDB;

-- Tabla de metas
CREATE TABLE metas (
    id_meta INT AUTO_INCREMENT PRIMARY KEY,
    descripcion TEXT,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    cantidad_objetivo INT NOT NULL,
    id_usuario INT NOT NULL,
    id_habito INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_habito) REFERENCES habitos(id_habito)
) ENGINE=InnoDB;
