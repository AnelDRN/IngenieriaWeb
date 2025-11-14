-- Base de datos: `parcial_v1_db`
CREATE DATABASE IF NOT EXISTS `parcial_v1_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `parcial_v1_db`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paises`
--
CREATE TABLE `paises` (
  `id_pais` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_pais` varchar(100) NOT NULL,
  PRIMARY KEY (`id_pais`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `paises`
--
INSERT INTO `paises` (`id_pais`, `nombre_pais`) VALUES
(1, 'Panamá'),
(2, 'Colombia'),
(3, 'México'),
(4, 'Argentina'),
(5, 'España'),
(6, 'Estados Unidos'),
(7, 'Otro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `temas_tecnologicos`
--
CREATE TABLE `temas_tecnologicos` (
  `id_tema` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_tema` varchar(100) NOT NULL,
  PRIMARY KEY (`id_tema`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `temas_tecnologicos`
--
INSERT INTO `temas_tecnologicos` (`id_tema`, `nombre_tema`) VALUES
(1, 'Desarrollo Web'),
(2, 'Inteligencia Artificial'),
(3, 'Bases de Datos'),
(4, 'Ciberseguridad'),
(5, 'Cloud Computing');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscriptores`
--
CREATE TABLE `inscriptores` (
  `id_inscriptor` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `edad` int(3) NOT NULL,
  `sexo` varchar(20) NOT NULL,
  `id_pais_residencia` int(11) NOT NULL,
  `nacionalidad` varchar(100) NOT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_inscriptor`),
  KEY `fk_pais_residencia` (`id_pais_residencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscriptor_temas`
--
CREATE TABLE `inscriptor_temas` (
  `id_inscriptor` int(11) NOT NULL,
  `id_tema` int(11) NOT NULL,
  PRIMARY KEY (`id_inscriptor`,`id_tema`),
  KEY `fk_tema` (`id_tema`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Filtros para la tabla `inscriptores`
--
ALTER TABLE `inscriptores`
  ADD CONSTRAINT `fk_pais_residencia` FOREIGN KEY (`id_pais_residencia`) REFERENCES `paises` (`id_pais`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `inscriptor_temas`
--
ALTER TABLE `inscriptor_temas`
  ADD CONSTRAINT `fk_inscriptor` FOREIGN KEY (`id_inscriptor`) REFERENCES `inscriptores` (`id_inscriptor`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tema` FOREIGN KEY (`id_tema`) REFERENCES `temas_tecnologicos` (`id_tema`) ON DELETE CASCADE ON UPDATE CASCADE;
