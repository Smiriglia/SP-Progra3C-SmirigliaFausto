-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 12-12-2023 a las 14:05:45
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
('000004', 'test4', 'dni', '42452558', 'abc@gmail.com', 'corpo', 'Polonia', 'Madrid', '123567547', 'efectivo', 'Eliminado', '000004co.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logaccesos`
--

CREATE TABLE `logaccesos` (
  `id` int(11) NOT NULL,
  `accion` varchar(255) DEFAULT NULL,
  `metodo` varchar(255) DEFAULT NULL,
  `fecha` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `logaccesos`
--

INSERT INTO `logaccesos` (`id`, `accion`, `metodo`, `fecha`) VALUES
(1, '/usuario/sesion/', 'POST', '12/12/2023'),
(2, '/usuario/sesion/', 'GET', '12/12/2023'),
(3, '/usuario/sesion/', 'POST', '12/12/2023'),
(4, '/reserva/crear', 'POST', '12/12/2023'),
(5, '/', 'GET', '12/12/2023'),
(6, '/', 'GET', '12/12/2023'),
(7, '/usuario/sesion/', 'POST', '12/12/2023'),
(8, '/reserva/consulta/importeTotal', 'GET', '12/12/2023'),
(9, '/usuario/sesion/', 'GET', '12/12/2023'),
(10, '/reserva/consulta/importeTotal', 'GET', '12/12/2023'),
(11, '/usuario/crear', 'POST', '12/12/2023'),
(12, '/usuario/crear', 'POST', '12/12/2023'),
(13, '/usuario/sesion/', 'POST', '12/12/2023'),
(14, '/cliente/eliminar', 'DELETE', '12/12/2023'),
(15, '/cliente/eliminar', 'DELETE', '12/12/2023'),
(16, '/cliente/eliminar', 'DELETE', '12/12/2023'),
(17, '/cliente/eliminar', 'DELETE', '12/12/2023'),
(18, '/cliente/eliminar', 'DELETE', '12/12/2023'),
(19, '/usuario/sesion/', 'GET', '12/12/2023'),
(20, '/usuario/sesion/', 'POST', '12/12/2023'),
(21, '/usuario/sesion/', 'POST', '12/12/2023 01:35:17'),
(22, '/usuario/sesion/', 'GET', '12/12/2023 02:56:29'),
(23, '/usuario/sesion/', 'GET', '12/12/2023 02:58:13'),
(24, '/usuario/sesion/', 'GET', '12/12/2023 02:59:08'),
(25, '/usuario/sesion/', 'POST', '12/12/2023 03:06:32'),
(26, '/usuario/sesion/', 'POST', '12/12/2023 03:09:10'),
(27, '/usuario/sesion/', 'POST', '12/12/2023 03:09:20'),
(28, '/usuario/sesion/', 'POST', '12/12/2023 03:11:10'),
(29, '/usuario/sesion/', 'POST', '12/12/2023 03:22:28'),
(30, '/usuario/sesion/', 'POST', '12/12/2023 03:23:34'),
(31, '/usuario/sesion/', 'POST', '12/12/2023 03:24:03'),
(32, '/usuario/sesion/', 'POST', '12/12/2023 03:24:46'),
(33, '/usuario/sesion/', 'POST', '12/12/2023 03:25:03'),
(34, '/usuario/sesion/', 'POST', '12/12/2023 03:25:58'),
(35, '/usuario/sesion/', 'POST', '12/12/2023 03:26:24'),
(36, '/usuario/sesion/', 'POST', '12/12/2023 03:39:36'),
(37, '/usuario/sesion/', 'POST', '12/12/2023 03:39:36'),
(38, '/usuario/sesion/', 'POST', '12/12/2023 03:40:07'),
(39, '/usuario/sesion/', 'POST', '12/12/2023 03:40:07'),
(40, '/usuario/sesion/', 'POST', '12/12/2023 03:40:31'),
(41, '/usuario/sesion/', 'POST', '12/12/2023 03:40:31'),
(42, '/usuario/sesion/', 'POST', '12/12/2023 03:42:39'),
(43, '/usuario/sesion/', 'POST', '12/12/2023 03:42:39'),
(44, '/usuario/sesion/', 'POST', '12/12/2023 03:43:28'),
(45, '/usuario/sesion/', 'POST', '12/12/2023 03:43:28'),
(46, '/usuario/sesion/', 'POST', '12/12/2023 03:43:51'),
(47, '/usuario/sesion/', 'POST', '12/12/2023 03:43:51'),
(48, '/usuario/sesion/', 'POST', '12/12/2023 03:44:59'),
(49, '/usuario/sesion/', 'POST', '12/12/2023 03:44:59'),
(50, '/usuario/sesion/', 'POST', '12/12/2023 03:45:30'),
(51, '/usuario/sesion/', 'POST', '12/12/2023 03:45:30'),
(52, '/usuario/sesion/', 'POST', '12/12/2023 03:49:20'),
(53, '/usuario/sesion/', 'POST', '12/12/2023 03:49:21'),
(54, '/usuario/sesion/', 'POST', '12/12/2023 03:50:06'),
(55, '/usuario/sesion/', 'POST', '12/12/2023 03:50:06'),
(56, '/usuario/sesion/', 'GET', '12/12/2023 03:50:22'),
(57, '/usuario/sesion/', 'GET', '12/12/2023 03:50:22'),
(58, '/usuario/sesion/', 'POST', '12/12/2023 03:56:10'),
(59, '/usuario/sesion/', 'POST', '12/12/2023 03:56:41'),
(60, '/reserva/cancelar', 'POST', '12/12/2023 03:56:54'),
(61, '/reserva/cancelar', 'POST', '12/12/2023 03:56:54'),
(62, '/reserva/cancelar', 'POST', '12/12/2023 03:57:04'),
(63, '/reserva/cancelar', 'POST', '12/12/2023 03:57:04'),
(64, '/usuario/crear', 'POST', '12/12/2023 04:01:14'),
(65, '/usuario/crear', 'POST', '12/12/2023 04:01:14'),
(66, '/usuario/crear', 'POST', '12/12/2023 04:01:21'),
(67, '/usuario/crear', 'POST', '12/12/2023 04:01:21'),
(68, '/usuario/crear', 'POST', '12/12/2023 04:01:30'),
(69, '/usuario/crear', 'POST', '12/12/2023 04:01:30'),
(70, '/usuario/crear', 'POST', '12/12/2023 04:02:06'),
(71, '/usuario/crear', 'POST', '12/12/2023 04:02:06'),
(72, '/usuario/crear', 'POST', '12/12/2023 04:04:51'),
(73, '/usuario/crear', 'POST', '12/12/2023 04:04:51'),
(74, '/usuario/crear', 'POST', '12/12/2023 04:05:41'),
(75, '/usuario/crear', 'POST', '12/12/2023 04:05:41'),
(76, '/usuario/crear', 'POST', '12/12/2023 04:06:16'),
(77, '/usuario/crear', 'POST', '12/12/2023 04:06:16'),
(78, '/usuario/crear', 'POST', '12/12/2023 04:06:32'),
(79, '/usuario/crear', 'POST', '12/12/2023 04:06:32'),
(80, '/usuario/crear', 'POST', '12/12/2023 04:06:49'),
(81, '/usuario/crear', 'POST', '12/12/2023 04:06:49'),
(82, '/usuario/crear', 'POST', '12/12/2023 04:10:34'),
(83, '/usuario/crear', 'POST', '12/12/2023 04:11:17'),
(84, '/usuario/crear', 'POST', '12/12/2023 04:11:37'),
(85, '/usuario/crear', 'POST', '12/12/2023 04:12:25'),
(86, '/usuario/crear', 'POST', '12/12/2023 04:13:40'),
(87, '/usuario/crear', 'POST', '12/12/2023 04:14:21'),
(88, '/usuario/crear', 'POST', '12/12/2023 04:15:14'),
(89, '/cliente/modificar', 'POST', '12/12/2023 04:15:32'),
(90, '/cliente/modificar', 'POST', '12/12/2023 04:16:51'),
(91, '/cliente/modificar', 'POST', '12/12/2023 04:17:40'),
(92, '/reserva/ajustar', 'POST', '12/12/2023 04:19:33'),
(93, '/usuario/sesion/', 'GET', '12/12/2023 04:19:44'),
(94, '/usuario/sesion/', 'GET', '12/12/2023 04:24:10'),
(95, '/usuario/sesion/', 'GET', '12/12/2023 04:24:45'),
(96, '/log/acceso/exportarPDF', 'GET', '12/12/2023 05:13:39'),
(97, '/log/exportarPDF', 'GET', '12/12/2023 05:14:41'),
(98, '/log/acceso/exportarPDF', 'GET', '12/12/2023 05:15:38'),
(99, '/log/acceso', 'GET', '12/12/2023 05:19:50'),
(100, '/log/acceso', 'GET', '12/12/2023 05:20:33'),
(101, '/log/transacciones', 'GET', '12/12/2023 05:37:28'),
(102, '/log/transaccion', 'GET', '12/12/2023 05:37:45'),
(103, '/log/transaccion', 'GET', '12/12/2023 05:38:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logtransacciones`
--

CREATE TABLE `logtransacciones` (
  `nroTransaccion` int(11) NOT NULL,
  `fecha` varchar(50) DEFAULT NULL,
  `idUsuario` int(11) DEFAULT NULL,
  `accion` varchar(50) NOT NULL,
  `code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `logtransacciones`
--

INSERT INTO `logtransacciones` (`nroTransaccion`, `fecha`, `idUsuario`, `accion`, `code`) VALUES
(1, '12/12/2023 03:50:06', 1, '', 200),
(2, '12/12/2023 03:50:22', 1, '', 200),
(3, '12/12/2023 04:01:14', 1, '', 400),
(4, '12/12/2023 04:01:21', 1, '', 400),
(5, '12/12/2023 04:01:30', 1, '', 400),
(6, '12/12/2023 04:02:06', 1, '', 400),
(7, '12/12/2023 04:04:51', 1, '', 400),
(8, '12/12/2023 04:06:32', 1, '', 400),
(9, '12/12/2023 04:06:49', 1, '', 400),
(10, '12/12/2023 04:10:34', 1, '', 400),
(11, '12/12/2023 04:11:17', 1, '', 400),
(12, '12/12/2023 04:11:37', -1, '', 400),
(13, '12/12/2023 04:12:25', -1, '', 400),
(14, '12/12/2023 04:13:40', -1, '', 400),
(15, '12/12/2023 04:15:32', -1, '', 200),
(16, '12/12/2023 04:16:51', -1, '', 200),
(17, '12/12/2023 04:17:40', -1, '', 200),
(18, '12/12/2023 04:19:44', -1, '', 200),
(19, '12/12/2023 04:24:45', -1, '/usuario/sesion/', 200),
(20, '12/12/2023 05:20:33', -1, '/log/acceso', 200),
(21, '12/12/2023 05:38:01', -1, '/log/transaccion', 200);

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
(7, 'corpo', '000003', '20/12/2009', '15/03/2008', 'doble', 1000.00, 'Activo', NULL, 'tarjeta'),
(8, 'corpo', '000003', '20/12/2009', '15/03/2008', 'doble', 1000.00, 'Activo', NULL, 'tarjeta'),
(9, 'corpo', '000003', '20/12/2009', '15/03/2008', 'doble', 1000.00, 'Activo', NULL, 'tarjeta'),
(10, 'corpo', '000003', '20/12/2009', '15/03/2008', 'doble', 1000.00, 'Activo', NULL, 'tarjeta'),
(11, 'corpo', '000003', '20/12/2009', '15/03/2008', 'doble', 1000.00, 'Activo', NULL, 'tarjeta'),
(12, 'corpo', '000003', '20/12/2009', '15/03/2008', 'doble', 1000.00, 'Activo', NULL, 'tarjeta'),
(13, 'corpo', '000003', '20/12/2009', '15/03/2008', 'doble', 1000.00, 'Activo', NULL, 'tarjeta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `clave` varchar(255) DEFAULT NULL,
  `rol` varchar(50) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `clave`, `rol`, `estado`) VALUES
(-1, 'juan', 'desconocido@test.com', '$2y$10$bYBV4GVkBfgM0hZURusnCuSAowZ1wEx6F4nvSvkm2jXPWBGyJybty', 'cliente', 'Eliminado'),
(1, 'juan', 'gerente@test.com', '$2y$10$xyBM/4nFWgEVqzxX4otdOOIvAmHan72Jx1OtShMDAyiWjb2oaZ4YG', 'gerente', 'Activo'),
(3, 'juan', 'recepcionista@test.com', '$2y$10$Z9cwl1XzwHiRB/QlJ.FgHu3/pL4zUw20kG.lv9fnGEL8MEjbjgBn2', 'recepcionista', 'Activo'),
(4, 'juan', 'cliente@test.com', '$2y$10$7IKvUTxS0/kpAI0o.VHUiOrnOp1zX56U9e3bt0TuLXmzQfQpgCwOi', 'cliente', 'Activo');

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
-- Indices de la tabla `logaccesos`
--
ALTER TABLE `logaccesos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `logtransacciones`
--
ALTER TABLE `logtransacciones`
  ADD PRIMARY KEY (`nroTransaccion`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nro_cliente` (`nro_cliente`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ajustes`
--
ALTER TABLE `ajustes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `logaccesos`
--
ALTER TABLE `logaccesos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT de la tabla `logtransacciones`
--
ALTER TABLE `logtransacciones`
  MODIFY `nroTransaccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ajustes`
--
ALTER TABLE `ajustes`
  ADD CONSTRAINT `ajustes_ibfk_1` FOREIGN KEY (`idReserva`) REFERENCES `reservas` (`id`);

--
-- Filtros para la tabla `logtransacciones`
--
ALTER TABLE `logtransacciones`
  ADD CONSTRAINT `logtransacciones_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`nro_cliente`) REFERENCES `clientes` (`nro_cliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
