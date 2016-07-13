-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Servidor: localhost:8889
-- Tiempo de generación: 22-04-2016 a las 01:04:12
-- Versión del servidor: 5.5.42
-- Versión de PHP: 7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de datos: `bdchat`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `groupchat`
--

CREATE TABLE `groupchat` (
  `groupid` int(11) NOT NULL,
  `description` varchar(100) CHARACTER SET latin1 NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `groupchat`
--

INSERT INTO `groupchat` (`groupid`, `description`, `created`) VALUES
(1, 'Test', '2016-04-18 20:53:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `message`
--

CREATE TABLE `message` (
  `messageid` int(11) NOT NULL,
  `groupchatid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `message` text CHARACTER SET latin1 NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `message`
--

INSERT INTO `message` (`messageid`, `groupchatid`, `userid`, `message`, `created`) VALUES
(3, 1, 1, 'Hola', '2016-04-18 21:44:44'),
(4, 1, 1, 'Prueba', '2016-04-18 22:15:48'),
(5, 1, 1, 'Prueba', '2016-04-18 22:16:16'),
(6, 1, 1, 'Prueba', '2016-04-18 22:37:27'),
(7, 1, 1, 'Prueba', '2016-04-18 22:37:40'),
(8, 1, 1, 'Prueba', '2016-04-18 22:45:24'),
(9, 1, 1, 'Prueba', '2016-04-19 17:20:33'),
(10, 1, 2, 'hola', '2016-04-20 20:43:38'),
(11, 1, 2, 'hola', '2016-04-20 21:03:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `message2`
--

CREATE TABLE `message2` (
  `messageid` int(11) NOT NULL,
  `usersend` int(11) NOT NULL,
  `userrecept` int(11) NOT NULL,
  `message` text COLLATE utf32_spanish_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `message2`
--

INSERT INTO `message2` (`messageid`, `usersend`, `userrecept`, `message`, `created`) VALUES
(1, 2, 1, 'hola', '2016-04-21 21:19:17'),
(2, 1, 2, 'que tal?', '2016-04-21 21:19:26'),
(3, 2, 1, 'como estas?', '2016-04-21 21:19:39'),
(4, 2, 1, 'como estas?', '2016-04-21 21:19:51'),
(5, 2, 1, 'como estas?', '2016-04-21 21:20:03'),
(6, 1, 2, 'rrr', '2016-04-21 21:20:09'),
(7, 2, 1, 'como estas?', '2016-04-21 21:20:15'),
(8, 2, 1, 'como estas?', '2016-04-21 21:29:14'),
(9, 2, 1, 'como estas?', '2016-04-21 21:40:26'),
(10, 2, 1, 'eeeee', '2016-04-21 21:49:13'),
(11, 2, 1, 'eeeee', '2016-04-21 21:49:28'),
(12, 2, 1, 'aaaa', '2016-04-21 21:49:39'),
(13, 2, 1, 'aaaaaccc', '2016-04-21 21:49:53'),
(14, 2, 1, 'aaaaaccc', '2016-04-21 21:50:10'),
(15, 1, 2, 'xyz', '2016-04-21 21:54:32'),
(16, 2, 1, 'wwwww', '2016-04-21 21:54:41'),
(17, 1, 2, 'habla', '2016-04-21 21:56:10'),
(18, 2, 1, '?', '2016-04-21 21:56:25'),
(19, 2, 1, '?', '2016-04-21 22:09:16'),
(20, 2, 1, '?', '2016-04-21 22:17:31'),
(21, 2, 1, '?', '2016-04-21 22:17:54'),
(22, 2, 1, '?', '2016-04-21 22:19:29'),
(23, 1, 2, 'hola', '2016-04-21 22:27:53'),
(24, 2, 1, '?', '2016-04-21 22:28:00'),
(25, 1, 2, 'hola', '2016-04-21 22:30:25'),
(26, 2, 1, '?', '2016-04-21 22:30:40'),
(27, 2, 1, '?', '2016-04-21 22:30:53'),
(28, 1, 2, 'qqq', '2016-04-21 22:30:59'),
(29, 2, 1, '?', '2016-04-21 22:31:02'),
(30, 1, 2, 'Hola', '2016-04-21 22:37:15'),
(31, 1, 2, 'Hola', '2016-04-21 22:37:26'),
(32, 2, 1, 'que hay', '2016-04-21 22:37:36'),
(33, 1, 2, 'aqui', '2016-04-21 22:37:44'),
(34, 2, 1, 'abx', '2016-04-21 22:38:02'),
(35, 1, 2, 'shjsbndf', '2016-04-21 22:38:11'),
(36, 2, 1, 'dvsgsgs', '2016-04-21 22:38:16'),
(37, 1, 2, 'hvj', '2016-04-21 22:38:31'),
(38, 2, 1, 'dvdvege', '2016-04-21 22:55:24'),
(39, 4, 2, 'hola', '2016-04-21 23:00:13'),
(40, 2, 4, 'habla', '2016-04-21 23:00:28'),
(41, 4, 2, 'que hay?', '2016-04-21 23:00:38'),
(42, 2, 4, 'hdhz', '2016-04-21 23:02:11'),
(43, 4, 2, 'fgfv', '2016-04-21 23:02:15'),
(44, 4, 2, 'yghdj', '2016-04-21 23:02:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `userid` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET latin1 NOT NULL,
  `email` varchar(50) CHARACTER SET latin1 NOT NULL,
  `gcmid` varchar(200) CHARACTER SET latin1 NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`userid`, `name`, `email`, `gcmid`, `created`) VALUES
(1, 'Emulador', 'rrrr@rrr.com', 'dNUvbCzdXrw:APA91bHiQ-A-pybpJ-OjkRSF2IKMjetPR-22nUk0Q5JXX3K-J9YelnlECSxOcVh0W62owwW3YqVMlkCPzYwJ3iw3xK3l7AtsAy7dP7btnEuOi95AY1PTVzUORzykzqaAAe6zFNUKDL9r', '2016-04-20 17:05:25'),
(2, 'Ricardo Bravo', 'ricardobravo@outlook.com', 'cdtm8Zr7c2E:APA91bGLtZEEVj7ejwO-C4TWYFo1RLSeVL02n1Royl1pecUdaFdhaUw4VTOyPe1deMKou8UchzbE9ZHk4UOQzxb7oBBXADPcTmqygPZjVjl2lVORP4kzfBy0gC7o7qNK_MCcz-6yACkl', '2016-04-20 17:17:05'),
(3, 'Demo', 'Demo', '', '2016-04-21 15:54:39'),
(4, 'Samsung', 'rrrr@rrr.pe', 'es30gFs5f7Q:APA91bFPZcW6gbbvyu3enUsOmKxcG0CFeb4ZY0y5M1UDksPacdmjaAXkPgBopJzDTut6Nu3tvL1pjoAEoDu8Ypj-bZ72AAR3p0bKO5D1bptKZNX-FZUJcoOobeiLADOB7o89yWDx0YUD', '2016-04-21 22:58:23');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `groupchat`
--
ALTER TABLE `groupchat`
  ADD PRIMARY KEY (`groupid`);

--
-- Indices de la tabla `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`messageid`);

--
-- Indices de la tabla `message2`
--
ALTER TABLE `message2`
  ADD PRIMARY KEY (`messageid`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `groupchat`
--
ALTER TABLE `groupchat`
  MODIFY `groupid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `message`
--
ALTER TABLE `message`
  MODIFY `messageid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT de la tabla `message2`
--
ALTER TABLE `message2`
  MODIFY `messageid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;