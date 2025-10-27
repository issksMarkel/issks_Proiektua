-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS `database`;
USE `database`;

-- Eliminar tabla si existe
DROP TABLE IF EXISTS `usuarios`;

-- Crear tabla usuarios
CREATE TABLE `usuarios` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `fecha_registro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar datos de ejemplo
INSERT INTO `usuarios` (`nombre`, `email`) VALUES 
('mikel', 'mikel@ejemplo.com'),
('aitor', 'aitor@ejemplo.com');
