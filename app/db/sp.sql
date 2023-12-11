-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 11-12-2023 a las 15:57:57
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ajustes`
--

CREATE TABLE `ajustes` (
  `id` int(11) NOT NULL,
  `idReserva` int(11) DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ajustes`
--

INSERT INTO `ajustes` (`id`, `idReserva`, `motivo`) VALUES
(1, 3, 'Cambio de habitacion'),
(2, 6, 'Cambio de habitacion'),
(3, 3, 'Cambio de habitacion');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `nro_cliente` varchar(6) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `tipoDocumento` varchar(50) DEFAULT NULL,
  `numeroDocumento` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `tipoCliente` varchar(10) DEFAULT NULL,
  `pais` varchar(255) DEFAULT NULL,
  `ciudad` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `modalidadPago` varchar(20) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `nombreArchivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`nro_cliente`, `nombre`, `tipoDocumento`, `numeroDocumento`, `email`, `tipoCliente`, `pais`, `ciudad`, `telefono`, `modalidadPago`, `estado`, `nombreArchivo`) VALUES
('000001', 'test2', 'dni', '42452555', 'abc@gmail.com', 'indi', 'Argentina', 'Madrid', '123567547', 'efectivo', 'Activo', '000001in.png'),
('000002', 'test1', 'pasaporte', '42452556', 'abc@gmail.com', 'corpo', 'Polonia', 'Madrid', '123567547', 'tarjeta', 'Eliminado', '000002co.png'),
('000003', 'persival', 'pasaporte', '42452999', 'abc@gmail.com', 'corpo', 'Argentina', 'Madrid', '123567547', 'tarjeta', 'Activo', '000003co.png'),
('000004', 'test4', 'dni', '42452558', 'abc@gmail.com', 'corpo', 'Polonia', 'Madrid', '123567547', 'efectivo', 'Activo', '000004co.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `tipoCliente` varchar(10) DEFAULT NULL,
  `nro_cliente` varchar(6) DEFAULT NULL,
  `fechaEntrada` varchar(10) DEFAULT NULL,
  `fechaSalida` varchar(10) DEFAULT NULL,
  `tipoHabitacion` varchar(20) DEFAULT NULL,
  `importeTotal` decimal(10,2) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `motivoAjuste` varchar(255) DEFAULT NULL,
  `modalidadPago` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `tipoCliente`, `nro_cliente`, `fechaEntrada`, `fechaSalida`, `tipoHabitacion`, `importeTotal`, `estado`, `motivoAjuste`, `modalidadPago`) VALUES
(1, 'indi', '000001', '20/12/2008', '15/03/2008', 'individual', 250.00, 'Cancelado', NULL, 'efectivo'),
(3, 'indi', '000001', '20/12/2008', '15/03/2008', 'suite', 1500.00, 'Activo', 'Cambio de habitacion', 'efectivo'),
(4, 'corpo', '000003', '20/12/2007', '15/03/2008', 'doble', 1230.00, 'Activo', NULL, 'efectivo'),
(5, 'corpo', '000003', '20/12/2007', '15/03/2008', 'doble', 1000.00, 'Cancelado', NULL, 'efectivo'),
(6, 'corpo', '000003', '20/12/2009', '15/03/2008', 'doble', 1000.00, 'Cancelado', 'Cambio de habitacion', 'efectivo'),
(7, 'corpo', '000003', '20/12/2009', '15/03/2008', 'doble', 1000.00, 'Activo', NULL, 'tarjeta');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ajustes`
--
ALTER TABLE `ajustes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idReserva` (`idReserva`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`nro_cliente`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nro_cliente` (`nro_cliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ajustes`
--
ALTER TABLE `ajustes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ajustes`
--
ALTER TABLE `ajustes`
  ADD CONSTRAINT `ajustes_ibfk_1` FOREIGN KEY (`idReserva`) REFERENCES `reservas` (`id`);

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`nro_cliente`) REFERENCES `clientes` (`nro_cliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
