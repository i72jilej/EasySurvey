-- phpMyAdmin SQL Dump
-- version 4.6.0
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 29-05-2016 a las 22:32:08
-- Versión del servidor: 5.5.47-0+deb8u1
-- Versión de PHP: 5.6.19-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `symfony`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Answers`
--

CREATE TABLE `Answers` (
  `id` int(11) NOT NULL,
  `id_question` int(11) NOT NULL,
  `body` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` datetime NOT NULL,
  `id_instance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Answers`
--

INSERT INTO `Answers` (`id`, `id_question`, `body`, `timestamp`, `id_instance`) VALUES
(1, 2, '4', '2016-05-22 20:10:49', 1),
(2, 3, '2', '2016-05-22 20:10:49', 1),
(3, 5, '5,6', '2016-05-22 20:10:49', 1),
(4, 10, 'sdad', '2016-05-22 20:10:49', 1),
(5, 2, '2', '2016-05-23 14:42:07', 1),
(6, 3, '2', '2016-05-23 14:42:07', 1),
(7, 5, '6', '2016-05-23 14:42:07', 1),
(8, 10, 'ddsaadas', '2016-05-23 14:42:07', 1),
(9, 2, '2', '2016-05-23 16:02:12', 1),
(10, 3, '1', '2016-05-23 16:02:12', 1),
(11, 5, '4', '2016-05-23 16:02:12', 1),
(12, 10, 'adsasd', '2016-05-23 16:02:12', 1),
(13, 2, '3', '2016-05-24 11:00:36', 1),
(14, 3, '2', '2016-05-24 11:00:36', 1),
(15, 5, '5', '2016-05-24 11:00:36', 1),
(16, 10, 'dsadsa', '2016-05-24 11:00:36', 1),
(17, 2, '1', '2016-05-26 16:55:38', 10),
(18, 3, '1', '2016-05-26 16:55:38', 10),
(19, 5, '4,5', '2016-05-26 16:55:38', 10),
(20, 10, 'aaaaaaaaaaaa', '2016-05-26 16:55:38', 10),
(21, 2, '1', '2016-05-26 16:55:58', 10),
(22, 3, '2', '2016-05-26 16:55:58', 10),
(23, 5, '4', '2016-05-26 16:55:58', 10),
(24, 10, 'bbbbbbbbbb', '2016-05-26 16:55:58', 10),
(25, 2, '3', '2016-05-26 16:56:19', 10),
(26, 3, '3', '2016-05-26 16:56:19', 10),
(27, 5, '4,5,6', '2016-05-26 16:56:19', 10),
(28, 10, 'cccccccccc', '2016-05-26 16:56:19', 10),
(29, 4, '12', '2016-05-26 22:29:23', 13),
(30, 11, '11', '2016-05-26 22:29:23', 13),
(31, 12, '1', '2016-05-26 22:29:23', 13),
(32, 13, '12', '2016-05-26 22:29:23', 13),
(33, 4, '111', '2016-05-26 22:29:43', 13),
(34, 11, '111', '2016-05-26 22:29:43', 13),
(35, 12, '111', '2016-05-26 22:29:43', 13),
(36, 13, '111', '2016-05-26 22:29:43', 13),
(37, 4, '333', '2016-05-26 22:30:02', 13),
(38, 11, '333', '2016-05-26 22:30:02', 13),
(39, 12, '333', '2016-05-26 22:30:02', 13),
(40, 13, '333', '2016-05-26 22:30:02', 13),
(41, 18, '1', '2016-05-28 16:42:29', 15),
(42, 20, '20', '2016-05-28 16:42:29', 15),
(43, 21, '24', '2016-05-28 16:42:29', 15),
(44, 22, 'aaaaa', '2016-05-28 16:42:29', 15),
(45, 18, '2', '2016-05-28 16:42:49', 15),
(46, 20, '21', '2016-05-28 16:42:49', 15),
(47, 21, '23,24', '2016-05-28 16:42:49', 15),
(48, 22, 'bbbbbbbb', '2016-05-28 16:42:49', 15),
(49, 18, '3', '2016-05-28 16:43:24', 15),
(50, 20, '22', '2016-05-28 16:43:24', 15),
(51, 21, '23,24,25', '2016-05-28 16:43:24', 15),
(52, 22, 'cccccccccc', '2016-05-28 16:43:24', 15),
(53, 2, '1', '2016-05-28 17:45:00', 16),
(54, 3, '1', '2016-05-28 17:45:00', 16),
(55, 5, '5', '2016-05-28 17:45:00', 16),
(56, 10, 'dsas', '2016-05-28 17:45:00', 16),
(57, 2, '11111', '2016-05-29 12:44:29', 10),
(58, 3, '1', '2016-05-29 12:44:29', 10),
(59, 5, '5', '2016-05-29 12:44:29', 10),
(60, 10, 'csaddas', '2016-05-29 12:44:29', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Instance`
--

CREATE TABLE `Instance` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `timecreated` datetime NOT NULL,
  `timefinish` datetime NOT NULL,
  `seeskey` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Instance`
--

INSERT INTO `Instance` (`id`, `quiz_id`, `user_id`, `timecreated`, `timefinish`, `seeskey`, `name`) VALUES
(1, 1, 1, '2016-05-25 19:08:56', '2016-05-25 19:09:45', '219328b228', 'Instancia 1'),
(10, 1, 1, '2016-05-25 19:17:16', '2016-05-31 00:00:00', 'e6f78cba76', 'Instancia 2'),
(13, 2, 3, '2016-05-26 22:28:47', '2016-06-26 00:00:00', 'b3a66331d2', 'Instancia test'),
(14, 1, 5, '2016-05-27 13:32:12', '2016-05-27 00:00:00', 'eeccfb20e5', 'Prueba');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Project`
--

CREATE TABLE `Project` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Project`
--

INSERT INTO `Project` (`id`, `user_id`, `name`, `description`) VALUES
(1, 1, 'Calidad', 'calidad'),
(2, 3, 'Test', NULL),
(5, 1, 'Prueba', 'Proyecto de prueba'),
(6, 11, 'Proyecto de prueba de Ralkai', 'Probando'),
(8, 12, 'Prueba2', 'prueba2'),
(9, 10, 'Prueba 123', 'prueba');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ProjectUser`
--

CREATE TABLE `ProjectUser` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `ProjectUser`
--

INSERT INTO `ProjectUser` (`id`, `project_id`, `user_id`) VALUES
(7, 1, 4),
(8, 1, 5),
(9, 1, 10),
(10, 1, 11),
(11, 5, 3),
(12, 5, 4),
(13, 5, 5),
(14, 5, 10),
(15, 5, 11),
(17, 9, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Question`
--

CREATE TABLE `Question` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Question`
--

INSERT INTO `Question` (`id`, `name`, `type_id`, `quiz_id`) VALUES
(2, 'Pregunta 1', 0, 1),
(3, 'Pregunta 2', 2, 1),
(4, 'pregunta 1', 1, 2),
(5, 'Pregunta 3', 3, 1),
(6, 'Pregunta 1', 0, 3),
(7, 'Pregunta 2', 1, 3),
(8, 'Pregunta 3', 2, 3),
(9, 'Pregunta 4', 3, 3),
(10, 'Pregunta 4', 1, 1),
(11, 'pregunta 2', 0, 2),
(12, 'pregunta 3', 0, 2),
(13, 'pregunta 4', 0, 2),
(14, 'Pregunta numerica de Ralkai', 0, 7),
(15, 'Pregunta de texto de Ralkai', 1, 7),
(16, 'Pregunta eleccion unica de Ralkai', 2, 7),
(17, 'Pregunta de eleccion multiple de Ralkai', 3, 7),
(32, 'Numérica', 0, 12),
(33, 'Texto', 1, 12),
(34, 'Simple', 2, 12),
(35, 'Múltiple', 3, 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Quiz`
--

CREATE TABLE `Quiz` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `project_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Quiz`
--

INSERT INTO `Quiz` (`id`, `name`, `description`, `project_id`) VALUES
(1, 'Cuestionario 1 de calidad', 'Cuestionario 1 de calidad', 1),
(2, 'cuestionario', NULL, 2),
(3, 'Pruieba', 'Encuesta de prueba', 3),
(7, 'Encuesta de pruebas de estilo', 'Encuesta para probar estilos en el servidor', 6),
(12, 'Cuestionario 2 de calidad', 'calidad2', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TextQuestionOption`
--

CREATE TABLE `TextQuestionOption` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `TextQuestionOption`
--

INSERT INTO `TextQuestionOption` (`id`, `question_id`, `text`) VALUES
(1, 3, 'Opcion a'),
(2, 3, 'Opcion b'),
(3, 3, 'Opcion c'),
(4, 5, 'Opcion 1'),
(5, 5, 'Opcion 2'),
(6, 5, 'Opcion 3'),
(7, 8, 'Opcion a'),
(8, 8, 'Opcion b'),
(9, 8, 'Opcion c'),
(10, 9, 'Opcion a'),
(11, 9, 'Opcion b'),
(12, 9, 'Opcion c'),
(13, 11, 'opcion 1'),
(14, 11, 'opcion 2'),
(15, 16, 'Opcion A de Ralkai'),
(16, 16, 'Opcion B de Ralkai'),
(17, 17, 'Opcion 1 de Ralkai'),
(18, 17, 'Opción 2 de Ralkai'),
(19, 17, 'Opción 3 de Ralkai'),
(39, 34, 'Opción a'),
(40, 34, 'Opción b'),
(41, 34, 'Opción c'),
(42, 35, 'Opción a'),
(43, 35, 'Opción b'),
(44, 35, 'Opción c');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `User`
--

CREATE TABLE `User` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `confirm` tinyint(1) NOT NULL,
  `sesskeyConfirm` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `User`
--

INSERT INTO `User` (`id`, `username`, `password`, `firstname`, `lastname`, `email`, `confirm`, `sesskeyConfirm`) VALUES
(1, 'ahvega', '8edcd467ae57b5048c3dd30307e7b3ee', 'ahvega', 'ahvega', 'ahvega@gmail.com', 1, '8e7b8'),
(3, 'jorge', '81dc9bdb52d04dc20036dbd8313ed055', 'Jorge', 'DM', 'jorge94cb@gmail.com', 1, '2b7d0'),
(4, 'Veltys', 'e807f1fcf82d132f9bb018ca6738a19f', 'Veltys', 'Veltys', 'veltys@veltys.es', 1, '9c78e'),
(5, 'i32chhid', '4213b716e4ef5b190b350993414049f9', 'David', 'Checa Hidalgo', 'i32chhid@uco.es', 1, 'dc743'),
(10, 'i22hevea', 'b57d840ce6f2221072f00d6310f65d83', 'i22hevea', 'i22hevea', 'i22hevea@uco.es', 1, '93724'),
(11, 'Ralkai', '5f4dcc3b5aa765d61d8327deb882cf99', 'Julio', 'Jiméenz', 'ralkaishagtten@gmail.com', 1, 'cf459'),
(15, 'herrerii', 'dcd6a9b9ade60a3dd7d3a60df66929f7', 'herrerii', 'herrerii', 'herrerii@gmail.com', 1, 'b56fe');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Answers`
--
ALTER TABLE `Answers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Instance`
--
ALTER TABLE `Instance`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Project`
--
ALTER TABLE `Project`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ProjectUser`
--
ALTER TABLE `ProjectUser`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Question`
--
ALTER TABLE `Question`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Quiz`
--
ALTER TABLE `Quiz`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `TextQuestionOption`
--
ALTER TABLE `TextQuestionOption`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Answers`
--
ALTER TABLE `Answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT de la tabla `Instance`
--
ALTER TABLE `Instance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT de la tabla `Project`
--
ALTER TABLE `Project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `ProjectUser`
--
ALTER TABLE `ProjectUser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT de la tabla `Question`
--
ALTER TABLE `Question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT de la tabla `Quiz`
--
ALTER TABLE `Quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `TextQuestionOption`
--
ALTER TABLE `TextQuestionOption`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT de la tabla `User`
--
ALTER TABLE `User`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
