-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 25-02-2025 a las 16:29:40
-- Versión del servidor: 5.7.33-0ubuntu0.16.04.1
-- Versión de PHP: 7.0.33-0ubuntu0.16.04.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gys`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `matriz_gys`
--

CREATE TABLE `matriz_gys` (
  `id` int(11) NOT NULL,
  `rfc` varchar(13) NOT NULL,
  `beneficiario` mediumint(8) NOT NULL,
  `puesto` varchar(6) NOT NULL,
  `monto` float NOT NULL,
  `centro` varchar(10) NOT NULL,
  `num_suplente` int(6) NOT NULL,
  `fecha_inicial` date NOT NULL,
  `fecha_final` date NOT NULL,
  `dias` int(2) NOT NULL,
  `num_empleado` int(6) NOT NULL,
  `fecha_captura` date NOT NULL,
  `incidencia` varchar(2) NOT NULL,
  `servicio` int(5) NOT NULL,
  `year` int(4) NOT NULL,
  `quincena` int(6) NOT NULL,
  `nombre_suplente` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `matriz_gys`
--
ALTER TABLE `matriz_gys`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `matriz_gys`
--
ALTER TABLE `matriz_gys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
