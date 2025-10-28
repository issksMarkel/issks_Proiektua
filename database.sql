CREATE DATABASE IF NOT EXISTS `database`;
USE `database`;

DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(100) NOT NULL,
    `nan` VARCHAR(20) NOT NULL UNIQUE,
    `telefono` VARCHAR(15) NOT NULL,
    `fecha_nacimiento` DATE NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `fecha_registro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO `usuarios` (`nombre`, `nan`, `telefono`, `fecha_nacimiento`, `email`, `password`) VALUES 
('Mikel Garcia', '12345678-Z', '612345678', '1990-05-15', 'mikel@ejemplo.com', '$2y$10$example_hash1'),
('Aitor Lopez', '87654321-X', '698765432', '1985-08-22', 'aitor@ejemplo.com', '$2y$10$example_hash2');
