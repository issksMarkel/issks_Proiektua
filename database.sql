CREATE DATABASE IF NOT EXISTS `database`;
USE `database`;

<<<<<<< HEAD
DROP TABLE IF EXISTS `erabiltzaile_pokemon`;
DROP TABLE IF EXISTS `erabiltzaile`;
=======
DROP TABLE IF EXISTS `usuarios`;
>>>>>>> temp-branch

CREATE TABLE `usuarios` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(100) NOT NULL,
    `nan` VARCHAR(20) NOT NULL UNIQUE,
    `telefono` VARCHAR(15) NOT NULL,
    `fecha_nacimiento` DATE NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
<<<<<<< HEAD
    `pasahitza` VARCHAR(255) NOT NULL,
    `erregistro_data` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO `erabiltzaile` (`izena`, `nan`, `telefono`, `jaiotze_data`, `email`, `pasahitza`) VALUES 
('Mikel Garcia', '12345678-Z', '612345678', '1990-05-15', 'mikel@ejemplo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Aitor Lopez', '87654321-X', '698765432', '1985-08-22', 'aitor@ejemplo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
=======
    `password` VARCHAR(255) NOT NULL,
    `fecha_registro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO `usuarios` (`nombre`, `nan`, `telefono`, `fecha_nacimiento`, `email`, `password`) VALUES 
('Mikel Garcia', '12345678-Z', '612345678', '1990-05-15', 'mikel@ejemplo.com', '$2y$10$example_hash1'),
('Aitor Lopez', '87654321-X', '698765432', '1985-08-22', 'aitor@ejemplo.com', '$2y$10$example_hash2');
>>>>>>> temp-branch

DROP TABLE IF EXISTS `elementuak`;

CREATE TABLE `elementuak` (
    `izena` VARCHAR(100) NOT NULL PRIMARY KEY,
    `mota` ENUM('Arrunta', 'Sua', 'Ura', 'Elektrikoa', 'Belarra', 'Izotza', 'Borroka', 'Pozoina', 'Lurra', 'Hegalaria', 'Psikikoa', 'Intsektua', 'Harria', 'Mamua', 'Dragoia') NOT NULL,
    `bizitza` INT NOT NULL,
    `erasoa` INT NOT NULL,
    `defentsa` INT NOT NULL
);

INSERT INTO `elementuak` (`izena`, `mota`, `bizitza`, `erasoa`, `defentsa`) VALUES 
('Pikachu', 'Elektrikoa', 35, 55, 40),
('Charmander', 'Sua', 39, 52, 43),
('Squirtle', 'Ura', 44, 48, 65),
('Bulbasaur', 'Belarra', 45, 49, 49);

<<<<<<< HEAD
CREATE TABLE `erabiltzaile_pokemon` (
=======
DROP TABLE IF EXISTS `usuario_elementuak`;

CREATE TABLE `usuario_elementuak` (
>>>>>>> temp-branch
    `usuario_id` INT NOT NULL,
    `elementu_izena` VARCHAR(100) NOT NULL,
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`elementu_izena`) REFERENCES `elementuak`(`izena`) ON DELETE CASCADE,
    UNIQUE KEY `unique_usuario_elementu` (`usuario_id`, `elementu_izena`)
);
