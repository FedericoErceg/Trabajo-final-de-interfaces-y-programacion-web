-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-11-2026 a las 13:33:59
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
-- Base de datos: `hotel_reservas`
--

-- --------------------------------------------------------
-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS hotel_reservas CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE hotel_reservas;

--
-- Estructura de tabla para la tabla `categorias_habitacion`
--

CREATE TABLE `categorias_habitacion` (
  `id_categoria` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `capacidad` int(11) NOT NULL CHECK (`capacidad` > 0),
  `precio_noche` decimal(10,2) NOT NULL CHECK (`precio_noche` >= 0),
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias_habitacion`
--

INSERT INTO `categorias_habitacion` (`id_categoria`, `tipo`, `capacidad`, `precio_noche`, `descripcion`) VALUES
(1, 'Single', 1, 35000.00, 'Habitación individual con cama simple'),
(2, 'Doble Simple', 2, 50000.00, 'Habitación doble estándar'),
(3, 'Doble Superior', 2, 65000.00, 'Habitación doble con amenities superiores'),
(4, 'Doble Suite', 2, 85000.00, 'Suite con cama doble'),
(5, 'Triple Simple', 3, 70000.00, 'Habitación triple estándar'),
(6, 'Triple Superior', 3, 90000.00, 'Habitación triple con amenities superiores'),
(7, 'Triple Suite', 3, 110000.00, 'Suite con tres plazas'),
(8, 'Cuádruple', 4, 120000.00, 'Habitación para cuatro personas'),
(9, 'Departamento', 5, 150000.00, 'Departamento completo para cinco personas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitaciones`
--

CREATE TABLE `habitaciones` (
  `id_habitacion` int(11) NOT NULL,
  `id_categoria_habitacion` int(11) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `piso` int(11) NOT NULL,
  `estado` enum('disponible','mantenimiento') NOT NULL DEFAULT 'disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `habitaciones`
--

INSERT INTO `habitaciones` (`id_habitacion`, `id_categoria_habitacion`, `numero`, `piso`, `estado`) VALUES
(5, 9, '205', 2, 'disponible'),
(6, 2, '02', 0, 'disponible'),
(8, 2, '03', 0, 'disponible'),
(11, 4, '104', 1, 'disponible'),
(12, 8, '103', 1, 'disponible'),
(13, 5, '102', 1, 'disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `id_habitacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `telefono` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `domicilio` varchar(150) NOT NULL,
  `provincia` varchar(50) NOT NULL,
  `pais` varchar(50) NOT NULL DEFAULT 'Argentina',
  `fecha_entrada` date NOT NULL,
  `fecha_salida` date NOT NULL,
  `observacion` text DEFAULT NULL,
  `total` decimal(10,2) NOT NULL CHECK (`total` >= 0),
  `estado` enum('pendiente','confirmada','cancelada','finalizada') NOT NULL DEFAULT 'pendiente',
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `id_habitacion`, `id_usuario`, `dni`, `nombre`, `apellido`, `telefono`, `email`, `domicilio`, `provincia`, `pais`, `fecha_entrada`, `fecha_salida`, `observacion`, `total`, `estado`, `fecha_creacion`) VALUES
(7, 8, 1, '56423132', 'Fernando', 'Martinez', '1165008121', 'fernandomartinez@gmail.com', 'Rawson 1714', 'Buenos Aires', 'Argentina', '2026-11-20', '2026-11-21', 'pasajero particular', 50000.00, 'confirmada', '2026-11-05 20:15:30'),
(8, 11, 1, '468654623', 'mario', 'benedeti', '152465321', 'asdgasdf@gmail.com', 'asdfasdfg', 'Buenos Aires', 'Argentina', '2026-11-12', '2026-11-19', 'particular', 595000.00, 'confirmada', '2026-11-12 15:00:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `firebase_uid` varchar(128) DEFAULT NULL,
  `rol` enum('admin','recepcionista') NOT NULL DEFAULT 'recepcionista',
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `email`, `password_hash`, `firebase_uid`, `rol`, `fecha_creacion`) VALUES
(1, 'Admin', 'Hotel', 'admin@hotel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'admin', '2026-11-05 20:14:22');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias_habitacion`
--
ALTER TABLE `categorias_habitacion`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `tipo` (`tipo`);

--
-- Indices de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD PRIMARY KEY (`id_habitacion`),
  ADD UNIQUE KEY `numero` (`numero`),
  ADD KEY `fk_habitacion_categoria` (`id_categoria_habitacion`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `fk_reserva_habitacion` (`id_habitacion`),
  ADD KEY `fk_reserva_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `firebase_uid` (`firebase_uid`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias_habitacion`
--
ALTER TABLE `categorias_habitacion`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  MODIFY `id_habitacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD CONSTRAINT `fk_habitacion_categoria` FOREIGN KEY (`id_categoria_habitacion`) REFERENCES `categorias_habitacion` (`id_categoria`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `fk_reserva_habitacion` FOREIGN KEY (`id_habitacion`) REFERENCES `habitaciones` (`id_habitacion`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reserva_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
