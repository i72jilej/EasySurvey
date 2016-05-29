-- phpMyAdmin SQL Dump
-- version 4.6.0
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 29-05-2016 a las 23:50:46
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
(9, 10, 'Prueba 123', 'prueba'),
(10, 17, 'Cuestionarios para medir el nivel de satisfacción a nivel de toda la prueba', 'Para conocer el nivel de satisfacción cada participante debe rellenar un cuestionario al final cada una de las sesiones de pruebas. De esta forma se puede medir su impresión sobre la usabilidad de todo el sistema que está siendo probado.');

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
(35, 'Múltiple', 3, 12),
(36, '01: Creo que usaré este sistema con frecuencia', 2, 13),
(37, '02: Encontré el sistema innecesariamente complejo', 2, 13),
(38, '03: Creo que el sistema ha sido fácil de usar', 2, 13),
(39, '04: Creo que necesitaré aydua de un técnico para utilizar éste sistema', 2, 13),
(40, '05: Encuentro las diferentes funciones del sistema bien integradas', 2, 13),
(41, '06: Creo que había muchas inconsistencias en el sistema', 2, 13),
(42, '07: Creo que la mayoría de la gente aprendería a utilizar este sistema rápidamente', 2, 13),
(43, '08: Encontré el sistema muy incómodo de usar', 2, 13),
(44, '09: Me sentí muy seguro de mí mismo utilizando el sistema', 2, 13),
(45, '10: Necesité aprender muchas cosas antes de poder utilizar el sistema', 2, 13),
(46, 'Usabilidad: El sitio web fue fácil de utilizar', 2, 14),
(47, 'Usabilidad: Es fácil navegar por el sitio web', 2, 14),
(48, 'Credibilidad: La información en el sitio web es creíble', 2, 14),
(49, 'Credibilidad: La información en el sitio web es digna de confianza', 2, 14),
(50, 'Credibilidad: Me siento cómodo/a comprando de este sitio web', 2, 14),
(51, 'Credibilidad: Me siento seguro llevando a cabo negocios con este sitio web', 2, 14),
(52, 'Lealtad: ¿Con qué probabilidad recomendaría el sitio web a un amigo?', 2, 14),
(53, 'Lealtad: Probablemente vuelva a visitar el sitio web en el futuro', 2, 14),
(54, 'Apariencia: Encontré atractivo el sitio web', 2, 14),
(55, 'Apariencia: El sitio web tiene una apariencia limpia y sencilla', 2, 14);

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
(12, 'Cuestionario 2 de calidad', 'calidad2', 1),
(13, 'SUS: System Usability Scale', '(10 Preguntas)', 10),
(14, 'SUPR-Q: Standardized User Experience Percentile Rank Questionnaire', NULL, 10);

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
(44, 35, 'Opción c'),
(45, 36, '1: En total desacuerdo'),
(46, 36, '2: Parcialmente en desacuerdo'),
(47, 36, '3: Ni de acuerdo ni en desacuerdo'),
(48, 36, '4: Parcialmente de acuerdo'),
(49, 36, '5: Totalmente de acuerdo'),
(50, 37, '1: En total desacuerdo'),
(51, 37, '2: Parcialmente en desacuerdo'),
(52, 37, '3: Ni de acuerdo ni en desacuerdo'),
(53, 37, '4: Parcialmente de acuerdo'),
(54, 37, '5: Totalmente de acuerdo'),
(55, 38, '1: En total desacuerdo'),
(56, 38, '2: Parcialmente en desacuerdo'),
(57, 38, '3: Ni de acuerdo ni en desacuerdo'),
(58, 38, '4: Parcialmente de acuerdo'),
(59, 38, '5: Totalmente de acuerdo'),
(60, 39, '1: En total desacuerdo'),
(61, 39, '2: Parcialmente en desacuerdo'),
(62, 39, '3: Ni de acuerdo ni en desacuerdo'),
(63, 39, '4: Parcialmente de acuerdo'),
(64, 39, '5: Totalmente de acuerdo'),
(65, 40, '1: En total desacuerdo'),
(66, 40, '2: Parcialmente en desacuerdo'),
(67, 40, '3: Ni de acuerdo ni en desacuerdo'),
(68, 40, '4: Parcialmente de acuerdo'),
(69, 40, '5: Totalmente de acuerdo'),
(70, 41, '1: En total desacuerdo'),
(71, 41, '2: Parcialmente en desacuerdo'),
(72, 41, '3: Ni de acuerdo ni en desacuerdo'),
(73, 41, '4: Parcialmente de acuerdo'),
(74, 41, '5: Totalmente de acuerdo'),
(75, 42, '1: En total desacuerdo'),
(76, 42, '2: Parcialmente en desacuerdo'),
(77, 42, '3: Ni de acuerdo ni en desacuerdo'),
(78, 42, '4: Parcialmente de acuerdo'),
(79, 42, '5: Totalmente de acuerdo'),
(80, 43, '1: En total desacuerdo'),
(81, 43, '2: Parcialmente en desacuerdo'),
(82, 43, '3: Ni de acuerdo ni en desacuerdo'),
(83, 43, '4: Parcialmente de acuerdo'),
(84, 43, '5: Totalmente de acuerdo'),
(85, 44, '1: En total desacuerdo'),
(86, 44, '2: Parcialmente en desacuerdo'),
(87, 44, '3: Ni de acuerdo ni en desacuerdo'),
(88, 44, '4: Parcialmente de acuerdo'),
(89, 44, '5: Totalmente de acuerdo'),
(90, 45, '1: En total desacuerdo'),
(91, 45, '2: Parcialmente en desacuerdo'),
(92, 45, '3: Ni de acuerdo ni en desacuerdo'),
(93, 45, '4: Parcialmente de acuerdo'),
(94, 45, '5: Totalmente de acuerdo'),
(95, 46, '1: En total desacuerdo'),
(96, 46, '2: Parcialmente en desacuerdo'),
(97, 46, '3: Ni de acuerdo ni en desacuerdo'),
(98, 46, '4: Parcialmente de acuerdo'),
(99, 46, '5: Totalmente de acuerdo'),
(100, 47, '1: En total desacuerdo'),
(101, 47, '2: Parcialmente en desacuerdo'),
(102, 47, '3: Ni de acuerdo ni en desacuerdo'),
(103, 47, '4: Parcialmente de acuerdo'),
(104, 47, '5: Totalmente de acuerdo'),
(105, 48, '1: En total desacuerdo'),
(106, 48, '2: Parcialmente en desacuerdo'),
(107, 48, '3: Ni de acuerdo ni en desacuerdo'),
(108, 48, '4: Parcialmente de acuerdo'),
(109, 48, '5: Totalmente de acuerdo'),
(110, 49, '1: En total desacuerdo'),
(111, 49, '2: Parcialmente en desacuerdo'),
(112, 49, '3: Ni de acuerdo ni en desacuerdo'),
(113, 49, '4: Parcialmente de acuerdo'),
(114, 49, '5: Totalmente de acuerdo'),
(115, 50, '1: En total desacuerdo'),
(116, 50, '2: Parcialmente en desacuerdo'),
(117, 50, '3: Ni de acuerdo ni en desacuerdo'),
(118, 50, '4: Parcialmente de acuerdo'),
(119, 50, '5: Totalmente de acuerdo'),
(120, 51, '1: En total desacuerdo'),
(121, 51, '2: Parcialmente en desacuerdo'),
(122, 51, '3: Ni de acuerdo ni en desacuerdo'),
(123, 51, '4: Parcialmente de acuerdo'),
(124, 51, '5: Totalmente de acuerdo'),
(125, 52, '00: Ninguna en absoluto'),
(126, 52, '01'),
(127, 52, '02'),
(128, 52, '03'),
(129, 52, '04'),
(130, 52, '05: Neutral'),
(131, 52, '06'),
(132, 52, '07'),
(133, 52, '08'),
(134, 52, '09'),
(135, 52, '10: Con toda seguridad'),
(136, 53, '1: En total desacuerdo'),
(137, 53, '2: Parcialmente en desacuerdo'),
(138, 53, '3: Ni de acuerdo ni en desacuerdo'),
(139, 53, '4: Parcialmente de acuerdo'),
(140, 53, '5: Totalmente de acuerdo'),
(141, 54, '1: En total desacuerdo'),
(142, 54, '2: Parcialmente en desacuerdo'),
(143, 54, '3: Ni de acuerdo ni en desacuerdo'),
(144, 54, '4: Parcialmente de acuerdo'),
(145, 54, '5: Totalmente de acuerdo'),
(146, 55, '1: En total desacuerdo'),
(147, 55, '2: Parcialmente en desacuerdo'),
(148, 55, '3: Ni de acuerdo ni en desacuerdo'),
(150, 55, '4: Parcialmente de acuerdo'),
(151, 55, '5: Totalmente de acuerdo');

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
(15, 'herrerii', 'dcd6a9b9ade60a3dd7d3a60df66929f7', 'herrerii', 'herrerii', 'herrerii@gmail.com', 1, 'b56fe'),
(17, 'Usabilidad', 'efedd1b436607dd61eeb392cda477480', 'Encuestas', 'de Usabilidad', 'dummy@email.com', 1, '5f357');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT de la tabla `ProjectUser`
--
ALTER TABLE `ProjectUser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT de la tabla `Question`
--
ALTER TABLE `Question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;
--
-- AUTO_INCREMENT de la tabla `Quiz`
--
ALTER TABLE `Quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT de la tabla `TextQuestionOption`
--
ALTER TABLE `TextQuestionOption`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;
--
-- AUTO_INCREMENT de la tabla `User`
--
ALTER TABLE `User`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
