-- Base de datos: `parcial_v2_db`
CREATE DATABASE IF NOT EXISTS `parcial_v2_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `parcial_v2_db`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paises_ref` (Referencia de Países)
--
CREATE TABLE `paises_ref` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pais_nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `paises_ref`
--
INSERT INTO `paises_ref` (`id`, `pais_nombre`) VALUES
(1, 'Panamá'),
(2, 'Costa Rica'),
(3, 'El Salvador'),
(4, 'Guatemala'),
(5, 'Honduras'),
(6, 'Nicaragua'),
(7, 'Otro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas_interes`
--
CREATE TABLE `areas_interes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `interes_nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `areas_interes`
--
INSERT INTO `areas_interes` (`id`, `interes_nombre`) VALUES
(1, 'Frontend Development'),
(2, 'Backend Development'),
(3, 'DevOps'),
(4, 'Data Science'),
(5, 'Mobile Development');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros`
--
CREATE TABLE `registros` (
  `id_registro` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_participante` varchar(100) NOT NULL,
  `apellido_participante` varchar(100) NOT NULL,
  `edad` int(3) NOT NULL,
  `genero` varchar(20) NOT NULL,
  `pais_id` int(11) NOT NULL,
  `nacionalidad` varchar(100) NOT NULL,
  `consulta` text DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_registro`),
  KEY `fk_pais_id` (`pais_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_intereses`
--
CREATE TABLE `registro_intereses` (
  `registro_id` int(11) NOT NULL,
  `interes_id` int(11) NOT NULL,
  PRIMARY KEY (`registro_id`,`interes_id`),
  KEY `fk_interes` (`interes_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Filtros para la tabla `registros`
--
ALTER TABLE `registros`
  ADD CONSTRAINT `fk_pais_id` FOREIGN KEY (`pais_id`) REFERENCES `paises_ref` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `registro_intereses`
--
ALTER TABLE `registro_intereses`
  ADD CONSTRAINT `fk_registro` FOREIGN KEY (`registro_id`) REFERENCES `registros` (`id_registro`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_interes` FOREIGN KEY (`interes_id`) REFERENCES `areas_interes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
