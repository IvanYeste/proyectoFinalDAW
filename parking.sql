-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-05-2024 a las 13:33:58
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
-- Base de datos: `parking`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `ID_horario` int(11) NOT NULL,
  `ID_trabajador` int(11) DEFAULT NULL,
  `Fecha` date NOT NULL,
  `Hora_inicio` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `horarios`
--

INSERT INTO `horarios` (`ID_horario`, `ID_trabajador`, `Fecha`, `Hora_inicio`) VALUES
(69, 2, '2024-05-01', '10:00:00'),
(70, 21, '2024-05-01', '11:00:00'),
(71, 20, '2024-05-01', '14:00:00'),
(72, 1, '2024-05-02', '10:00:00'),
(83, 21, '2024-05-03', '10:00:00'),
(85, 1, '2024-06-01', '10:00:00'),
(86, 2, '2024-06-01', '11:00:00'),
(87, 20, '2024-06-01', '14:00:00'),
(88, 1, '2024-06-02', '10:00:00'),
(89, 2, '2024-06-02', '11:00:00'),
(90, 20, '2024-06-02', '14:00:00'),
(91, 71, '2024-06-08', '10:00:00'),
(92, 72, '2024-06-08', '11:00:00'),
(93, 73, '2024-06-08', '14:00:00'),
(94, 73, '2024-06-09', '10:00:00'),
(95, 1, '2024-06-09', '11:00:00'),
(98, 72, '2024-06-09', '14:00:00'),
(99, 20, '2024-06-15', '10:00:00'),
(100, 71, '2024-05-11', '11:00:00'),
(101, 72, '2024-06-15', '14:00:00'),
(102, 71, '2024-06-15', '11:00:00'),
(103, 71, '2024-06-16', '10:00:00'),
(104, 20, '2024-06-16', '11:00:00'),
(105, 73, '2024-06-16', '14:00:00'),
(106, 72, '2024-06-22', '10:00:00'),
(107, 73, '2024-06-22', '11:00:00'),
(108, 1, '2024-06-22', '14:00:00'),
(109, 1, '2024-06-23', '10:00:00'),
(110, 20, '2024-06-23', '11:00:00'),
(111, 71, '2024-06-23', '14:00:00'),
(112, 1, '2024-06-07', '10:00:00'),
(113, 20, '0000-00-00', '11:00:00'),
(114, 72, '2024-06-07', '14:00:00'),
(115, 20, '2024-06-07', '11:00:00'),
(116, 71, '2024-06-06', '10:00:00'),
(117, 73, '2024-06-06', '11:00:00'),
(119, 20, '2024-06-06', '11:00:00'),
(120, 20, '2024-06-05', '10:00:00'),
(121, 73, '2024-06-05', '11:00:00'),
(122, 72, '2024-06-05', '14:00:00'),
(123, 73, '2024-06-04', '10:00:00'),
(124, 71, '2024-06-04', '11:00:00'),
(125, 2, '2024-06-04', '14:00:00'),
(126, 2, '2024-06-03', '10:00:00'),
(127, 72, '2024-06-03', '11:00:00'),
(129, 71, '2024-06-03', '14:00:00'),
(130, 71, '2024-06-03', '14:00:00'),
(131, 71, '2024-06-03', '14:00:00'),
(132, 71, '2024-06-03', '14:00:00'),
(133, 71, '2024-06-03', '14:00:00'),
(134, 71, '2024-06-03', '14:00:00'),
(135, 71, '2024-06-03', '14:00:00'),
(136, 71, '2024-06-03', '14:00:00'),
(137, 71, '2024-06-03', '14:00:00'),
(138, 71, '2024-06-03', '14:00:00'),
(139, 71, '2024-06-03', '14:00:00'),
(140, 71, '2024-06-03', '14:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `ID_reserva` int(11) NOT NULL,
  `ID_cliente` int(11) NOT NULL,
  `Fecha_inicio` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `Fecha_fin` date NOT NULL,
  `Matricula` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`ID_reserva`, `ID_cliente`, `Fecha_inicio`, `hora_inicio`, `hora_fin`, `Fecha_fin`, `Matricula`) VALUES
