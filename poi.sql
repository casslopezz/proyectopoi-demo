-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-04-2025 a las 00:39:36
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `poi`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archivo_compartido`
--

CREATE TABLE `archivo_compartido` (
  `id_archivo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_mensaje` int(11) NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `tipo` enum('Imagen','Audio','Documento','Otro') NOT NULL,
  `url` text NOT NULL,
  `fecha_subida` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo`
--

CREATE TABLE `grupo` (
  `id_grupo` int(11) NOT NULL,
  `nombre_grupo` varchar(100) NOT NULL,
  `id_usuario_creador` int(11) NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensaje`
--

CREATE TABLE `mensaje` (
  `id_mensaje` int(11) NOT NULL,
  `id_emisor` int(11) NOT NULL,
  `id_grupo` int(11) DEFAULT NULL,
  `id_receptor` int(11) DEFAULT NULL,
  `contenido` text NOT NULL,
  `fecha_envio` datetime DEFAULT current_timestamp(),
  `tipo` enum('Texto','Imagen','Audio','Video') NOT NULL,
  `cifrado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensaje`
--

INSERT INTO `mensaje` (`id_mensaje`, `id_emisor`, `id_grupo`, `id_receptor`, `contenido`, `fecha_envio`, `tipo`, `cifrado`) VALUES
(1, 1, NULL, 2, 'Hola', '2025-04-05 13:31:14', 'Texto', 0),
(2, 4, NULL, 3, 'Holia', '2025-04-07 12:08:12', 'Texto', 0),
(3, 4, NULL, 3, 'Holi', '2025-04-07 12:08:17', 'Texto', 0),
(4, 3, NULL, 4, 'HOLIIIIIIIIIIIIIII', '2025-04-07 12:08:56', 'Texto', 0),
(5, 3, NULL, 4, 'como estas?', '2025-04-07 12:22:02', 'Texto', 0),
(6, 3, NULL, 4, 'todo bien?', '2025-04-07 12:22:10', 'Texto', 0),
(7, 4, NULL, 3, 'muy bien y tu?', '2025-04-07 12:22:58', 'Texto', 0),
(8, 4, NULL, 3, 'hola', '2025-04-08 14:19:04', 'Texto', 0),
(9, 3, NULL, 1, 'hola tilin', '2025-04-08 14:23:12', 'Texto', 0),
(10, 3, NULL, 4, 'hola tilin', '2025-04-08 14:34:12', 'Texto', 0),
(11, 4, NULL, 3, 'holi', '2025-04-08 14:34:26', 'Texto', 0),
(12, 4, NULL, 3, 'holi tilin', '2025-04-08 14:34:56', 'Texto', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recompensa`
--

CREATE TABLE `recompensa` (
  `id_recompensa` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `criterio_obtencion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recompensa_usuario`
--

CREATE TABLE `recompensa_usuario` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_recompensa` int(11) NOT NULL,
  `fecha_obtenida` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarea`
--

CREATE TABLE `tarea` (
  `id_tarea` int(11) NOT NULL,
  `id_grupo` int(11) NOT NULL,
  `id_usuario_asignado` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `estado` enum('Pendiente','En Progreso','Completada') NOT NULL DEFAULT 'Pendiente',
  `fecha_limite` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacion_compartida`
--

CREATE TABLE `ubicacion_compartida` (
  `id_ubicacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_mensaje` int(11) NOT NULL,
  `latitud` decimal(10,8) NOT NULL,
  `longitud` decimal(11,8) NOT NULL,
  `fecha_hora` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `estado_conexion` tinyint(1) NOT NULL DEFAULT 0,
  `ultima_conexion` datetime DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `correo`, `contraseña`, `estado_conexion`, `ultima_conexion`, `foto`) VALUES
(1, 'Angel Omar Garcia Niño', 'agomargn@gmail.com', '$2y$10$KnrGiw/FrrNrH9/TWZ1GUOYBxbDOp9FWCuT7F2A514qvPd4MR40/2', 0, '2025-04-05 13:58:07', 'uploads/1743828751_Fondo (14).png'),
(2, 'Karen Maribel Garcia Niño', 'omar.arisen1709@gmail.com', '$2y$10$gAKsyBiKxZrHR4KSTz9ZmelOiWdM712eBd/FPVMC0ohrGOHAnNq9S', 1, '2025-04-05 13:58:27', NULL),
(3, 'Carolina Ugalde', 'carougalde11@gmail.com', '$2y$10$5Ks7wK/cLH2v27YMFa5sg.5xy9hYSVs0kSaSsU1VcdlBjj/VkewI2', 1, '2025-04-08 14:34:00', 'uploads/1744045090_CARO.png'),
(4, 'Valeria Ugalde', 'valeugalde15@gmail.com', '$2y$10$U84xokX34JFtRz92AM5of.Oc8t9Yz/hzjNN43Okyixn7GctqW51Xa', 1, '2025-04-08 14:33:06', 'uploads/1744049265_VALE.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_grupo`
--

CREATE TABLE `usuario_grupo` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_grupo` int(11) NOT NULL,
  `fecha_union` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `videollamada`
--

CREATE TABLE `videollamada` (
  `id_videollamada` int(11) NOT NULL,
  `id_usuario1` int(11) NOT NULL,
  `id_usuario2` int(11) NOT NULL,
  `fecha_inicio` datetime DEFAULT current_timestamp(),
  `fecha_fin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `archivo_compartido`
--
ALTER TABLE `archivo_compartido`
  ADD PRIMARY KEY (`id_archivo`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_mensaje` (`id_mensaje`);

--
-- Indices de la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD PRIMARY KEY (`id_grupo`),
  ADD KEY `id_usuario_creador` (`id_usuario_creador`);

--
-- Indices de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `id_emisor` (`id_emisor`),
  ADD KEY `id_grupo` (`id_grupo`),
  ADD KEY `id_receptor` (`id_receptor`);

--
-- Indices de la tabla `recompensa`
--
ALTER TABLE `recompensa`
  ADD PRIMARY KEY (`id_recompensa`);

--
-- Indices de la tabla `recompensa_usuario`
--
ALTER TABLE `recompensa_usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_recompensa` (`id_recompensa`);

--
-- Indices de la tabla `tarea`
--
ALTER TABLE `tarea`
  ADD PRIMARY KEY (`id_tarea`),
  ADD KEY `id_grupo` (`id_grupo`),
  ADD KEY `id_usuario_asignado` (`id_usuario_asignado`);

--
-- Indices de la tabla `ubicacion_compartida`
--
ALTER TABLE `ubicacion_compartida`
  ADD PRIMARY KEY (`id_ubicacion`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_mensaje` (`id_mensaje`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `usuario_grupo`
--
ALTER TABLE `usuario_grupo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_grupo` (`id_grupo`);

--
-- Indices de la tabla `videollamada`
--
ALTER TABLE `videollamada`
  ADD PRIMARY KEY (`id_videollamada`),
  ADD KEY `id_usuario1` (`id_usuario1`),
  ADD KEY `id_usuario2` (`id_usuario2`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `archivo_compartido`
--
ALTER TABLE `archivo_compartido`
  MODIFY `id_archivo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `grupo`
--
ALTER TABLE `grupo`
  MODIFY `id_grupo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `recompensa`
--
ALTER TABLE `recompensa`
  MODIFY `id_recompensa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recompensa_usuario`
--
ALTER TABLE `recompensa_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tarea`
--
ALTER TABLE `tarea`
  MODIFY `id_tarea` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ubicacion_compartida`
--
ALTER TABLE `ubicacion_compartida`
  MODIFY `id_ubicacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuario_grupo`
--
ALTER TABLE `usuario_grupo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `videollamada`
--
ALTER TABLE `videollamada`
  MODIFY `id_videollamada` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `archivo_compartido`
--
ALTER TABLE `archivo_compartido`
  ADD CONSTRAINT `archivo_compartido_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `archivo_compartido_ibfk_2` FOREIGN KEY (`id_mensaje`) REFERENCES `mensaje` (`id_mensaje`) ON DELETE CASCADE;

--
-- Filtros para la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD CONSTRAINT `grupo_ibfk_1` FOREIGN KEY (`id_usuario_creador`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD CONSTRAINT `mensaje_ibfk_1` FOREIGN KEY (`id_emisor`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `mensaje_ibfk_2` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`) ON DELETE SET NULL,
  ADD CONSTRAINT `mensaje_ibfk_3` FOREIGN KEY (`id_receptor`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `recompensa_usuario`
--
ALTER TABLE `recompensa_usuario`
  ADD CONSTRAINT `recompensa_usuario_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `recompensa_usuario_ibfk_2` FOREIGN KEY (`id_recompensa`) REFERENCES `recompensa` (`id_recompensa`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tarea`
--
ALTER TABLE `tarea`
  ADD CONSTRAINT `tarea_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`) ON DELETE CASCADE,
  ADD CONSTRAINT `tarea_ibfk_2` FOREIGN KEY (`id_usuario_asignado`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ubicacion_compartida`
--
ALTER TABLE `ubicacion_compartida`
  ADD CONSTRAINT `ubicacion_compartida_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `ubicacion_compartida_ibfk_2` FOREIGN KEY (`id_mensaje`) REFERENCES `mensaje` (`id_mensaje`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuario_grupo`
--
ALTER TABLE `usuario_grupo`
  ADD CONSTRAINT `usuario_grupo_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuario_grupo_ibfk_2` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`) ON DELETE CASCADE;

--
-- Filtros para la tabla `videollamada`
--
ALTER TABLE `videollamada`
  ADD CONSTRAINT `videollamada_ibfk_1` FOREIGN KEY (`id_usuario1`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `videollamada_ibfk_2` FOREIGN KEY (`id_usuario2`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

USE poi;

ALTER TABLE archivo_compartido MODIFY id_archivo INT NOT NULL AUTO_INCREMENT;
ALTER TABLE grupo MODIFY id_grupo INT NOT NULL AUTO_INCREMENT;
ALTER TABLE mensaje MODIFY id_mensaje INT NOT NULL AUTO_INCREMENT;
ALTER TABLE recompensa MODIFY id_recompensa INT NOT NULL AUTO_INCREMENT;
ALTER TABLE recompensa_usuario MODIFY id INT NOT NULL AUTO_INCREMENT;
ALTER TABLE tarea MODIFY id_tarea INT NOT NULL AUTO_INCREMENT;
ALTER TABLE ubicacion_compartida MODIFY id_ubicacion INT NOT NULL AUTO_INCREMENT;
ALTER TABLE usuario MODIFY id_usuario INT NOT NULL AUTO_INCREMENT;
ALTER TABLE usuario_grupo MODIFY id INT NOT NULL AUTO_INCREMENT;
ALTER TABLE videollamada MODIFY id_videollamada INT NOT NULL AUTO_INCREMENT;

-- archivo_compartido
ALTER TABLE archivo_compartido
  ADD CONSTRAINT fk_archivo_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
  ADD CONSTRAINT fk_archivo_mensaje FOREIGN KEY (id_mensaje) REFERENCES mensaje(id_mensaje);

-- grupo
ALTER TABLE grupo
  ADD CONSTRAINT fk_grupo_usuario FOREIGN KEY (id_usuario_creador) REFERENCES usuario(id_usuario);

-- mensaje
ALTER TABLE mensaje
  ADD CONSTRAINT fk_mensaje_emisor FOREIGN KEY (id_emisor) REFERENCES usuario(id_usuario),
  ADD CONSTRAINT fk_mensaje_receptor FOREIGN KEY (id_receptor) REFERENCES usuario(id_usuario),
  ADD CONSTRAINT fk_mensaje_grupo FOREIGN KEY (id_grupo) REFERENCES grupo(id_grupo);

-- recompensa_usuario
ALTER TABLE recompensa_usuario
  ADD CONSTRAINT fk_recompensa_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
  ADD CONSTRAINT fk_recompensa FOREIGN KEY (id_recompensa) REFERENCES recompensa(id_recompensa);

-- tarea
ALTER TABLE tarea
  ADD CONSTRAINT fk_tarea_grupo FOREIGN KEY (id_grupo) REFERENCES grupo(id_grupo),
  ADD CONSTRAINT fk_tarea_usuario FOREIGN KEY (id_usuario_asignado) REFERENCES usuario(id_usuario);

-- ubicacion_compartida
ALTER TABLE ubicacion_compartida
  ADD CONSTRAINT fk_ubicacion_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
  ADD CONSTRAINT fk_ubicacion_mensaje FOREIGN KEY (id_mensaje) REFERENCES mensaje(id_mensaje);

-- usuario_grupo
ALTER TABLE usuario_grupo
  ADD CONSTRAINT fk_usuario_grupo_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
  ADD CONSTRAINT fk_usuario_grupo_grupo FOREIGN KEY (id_grupo) REFERENCES grupo(id_grupo);

-- videollamada
ALTER TABLE videollamada
  ADD CONSTRAINT fk_video_usuario1 FOREIGN KEY (id_usuario1) REFERENCES usuario(id_usuario),
  ADD CONSTRAINT fk_video_usuario2 FOREIGN KEY (id_usuario2) REFERENCES usuario(id_usuario);

ALTER TABLE grupo 
ADD CONSTRAINT fk_grupo_usuario 
FOREIGN KEY (id_usuario_creador) REFERENCES usuario(id_usuario)
ON DELETE CASCADE;

ALTER TABLE usuario_grupo 
ADD CONSTRAINT fk_ug_usuario 
FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
ON DELETE CASCADE,
ADD CONSTRAINT fk_ug_grupo 
FOREIGN KEY (id_grupo) REFERENCES grupo(id_grupo)
ON DELETE CASCADE;

USE poi;
-- Agregar el campo 'rol' a la tabla 'Usuario'
ALTER TABLE Usuario ADD rol ENUM('profesor', 'alumno') NOT NULL DEFAULT 'alumno';

-- Agregar los campos 'calificacion' y 'id_usuario_profesor' a la tabla 'tarea'
ALTER TABLE tarea ADD calificacion INT DEFAULT NULL;
ALTER TABLE tarea ADD id_usuario_profesor INT DEFAULT NULL;

-- Agregar la clave foránea para 'id_usuario_profesor'
ALTER TABLE tarea ADD CONSTRAINT fk_tarea_profesor FOREIGN KEY (id_usuario_profesor) REFERENCES usuario(id_usuario);

USE poi;

ALTER TABLE grupo ADD COLUMN id_usuario_creado INT;
ALTER TABLE grupo ADD FOREIGN KEY (id_usuario_creado) REFERENCES Usuario(id_usuario);