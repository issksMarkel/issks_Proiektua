-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS `database`;
USE `database`;

-- Eliminar tabla si existe
DROP TABLE IF EXISTS `usuarios`;

-- Crear tabla usuarios con la estructura correcta
CREATE TABLE `usuarios` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(100) NOT NULL,
    `nan` VARCHAR(20) NOT NULL UNIQUE,
    `telefono` VARCHAR(15) NOT NULL,
    `fecha_nacimiento` DATE NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `fecha_registro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar datos de ejemplo
INSERT INTO `usuarios` (`nombre`, `nan`, `telefono`, `fecha_nacimiento`, `email`) VALUES 
('Mikel Garcia', '12345678-Z', '612345678', '1990-05-15', 'mikel@ejemplo.com'),
('Aitor Lopez', '87654321-X', '698765432', '1985-08-22', 'aitor@ejemplo.com');