(30, 69, '2024-05-19', '14:06:00', '16:06:00', '2024-05-22', '1900HSZ'),
(31, 74, '2024-05-24', '12:25:00', '13:26:00', '2024-05-25', '1900HSZ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_cambio_horario`
--

CREATE TABLE `solicitudes_cambio_horario` (
  `ID_solicitud` int(11) NOT NULL,
  `ID_trabajador_envia` int(11) NOT NULL,
  `ID_trabajador_recibe` int(11) NOT NULL,
  `Horario_actual` int(11) DEFAULT NULL,
  `Horario_cambio` int(11) DEFAULT NULL,
  `Estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `solicitudes_cambio_horario`
--

INSERT INTO `solicitudes_cambio_horario` (`ID_solicitud`, `ID_trabajador_envia`, `ID_trabajador_recibe`, `Horario_actual`, `Horario_cambio`, `Estado`) VALUES
(36, 1, 21, 70, 72, 'Registrado'),
(37, 1, 20, 72, 71, 'Registrado'),
(38, 1, 20, 71, 72, 'Registrado'),
(39, 1, 20, 72, 71, 'Registrado'),
(40, 1, 20, 71, 72, 'Registrado'),
(41, 1, 20, 72, 71, 'Rechazada'),
(42, 1, 21, 72, 70, 'Registrado'),
(43, 1, 21, 70, 72, 'Registrado'),
(44, 1, 20, 72, 71, 'Registrado'),
(45, 1, 20, 71, 72, 'Registrado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `ID_usuario` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Apellidos` varchar(100) NOT NULL,
  `pwd` varchar(100) NOT NULL,
  `e-mail` varchar(200) NOT NULL,
  `tipo` char(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`ID_usuario`, `Nombre`, `Apellidos`, `pwd`, `e-mail`, `tipo`) VALUES
(1, 'Ivan', 'Yeste Antoli', '123', 'ivanyesteantoli@gmail.com', '0'),
(2, 'Elia', 'Ruiz Felipe', '123', 'qwe@qwe.com', '0'),
(3, 'M.Jose', '', '123', 'qwe@qwe.com', '1'),
(20, 'Marc', 'Yeste Antoli', '123', 'm@m', '0'),
(21, 'Alba', 'Mestre', '123', 'a@a', '0'),
(66, 'f', 'f', 'f', 'f', '2'),
(69, 'asc', 'asc', 's', 'w@w', '2'),
(71, 'Ainoa', 'Traber', '123', 'i@i.com', '0'),
(72, 'Olga', 'Garcia', '123', 'i@i.com', '0'),
(73, 'Tania', 'Ripolles', '123', 'i@i.com', '0'),
(74, 'w', 'w', 'w', 'w', '2');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`ID_horario`),
  ADD KEY `ID_trabajador` (`ID_trabajador`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`ID_reserva`),
  ADD KEY `fk_cliente_usuario` (`ID_cliente`);

--
-- Indices de la tabla `solicitudes_cambio_horario`
--
ALTER TABLE `solicitudes_cambio_horario`
  ADD PRIMARY KEY (`ID_solicitud`),
  ADD KEY `ID_trabajador_envia` (`ID_trabajador_envia`),
  ADD KEY `ID_trabajador_recibe` (`ID_trabajador_recibe`),
  ADD KEY `Horario_cambio` (`Horario_cambio`),
  ADD KEY `Horario_actual` (`Horario_actual`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `ID_horario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `ID_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `solicitudes_cambio_horario`
--
ALTER TABLE `solicitudes_cambio_horario`
  MODIFY `ID_solicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`ID_trabajador`) REFERENCES `usuarios` (`ID_usuario`);

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `fk_cliente_usuario` FOREIGN KEY (`ID_cliente`) REFERENCES `usuarios` (`ID_usuario`);

--
-- Filtros para la tabla `solicitudes_cambio_horario`
--
ALTER TABLE `solicitudes_cambio_horario`
  ADD CONSTRAINT `solicitudes_cambio_horario_ibfk_1` FOREIGN KEY (`ID_trabajador_envia`) REFERENCES `usuarios` (`ID_usuario`),
  ADD CONSTRAINT `solicitudes_cambio_horario_ibfk_2` FOREIGN KEY (`ID_trabajador_recibe`) REFERENCES `usuarios` (`ID_usuario`),
  ADD CONSTRAINT `solicitudes_cambio_horario_ibfk_3` FOREIGN KEY (`Horario_cambio`) REFERENCES `horarios` (`ID_horario`),
  ADD CONSTRAINT `solicitudes_cambio_horario_ibfk_4` FOREIGN KEY (`Horario_actual`) REFERENCES `horarios` (`ID_horario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
