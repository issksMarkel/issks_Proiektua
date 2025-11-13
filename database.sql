CREATE DATABASE IF NOT EXISTS `database`;
USE `database`;

DROP TABLE IF EXISTS `erabiltzaile_pokemon`;
DROP TABLE IF EXISTS `erabiltzaile`;

CREATE TABLE `erabiltzaile` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `izena` VARCHAR(100) NOT NULL,
    `nan` VARCHAR(20) NOT NULL UNIQUE,
    `telefono` VARCHAR(15) NOT NULL,
    `jaiotze_data` DATE NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `pasahitza` VARCHAR(255) NOT NULL,
    `erregistro_data` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO `erabiltzaile` (`izena`, `nan`, `telefono`, `jaiotze_data`, `email`, `pasahitza`) VALUES 
('Mikel Garcia', '12345678-Z', '612345678', '1990-05-15', 'mikel@ejemplo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Aitor Lopez', '87654321-X', '698765432', '1985-08-22', 'aitor@ejemplo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

DROP TABLE IF EXISTS `pokemon`;

CREATE TABLE `pokemon` (
    `izena` VARCHAR(100) NOT NULL PRIMARY KEY,
    `mota` ENUM('Arrunta', 'Sua', 'Ura', 'Elektrikoa', 'Belarra', 'Izotza', 'Borroka', 'Pozoina', 'Lurra', 'Hegalaria', 'Psikikoa', 'Intsektua', 'Harria', 'Mamua', 'Dragoia') NOT NULL,
    `bizitza` INT NOT NULL,
    `erasoa` INT NOT NULL,
    `defentsa` INT NOT NULL
);

INSERT INTO `pokemon` (`izena`, `mota`, `bizitza`, `erasoa`, `defentsa`) VALUES 
('Pikachu', 'Elektrikoa', 35, 55, 40),
('Charmander', 'Sua', 39, 52, 43),
('Squirtle', 'Ura', 44, 48, 65),
('Bulbasaur', 'Belarra', 45, 49, 49);

CREATE TABLE `erabiltzaile_pokemon` (
    `usuario_id` INT NOT NULL,
    `elementu_izena` VARCHAR(100) NOT NULL,
    FOREIGN KEY (`usuario_id`) REFERENCES `erabiltzaile`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`elementu_izena`) REFERENCES `pokemon`(`izena`) ON DELETE CASCADE,
    UNIQUE KEY `unique_usuario_elementu` (`usuario_id`, `elementu_izena`)
);