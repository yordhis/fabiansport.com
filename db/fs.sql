-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-07-2022 a las 22:25:29
-- Versión del servidor: 10.4.18-MariaDB
-- Versión de PHP: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `fs`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carritos`
--

CREATE TABLE `carritos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_talla` int(11) NOT NULL,
  `cantidad` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `costo` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `talla` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `img` text COLLATE utf8_unicode_ci NOT NULL,
  `linea` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sexo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `categoria` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `marca` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `listado` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `carritos`
--

INSERT INTO `carritos` (`id`, `codigo`, `id_producto`, `id_talla`, `cantidad`, `costo`, `nombre`, `talla`, `color`, `img`, `linea`, `sexo`, `categoria`, `marca`, `listado`, `actualizado`) VALUES
(3, '1310000', 2, 3, '10', '10', 'mouse 2', 'l', 'Azul', 'foto.png', 'deportes', 'hombre', 'zapato', 'nike', '2021-07-21 22:36:15', '2021-07-21 22:36:15'),
(4, '1310000', 2, 3, '10', '10', 'mouse 2', 'l', 'Azul', 'foto.png', 'deportes', 'hombre', 'zapato', 'nike', '2021-07-21 22:36:15', '2021-07-21 22:36:15'),
(11, '1310001', 2, 3, '10', '10', 'mouse 2', 'l', 'Azul', 'foto.png', 'deportes', 'hombre', 'zapato', 'nike', '2021-07-23 18:20:14', '2021-07-23 18:20:14'),
(12, '1310001', 2, 3, '10', '10', 'mouse 2', 'l', 'Azul', 'foto.png', 'deportes', 'hombre', 'zapato', 'nike', '2021-07-23 18:20:14', '2021-07-23 18:20:14'),
(13, '1310001', 2, 3, '10', '10', 'mouse 2', 'l', 'Azul', 'foto.png', 'deportes', 'hombre', 'zapato', 'nike', '2021-07-23 18:20:14', '2021-07-23 18:20:14'),
(14, '1310001', 2, 3, '10', '10', 'yolo', 'l', 'Azul', 'foto.png', 'deportes', 'hombre', 'zapato', 'nike', '2021-07-23 18:22:10', '2021-07-23 18:22:10'),
(15, '1310001', 2, 8, '100', '10', 'mouse 2', 'p', 'marrón', 'foto.png', 'deportes', 'hombre', 'zapato', 'nike', '2021-07-23 18:22:10', '2021-07-23 18:22:10'),
(16, '1310001', 2, 3, '10', '10', 'yolo', 'l', 'Azul', 'foto.png', 'deportes', 'hombre', 'zapato', 'nike', '2021-07-23 18:25:06', '2021-07-23 18:25:06'),
(17, '1310001', 2, 3, '10', '10', 'mouse 2', 'l', 'Azul', 'foto.png', 'deportes', 'hombre', 'zapato', 'nike', '2021-07-23 18:25:06', '2021-07-23 18:25:06'),
(18, '1310009', 5, 66, '2', '1005', 'mouse 225656', 'm', 'azul', 'A004_87331626365734.png', 'Zapatos', 'Niños', 'calzado', 'addidas', '2021-09-06 15:52:45', '2021-09-06 15:52:45'),
(19, '1310010', 5, 66, '2', '1005', 'mouse 225656', 'm', 'azul', 'A004_87331626365734.png', 'Zapatos', 'Niños', 'calzado', 'addidas', '2021-09-06 16:00:52', '2021-09-06 16:00:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'calzado', '2020-10-19 03:27:59', '2020-10-19 03:27:59'),
(2, 'ropa', '2020-10-19 03:27:59', '2020-10-19 03:27:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colores`
--

CREATE TABLE `colores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `hexadecimal` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `listado` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `colores`
--

INSERT INTO `colores` (`id`, `nombre`, `hexadecimal`, `listado`, `actualizado`) VALUES
(1, 'Blanco', 'hhh125', '2020-10-26 19:12:56', '2020-10-26 19:12:56'),
(2, 'Negro', 'hhh124', '2020-10-26 19:12:56', '2020-10-26 19:12:56'),
(3, 'Azul', 'hhh123', '2020-10-26 19:12:56', '2020-10-26 19:12:56'),
(4, 'Marron', 'hhh122', '2020-10-26 19:12:56', '2020-10-26 19:12:56'),
(5, 'Gris', 'hhh121', '2020-10-26 19:12:56', '2020-10-26 19:12:56'),
(6, 'Verde', 'hhh120', '2020-10-26 19:12:56', '2020-10-26 19:12:56'),
(7, 'Naranja', 'ffffff', '2020-10-26 19:12:56', '2020-10-26 19:12:56'),
(8, 'Rosa', 'fffffg', '2020-10-26 19:12:56', '2020-10-26 19:12:56'),
(9, 'Purpura', 'ffff00', '2020-10-26 19:12:56', '2020-10-26 19:12:56'),
(10, 'Rojo', 'ffff78', '2020-10-26 19:12:56', '2020-10-26 19:12:56'),
(11, 'Plateado', '255255', '2020-10-26 19:12:56', '2020-10-26 19:12:56'),
(12, 'Amarillo', '250250', '2020-10-26 19:12:56', '2020-10-26 19:12:56'),
(13, 'Turquesa', '', '2020-10-26 19:12:56', '2020-10-26 19:12:56'),
(14, 'estoy dolido', '', '2020-12-29 21:40:09', '2020-12-29 21:40:09'),
(15, 'yordhis el papi', '', '2020-12-29 21:42:44', '2020-12-29 21:42:44'),
(16, 'verde / naranja', '', '2020-12-29 21:46:01', '2020-12-29 21:46:01'),
(20, 'rojo / verde', '', '2020-12-29 21:47:35', '2020-12-29 21:47:35'),
(21, 'raw white / grey one / active blue', '', '2020-12-31 18:45:58', '2020-12-31 18:45:58'),
(22, 'cloud white / core white / green', '', '2020-12-31 19:40:39', '2020-12-31 19:40:39'),
(23, 'chalk white / cloud white / bright blue', '', '2020-12-31 19:51:13', '2020-12-31 19:51:13'),
(24, 'football blue / football blue / football blue', '', '2020-12-31 20:41:52', '2020-12-31 20:41:52'),
(26, 'yellow / legacy gold / tech copper', '', '2020-12-31 21:07:47', '2020-12-31 21:07:47'),
(27, 'real magenta / cloud white / semi coral', '', '2020-12-31 21:22:50', '2020-12-31 21:22:50'),
(28, 'collegiate royal / cloud white / solar orange', '', '2020-12-31 21:25:46', '2020-12-31 21:25:46'),
(29, 'blanco / rojo', '', '2021-01-02 15:39:56', '2021-01-02 15:39:56'),
(30, 'azul - rojo', '', '2021-01-02 15:40:29', '2021-01-02 15:40:29'),
(31, 'cloud white / cloud white / real magenta', '', '2021-01-02 15:57:26', '2021-01-02 15:57:26'),
(32, 'trace maroon / legacy red / glory grey', '', '2021-01-02 16:09:59', '2021-01-02 16:09:59'),
(35, 'jkasdugyuioas', '', '2021-01-04 22:59:26', '2021-01-04 22:59:26'),
(36, 'cloud white / core black / cloud white', '', '2021-01-04 23:46:54', '2021-01-04 23:46:54'),
(37, 'blaco y negro', '', '2021-01-04 23:56:32', '2021-01-04 23:56:32'),
(38, 'core black / cloud white / core black', '', '2021-01-05 00:06:35', '2021-01-05 00:06:35'),
(39, 'core black / core black / core black', '', '2021-01-05 00:13:06', '2021-01-05 00:13:06'),
(40, 'core black / night metallic / cloud white', '', '2021-01-05 00:15:54', '2021-01-05 00:15:54'),
(41, 'cloud white / sharp blue / true orange', '', '2021-01-05 00:16:49', '2021-01-05 00:16:49'),
(42, 'grey five / silver metallic / signal green', '', '2021-01-05 00:18:18', '2021-01-05 00:18:18'),
(43, 'cloud white / core black / glow pink', '', '2021-01-05 00:21:22', '2021-01-05 00:21:22'),
(46, 'crew navy / cloud white / legend ink', '', '2021-01-05 00:25:24', '2021-01-05 00:25:24'),
(47, 'cloud white / core black / royal blue', '', '2021-01-05 00:26:35', '2021-01-05 00:26:35'),
(48, 'core black / grey six / dove grey', '', '2021-01-05 00:27:44', '2021-01-05 00:27:44'),
(49, 'legend ink / shock yellow / cloud white', '', '2021-01-05 00:30:37', '2021-01-05 00:30:37'),
(51, 'royal blue / cloud white / core black', '', '2021-01-05 00:33:35', '2021-01-05 00:33:35'),
(54, 'glow blue / glow blue / signal green', '', '2021-01-05 00:40:55', '2021-01-05 00:40:55'),
(55, 'grey six / core black / cloud white', '', '2021-01-05 00:44:29', '2021-01-05 00:44:29'),
(56, 'cloud white / core black / core black', '', '2021-01-05 00:45:22', '2021-01-05 00:45:22'),
(57, 'grey two / cloud white / grey six', '', '2021-01-05 00:46:19', '2021-01-05 00:46:19'),
(58, 'onix / onix / cloud white', '', '2021-01-05 00:49:35', '2021-01-05 00:49:35'),
(59, 'blue oxide', '', '2021-01-09 18:03:14', '2021-01-09 18:03:14'),
(60, 'black / multicolor', '', '2021-01-09 18:13:15', '2021-01-09 18:13:15'),
(63, 'legacy blue', '', '2021-01-09 18:21:42', '2021-01-09 18:21:42'),
(64, 'black / white', '', '2021-01-09 18:25:03', '2021-01-09 18:25:03'),
(65, 'crew navy / white', '', '2021-01-09 18:34:01', '2021-01-09 18:34:01'),
(66, 'grey six / black', '', '2021-01-09 18:35:53', '2021-01-09 18:35:53'),
(67, 'white / mystery ink', '', '2021-01-09 18:37:37', '2021-01-09 18:37:37'),
(68, 'white / pantone / dark blue', '', '2021-01-09 18:47:37', '2021-01-09 18:47:37'),
(69, 'real magenta / black / white', '', '2021-01-09 18:54:30', '2021-01-09 18:54:30'),
(70, 'white / solar red / glow blue / black', '', '2021-01-09 18:57:20', '2021-01-09 18:57:20'),
(71, 'white / black / solar red', '', '2021-01-09 18:58:08', '2021-01-09 18:58:08'),
(72, 'power pink / power berry', '', '2021-01-09 18:59:12', '2021-01-09 18:59:12'),
(73, 'tech indigo / tech indigo / white', '', '2021-01-09 19:02:35', '2021-01-09 19:02:35'),
(74, 'legend ink / legend ink / gold metallic', '', '2021-01-09 19:06:51', '2021-01-09 19:06:51'),
(75, 'grey five / grey five / core black', '', '2021-01-09 19:08:56', '2021-01-09 19:08:56'),
(76, 'sky tint / ash grey / signal green', '', '2021-01-09 19:09:56', '2021-01-09 19:09:56'),
(80, 'tech purple / silver metallic / cloud white', '', '2021-01-09 19:15:08', '2021-01-09 19:15:08'),
(81, 'halo silver / halo silver / dash grey', '', '2021-01-09 19:16:18', '2021-01-09 19:16:18'),
(82, 'cloud white / cloud white / orbit grey', '', '2021-01-09 19:18:02', '2021-01-09 19:18:02'),
(83, 'chalk white / chalk white / yellow tint', '', '2021-01-09 19:18:52', '2021-01-09 19:18:52'),
(84, 'legend ink / vapour pink / crystal white', '', '2021-01-09 19:19:59', '2021-01-09 19:19:59'),
(85, 'pink tint / cloud white / copper metallic', '', '2021-01-09 19:27:06', '2021-01-09 19:27:06'),
(88, 'cloud white / core black / pop', '', '2021-01-09 19:34:22', '2021-01-09 19:34:22'),
(89, 'cloud white / silver metallic / tactile blue', '', '2021-01-09 19:37:17', '2021-01-09 19:37:17'),
(90, 'crew navy / hazy blue', '', '2021-01-09 19:40:31', '2021-01-09 19:40:31'),
(91, 'multicolor', '', '2021-01-09 19:48:59', '2021-01-09 19:48:59'),
(92, 'off white', '', '2021-01-09 20:00:15', '2021-01-09 20:00:15'),
(93, 'bright cyan / shock pink / black', '', '2021-01-09 20:09:18', '2021-01-09 20:09:18'),
(94, 'legend ink / white', '', '2021-01-09 20:10:59', '2021-01-09 20:10:59'),
(95, 'screaming orange / black', '', '2021-01-09 20:11:16', '2021-01-09 20:11:16'),
(96, 'orbit grey / black / black', '', '2021-01-09 20:12:40', '2021-01-09 20:12:40'),
(97, 'vapour pink / white', '', '2021-01-09 20:14:34', '2021-01-09 20:14:34'),
(98, 'crew navy / crew navy / black', '', '2021-01-09 20:16:20', '2021-01-09 20:16:20'),
(99, 'signal green / signal green / black', '', '2021-01-09 20:17:05', '2021-01-09 20:17:05'),
(100, 'cloud white / cloud white / chalk purple', '', '2021-01-09 20:24:59', '2021-01-09 20:24:59'),
(101, 'grey two / core black / super pop', '', '2021-01-09 20:26:52', '2021-01-09 20:26:52'),
(102, 'clear pink / cloud white / clear lilac', '', '2021-01-09 20:35:07', '2021-01-09 20:35:07'),
(103, 'core black / cloud white / silver metallic', '', '2021-01-09 20:35:45', '2021-01-09 20:35:45'),
(104, 'royal blue / cloud white / vivid red', '', '2021-01-09 20:37:14', '2021-01-09 20:37:14'),
(105, 'cloud white / silver metallic / glow blue', '', '2021-01-09 20:38:29', '2021-01-09 20:38:29'),
(106, 'cloud white / hazy rose / hazy rose', '', '2021-01-09 20:41:23', '2021-01-09 20:41:23'),
(108, 'royal blue / core black / vivid red', '', '2021-01-09 21:08:46', '2021-01-09 21:08:46'),
(109, 'cloud white / cloud white / vivid red', '', '2021-01-09 21:10:19', '2021-01-09 21:10:19'),
(110, 'cloud white / vivid red / yellow', '', '2021-01-09 21:11:09', '2021-01-09 21:11:09'),
(111, 'cloud white / vivid green / rich mauve', '', '2021-01-09 21:11:55', '2021-01-09 21:11:55'),
(112, 'vivid red / black / white', '', '2021-01-09 21:18:16', '2021-01-09 21:18:16'),
(113, 'light grey heather / vivid red / white', '', '2021-01-09 21:19:34', '2021-01-09 21:19:34'),
(114, 'legend ink / royal blue', '', '2021-01-09 21:21:08', '2021-01-09 21:21:08'),
(115, 'grey six / white / black', '', '2021-01-09 21:21:46', '2021-01-09 21:21:46'),
(116, 'haze coral / noble purple / white', '', '2021-01-09 21:23:20', '2021-01-09 21:23:20'),
(117, 'scarlet / legend ink / white', '', '2021-01-09 21:24:22', '2021-01-09 21:24:22'),
(118, 'mystery ink / bold gold', '', '2021-01-09 21:27:36', '2021-01-09 21:27:36'),
(121, 'trace pink / multicolor / hazy rose', '', '2021-01-09 21:30:34', '2021-01-09 21:30:34'),
(122, 'glow pink / power pink / white', '', '2021-01-09 21:31:57', '2021-01-09 21:31:57'),
(123, 'sky tint / royal blue / white', '', '2021-01-09 21:33:22', '2021-01-09 21:33:22'),
(124, 'active red / black / white', '', '2021-01-09 21:34:51', '2021-01-09 21:34:51'),
(125, 'active purple / smoke lenses / shock red', '', '2021-01-09 21:36:25', '2021-01-09 21:36:25'),
(126, 'tech indigo / glory purple', '', '2021-01-09 21:39:14', '2021-01-09 21:39:14'),
(127, 'legend ink / signal cyan / white', '', '2021-01-09 21:40:38', '2021-01-09 21:40:38'),
(128, 'royal blue / chalk white / white', '', '2021-01-09 21:41:13', '2021-01-09 21:41:13'),
(129, 'raw khaki / trace olive', '', '2021-01-09 21:43:04', '2021-01-09 21:43:04'),
(131, 'vivid red / collegiate navy / blue', '', '2021-01-09 21:46:09', '2021-01-09 21:46:09'),
(132, 'haze coral', '', '2021-01-09 21:47:31', '2021-01-09 21:47:31'),
(133, 'pink tint', '', '2021-01-09 21:49:34', '2021-01-09 21:49:34'),
(134, 'black / wild pink / medium grey heather / white', '', '2021-01-09 21:51:06', '2021-01-09 21:51:06'),
(135, 'dark blue / cloud white / dark blue', '', '2021-01-09 21:53:29', '2021-01-09 21:53:29'),
(136, 'grey three / silver metallic / core black', '', '2021-01-09 21:55:41', '2021-01-09 21:55:41'),
(137, 'vapour pink / vapour pink / cloud white', '', '2021-01-09 21:56:26', '2021-01-09 21:56:26'),
(138, 'core black / cloud white / active gold', '', '2021-01-09 22:29:23', '2021-01-09 22:29:23'),
(139, 'cloud white / cloud white / royal blue', '', '2021-01-09 22:30:23', '2021-01-09 22:30:23'),
(140, 'cloud white / silver metallic / sky tint', '', '2021-01-09 22:36:02', '2021-01-09 22:36:02'),
(141, 'signal orange / solar gold / signal pink', '', '2021-01-09 22:38:57', '2021-01-09 22:38:57'),
(142, 'cloud white / shock pink / ray blue', '', '2021-01-09 22:44:24', '2021-01-09 22:44:24'),
(143, 'cloud white / core black / gold metallic', '', '2021-01-09 22:46:10', '2021-01-09 22:46:10'),
(144, 'cloud white / core black / charcoal solid grey', '', '2021-01-09 22:48:27', '2021-01-09 22:48:27'),
(145, 'cloud white / cloud white / cloud white', '', '2021-01-09 22:55:24', '2021-01-09 22:55:24'),
(146, 'cloud white / core black / hi-res red', '', '2021-01-09 22:56:55', '2021-01-09 22:56:55'),
(149, 'core black / green / cloud white', '', '2021-01-09 23:01:57', '2021-01-09 23:01:57'),
(150, 'blue / core black / cloud white', '', '2021-01-09 23:03:21', '2021-01-09 23:03:21'),
(151, 'red / core black / yellow', '', '2021-01-09 23:04:05', '2021-01-09 23:04:05'),
(152, 'carismatico', '', '2021-01-11 16:37:36', '2021-01-11 16:37:36'),
(153, 'raquel', '', '2021-01-16 02:31:03', '2021-01-16 02:31:03'),
(154, 'geselle', '', '2021-01-18 12:50:18', '2021-01-18 12:50:18'),
(176, 'color', '000fff', '2021-09-07 20:54:03', '2021-09-07 20:54:03'),
(177, 'pantera', '000fff', '2021-09-07 20:57:31', '2021-09-07 20:57:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_entrega`
--

CREATE TABLE `datos_entrega` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `departamento` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `provincia` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `distrito` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `direccion` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `telefono` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `referencia` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `correo` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `listado` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `datos_entrega`
--

INSERT INTO `datos_entrega` (`id`, `id_usuario`, `nombre`, `departamento`, `provincia`, `distrito`, `direccion`, `telefono`, `referencia`, `correo`, `listado`, `actualizado`) VALUES
(3, 39, 'don juan', 'bloque 12', 'china', 'aganistan', 'mi jardin', '+501 6547 689 551', 'al lado de la bodega', 'juan.100@gmail.com', '2021-07-17 08:52:35', '2021-07-17 08:52:35'),
(7, 44, 'don yordhis', 'bloque 12', 'china', 'aganistan', 'mi jardin', '+501 6547 689 551', 'al lado de la bodega', 'yordhis.10@gmail.com', '2021-07-20 16:46:53', '2021-07-20 16:46:53'),
(18, 101, '', '', '', '', '', '', '', 'entregaListoYa.s10@gmail.com', '2021-09-06 00:03:52', '2021-09-06 00:03:52'),
(19, 103, '', '', '', '', '', '', '', 'testGoogle.s10@gmail.com', '2021-09-06 00:34:55', '2021-09-06 00:34:55'),
(20, 104, '', '', '', '', '', '', '', 'testFacebook.s10@gmail.com', '2021-09-06 00:40:01', '2021-09-06 00:40:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_factura`
--

CREATE TABLE `datos_factura` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `correo` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `razon_social` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `departamento` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `provincia` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `distrito` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `direccion` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `telefono` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_documento` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_contribuyente` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `identificacion` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `listado` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `datos_factura`
--

INSERT INTO `datos_factura` (`id`, `id_usuario`, `correo`, `razon_social`, `departamento`, `provincia`, `distrito`, `direccion`, `telefono`, `tipo_documento`, `tipo_contribuyente`, `identificacion`, `listado`, `actualizado`) VALUES
(6, 44, 'yordhis.100@gmail.com', 'yordhis osuna', 'lima', 'lima', 'lomia', 'al lado de lomito', '+51 0235 88 99 1255', 'extranjero', 'natural', '24823972', '2021-07-20 16:33:04', '2021-07-20 16:33:04'),
(17, 101, 'entregaListoYa.s10@gmail.com', '', '', '', '', '', '', '', '', '', '2021-09-06 00:03:52', '2021-09-06 00:03:52'),
(18, 103, 'testGoogle.s10@gmail.com', '', '', '', '', '', '', '', '', '', '2021-09-06 00:34:55', '2021-09-06 00:34:55'),
(19, 104, 'testFacebook.s10@gmail.com', '', '', '', '', '', '', '', '', '', '2021-09-06 00:40:01', '2021-09-06 00:40:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `tipo_entrega` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `numero_envio` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 0,
  `metodo_pago` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `img` text COLLATE utf8_unicode_ci NOT NULL,
  `num_comprobante` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_pago` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `monto_pagado` float NOT NULL,
  `montoUSD` float NOT NULL,
  `titular` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `mayoria_edad` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `listado` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id`, `codigo`, `id_cliente`, `tipo_entrega`, `numero_envio`, `estatus`, `metodo_pago`, `img`, `num_comprobante`, `fecha_pago`, `monto_pagado`, `montoUSD`, `titular`, `mayoria_edad`, `listado`, `actualizado`) VALUES
(1, '1310000', 48, '', '', 0, '1', '1310000_96991626408808.png', '5478', '15-07-2021', 100, 0, 'yordhis', '1', '2021-07-16 04:13:28', '2021-07-16 04:13:28'),
(2, '1310001', 48, '', '', 0, '1', '1310001_95381626410914.png', '5478', '15-07-2021', 100, 0, 'yordhis', '1', '2021-07-16 04:48:34', '2021-07-16 04:48:34'),
(3, '1310002', 48, 'Envio', '13125478001', 1, '1', '1310002_64771626411144.png', '5478', '15-07-2021', 100, 0, 'yordhis', '1', '2021-07-16 04:52:24', '2021-07-16 04:52:24'),
(5, '1310003', 44, '', '', 0, 'x', '1310003_21671630942619.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-06 15:37:00', '2021-09-06 15:37:00'),
(6, '1310004', 44, '', '', 0, 'x', '1310004_42171630942734.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-06 15:38:54', '2021-09-06 15:38:54'),
(7, '1310005', 44, '', '', 0, 'x', '1310005_1221630942954.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-06 15:42:34', '2021-09-06 15:42:34'),
(8, '1310006', 44, '', '', 0, 'x', '1310006_43001630943111.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-06 15:45:11', '2021-09-06 15:45:11'),
(9, '1310007', 44, '', '', 0, 'x', '1310007_14471630943207.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-06 15:46:47', '2021-09-06 15:46:47'),
(10, '1310008', 44, '', '', 0, 'x', '1310008_68891630943509.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-06 15:51:49', '2021-09-06 15:51:49'),
(11, '1310009', 44, '', '', 0, 'x', '1310009_60471630943565.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-06 15:52:45', '2021-09-06 15:52:45'),
(12, '1310010', 44, 'MRW 006985', '00001245288', 1, 'x', '1310010_49381630944052.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-06 16:00:52', '2021-09-06 16:00:52'),
(13, '1310011', 44, '', '', 0, 'x', '1310011_72111630979095.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-07 01:44:55', '2021-09-07 01:44:55'),
(14, '1310012', 44, '', '', 0, 'x', '1310012_96211630979435.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-07 01:50:35', '2021-09-07 01:50:35'),
(15, '1310013', 44, '', '', 0, 'x', '1310013_49141630980895.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-07 02:14:56', '2021-09-07 02:14:56'),
(16, '1310014', 44, '', '', 0, 'x', '1310014_30021630980969.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-07 02:16:09', '2021-09-07 02:16:09'),
(17, '1310015', 44, '', '', 0, 'x', '1310015_89031630981255.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-07 02:20:56', '2021-09-07 02:20:56'),
(18, '1310016', 44, '', '', 0, 'x', '1310016_40071630981473.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-07 02:24:33', '2021-09-07 02:24:33'),
(19, '1310017', 44, '', '', 0, 'x', '1310017_34341630982615.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-07 02:43:35', '2021-09-07 02:43:35'),
(20, '1310018', 44, '', '', 0, 'x', '1310018_28681630982644.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-07 02:44:04', '2021-09-07 02:44:04'),
(21, '1310019', 44, '', '', 0, 'x', '1310019_97231630984179.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-07 03:09:39', '2021-09-07 03:09:39'),
(22, '1310020', 44, '', '', 0, 'x', '1310020_93941632376182.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-23 05:49:42', '2021-09-23 05:49:42'),
(23, '1310021', 44, '', '', 0, 'x', '1310021_56221632376250.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-23 05:50:50', '2021-09-23 05:50:50'),
(24, '1310022', 44, '', '', 0, 'x', '1310022_3291632376499.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-23 05:54:59', '2021-09-23 05:54:59'),
(25, '1310023', 44, '', '', 0, 'x', '1310023_10171632376577.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-23 05:56:17', '2021-09-23 05:56:17'),
(26, '1310024', 44, '', '', 0, 'x', '1310024_34931632376718.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-23 05:58:38', '2021-09-23 05:58:38'),
(27, '1310025', 44, '', '', 0, 'x', '1310025_96641632376756.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-23 05:59:16', '2021-09-23 05:59:16'),
(28, '1310026', 44, '', '', 0, 'x', '1310026_56661632376877.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-23 06:01:17', '2021-09-23 06:01:17'),
(29, '1310027', 44, '', '', 0, 'x', '1310027_61081632426281.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-23 19:44:41', '2021-09-23 19:44:41'),
(30, '1310028', 44, '', '', 0, 'x', '1310028_80341632426311.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-23 19:45:11', '2021-09-23 19:45:11'),
(31, '1310029', 44, '', '', 0, 'x', '1310029_56941632426368.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-23 19:46:08', '2021-09-23 19:46:08'),
(32, '1310030', 44, '', '', 0, 'x', '1310030_31941632426384.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-23 19:46:24', '2021-09-23 19:46:24'),
(33, '1310031', 44, '', '', 0, 'x', '1310031_73851632522169.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-24 22:22:49', '2021-09-24 22:22:49'),
(34, '1310032', 44, '', '', 0, 'x', '1310032_52551632522302.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-24 22:25:02', '2021-09-24 22:25:02'),
(35, '1310033', 44, '', '', 0, 'x', '1310033_11861632522410.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-24 22:26:50', '2021-09-24 22:26:50'),
(36, '1310034', 44, '', '', 0, 'x', '1310034_64811632523062.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-24 22:37:42', '2021-09-24 22:37:42'),
(37, '1310035', 44, '', '', 0, 'x', '1310035_46861632523460.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-24 22:44:20', '2021-09-24 22:44:20'),
(38, '1310036', 44, '', '', 0, 'x', '1310036_61421632523933.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-24 22:52:13', '2021-09-24 22:52:13'),
(39, '1310037', 44, '', '', 0, 'x', '1310037_51621632524156.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-24 22:55:56', '2021-09-24 22:55:56'),
(40, '1310038', 44, '', '', 0, 'x', '1310038_23331632524362.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-24 22:59:22', '2021-09-24 22:59:22'),
(41, '1310039', 44, '', '', 0, 'x', '1310039_31021632524407.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-24 23:00:07', '2021-09-24 23:00:07'),
(42, '1310040', 44, '', '', 0, 'x', '1310040_57071632524670.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-24 23:04:30', '2021-09-24 23:04:30'),
(43, '1310041', 44, '', '', 0, 'x', '1310041_45421632524943.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-24 23:09:03', '2021-09-24 23:09:03'),
(44, '1310042', 44, '', '', 0, 'x', '1310042_461632525852.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-24 23:24:12', '2021-09-24 23:24:12'),
(45, '1310043', 44, '', '', 0, 'x', '1310043_1281632525862.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-24 23:24:22', '2021-09-24 23:24:22'),
(46, '1310044', 44, '', '', 0, 'x', '1310044_98991632525984.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-24 23:26:24', '2021-09-24 23:26:24'),
(47, '1310045', 44, '', '', 0, 'x', '1310045_22001632525992.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-24 23:26:32', '2021-09-24 23:26:32'),
(48, '1310046', 44, '', '', 0, 'x', '1310046_87291632526191.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-24 23:29:51', '2021-09-24 23:29:51'),
(49, '1310047', 44, '', '', 0, 'x', '1310047_55571632526219.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-24 23:30:19', '2021-09-24 23:30:19'),
(50, '1310048', 44, '', '', 0, 'x', '1310048_75521632526305.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-24 23:31:45', '2021-09-24 23:31:45'),
(51, '1310049', 44, '', '', 0, 'x', '1310049_90641632529191.png', '124578658224823972', '06-09-2021', 10, 10, 'yordhis', '1', '2021-09-25 00:19:51', '2021-09-25 00:19:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos`
--

CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_dad` int(11) NOT NULL,
  `descuento` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `codigo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `costo` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `img` varchar(350) COLLATE utf8_unicode_ci NOT NULL,
  `listado` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `favoritos`
--

INSERT INTO `favoritos` (`id`, `id_usuario`, `id_producto`, `id_dad`, `descuento`, `codigo`, `nombre`, `costo`, `img`, `listado`, `actualizado`) VALUES
(1, 44, 35, 0, '', '', 'franela termica 10', '45', '16251625004457.jpg', '2021-07-20 16:29:15', '2021-07-20 16:29:15'),
(2, 44, 36, 0, '', '', 'franela termica 10', '45', '16251625004457.jpg', '2021-07-20 16:31:56', '2021-07-20 16:31:56'),
(3, 44, 36, 0, '', '', 'franela termica 10', '45', '16251625004457.jpg', '2021-09-06 02:09:31', '2021-09-06 02:09:31'),
(4, 49, 36, 0, '', '', 'franela termica 10', '45', '16251625004457.jpg', '2021-09-06 02:13:51', '2021-09-06 02:13:51'),
(5, 44, 3, 1, '', 'xxxx', 'tal', '100', 'foto', '2021-09-06 03:09:17', '2021-09-06 03:09:17'),
(8, 44, 5, 11, '', 'xxxx', 'tal', '100', 'foto', '2021-09-06 03:53:26', '2021-09-06 03:53:26'),
(9, 44, 5, 0, '', 'xxxx', 'tal', '100', 'foto', '2021-09-06 03:56:26', '2021-09-06 03:56:26'),
(10, 44, 5, 11, '12', 'xxxx', 'tal', '100', 'foto', '2021-09-06 04:06:26', '2021-09-06 04:06:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `generos`
--

CREATE TABLE `generos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `generos`
--

INSERT INTO `generos` (`id`, `nombre`) VALUES
(1, 'Niños'),
(2, 'Hombre'),
(3, 'Mujer'),
(4, 'Unisex');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `info_entrega`
--

CREATE TABLE `info_entrega` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `departamento` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `provincia` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `distrito` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `direccion` varchar(350) COLLATE utf8_unicode_ci NOT NULL,
  `referencia` varchar(350) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
  `telefono` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `listado` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `info_entrega`
--

INSERT INTO `info_entrega` (`id`, `codigo`, `nombre`, `departamento`, `provincia`, `distrito`, `direccion`, `referencia`, `telefono`, `listado`, `actualizado`) VALUES
(4, '1310003', 'yordhis', 'bloque 11', 'china', 'aganistan', 'mi jardin', 'al lado de la bodega', '+501 6547 689 541', '2021-07-17 06:01:21', '2021-07-17 06:01:21'),
(6, '1310002', 'don juan', 'bloque 12', 'china', 'aganistan', 'mi jardin', 'al lado de la bodega', '+501 6547 689 551', '2021-07-17 06:27:10', '2021-07-17 06:27:10'),
(7, '1310000', 'don juan', 'bloque 12', 'china', 'aganistan', 'mi jardin', 'al lado de la bodega', '+501 6547 689 551', '2021-07-21 22:34:48', '2021-07-21 22:34:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `info_factura`
--

CREATE TABLE `info_factura` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `correo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `razon_social` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `departamento` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `provincia` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `distrito` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `direccion` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_documento` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_contribuyente` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `identificacion` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `listado` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `info_factura`
--

INSERT INTO `info_factura` (`id`, `codigo`, `correo`, `razon_social`, `departamento`, `provincia`, `distrito`, `direccion`, `tipo_documento`, `tipo_contribuyente`, `identificacion`, `telefono`, `listado`, `actualizado`) VALUES
(1, '1310001', 'yordhis.10@gmail.com', 'Yordhis osuna', 'lima', 'limo', 'lomito', 'al lado de lomito', 'nacional', 'natural', '24823972', '+51 0235 88 99 123', '2021-07-17 03:33:07', '2021-07-17 03:33:07'),
(3, '1310002', 'juan.100@gmail.com', 'Luis22 osuna', 'lima', 'lima', 'lomia', 'al lado de lomito', 'extranjero', 'natural', '30256897', '+51 0235 88 99 1255', '2021-07-17 05:13:32', '2021-07-17 05:13:32'),
(4, '1310000', 'juan.100@gmail.com', 'Luis22 osuna', 'lima', 'lima', 'lomia', 'al lado de lomito', 'extranjero', 'natural', '30256897', '+51 0235 88 99 1255', '2021-07-21 22:33:35', '2021-07-21 22:33:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lineas`
--

CREATE TABLE `lineas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `lineas`
--

INSERT INTO `lineas` (`id`, `nombre`) VALUES
(1, 'Zapatos'),
(2, 'Ropa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `img` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `listado` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`id`, `nombre`, `img`, `listado`, `actualizado`) VALUES
(1, 'addidas', 'addidas.jpg', '2020-10-19 03:27:09', '2020-10-19 03:27:09'),
(2, 'cat', 'cat.jpg', '2020-10-19 03:27:09', '2020-10-19 03:27:09'),
(3, 'converse', 'converse.jpg', '2020-11-03 21:58:26', '2020-11-03 21:58:26'),
(4, 'dc', 'dc.jpg', '2020-11-03 21:58:26', '2020-11-03 21:58:26'),
(5, 'hi-tec', 'hitec.jpg', '2020-11-03 21:58:26', '2020-11-03 21:58:26'),
(6, 'marrel', 'marrel.jpg', '2020-11-03 21:58:26', '2020-11-03 21:58:26'),
(7, 'nike', 'nike.jpg', '2020-11-03 21:58:26', '2020-11-03 21:58:26'),
(8, 'reebok', 'reebok.jpg', '2020-11-03 21:58:26', '2020-11-03 21:58:26'),
(9, 'salamon', 'salomon.jpg', '2020-11-03 21:58:26', '2020-11-03 21:58:26'),
(10, 'umbro', 'umbro.jpg', '2020-11-03 21:58:26', '2020-11-03 21:58:26'),
(11, 'columbia', 'columbia.jpg', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'mammut', 'mammut.jpg', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `id_marca` int(11) NOT NULL,
  `id_linea` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_genero` int(11) NOT NULL,
  `id_descuento` varchar(11) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `descuento` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `costo` float NOT NULL,
  `descripcion` text COLLATE utf8_unicode_ci NOT NULL,
  `caracteristicas` text COLLATE utf8_unicode_ci NOT NULL,
  `img` text COLLATE utf8_unicode_ci NOT NULL,
  `id_color` int(11) NOT NULL,
  `es_padre` tinyint(1) NOT NULL DEFAULT 0,
  `mi_padre` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'sin padre',
  `oferta` varchar(11) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `clic` int(111) NOT NULL DEFAULT 0,
  `stock` int(11) NOT NULL DEFAULT 0,
  `listado` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo`, `nombre`, `id_marca`, `id_linea`, `id_categoria`, `id_genero`, `id_descuento`, `descuento`, `costo`, `descripcion`, `caracteristicas`, `img`, `id_color`, `es_padre`, `mi_padre`, `oferta`, `clic`, `stock`, `listado`, `actualizado`) VALUES
(2, 'A001', 'mouse 2', 2, 1, 1, 2, '1', '0', 1000, 'info de acero', 'info y resistentes', 'A001_60441626365640.png,A001_79211626365640.png,A001_24931626365640.png', 1, 0, 'A002', '1', 1, 0, '2021-07-15 16:14:00', '2021-07-15 16:14:00'),
(3, 'A002', 'mouse 2', 2, 1, 1, 2, '1', '0', 1000, 'info de acero', 'info y resistentes', 'A002_90611626365694.png,A002_47481626365694.png,A002_95201626365694.png', 2, 1, 'A002', '1', 0, 0, '2021-07-15 16:14:54', '2021-07-15 16:14:54'),
(9, '55A008', 'telefono', 2, 1, 1, 2, '1', '1', 10, 'info de acero', 'info y resistentes', 'Sin imagenes', 5, 1, '55A008', '1', 0, 110, '2021-09-07 17:45:54', '2021-09-07 17:45:54'),
(11, 'A009', 'telefono', 2, 1, 1, 2, '1', '1', 10, 'info de acero', 'info y resistentes', 'Sin imagenes', 5, 1, '55A008', '1', 0, 20, '2021-12-08 03:52:49', '2021-12-08 03:52:49'),
(12, 'A010', 'telefono', 2, 1, 1, 2, '1', '1', 10, 'info de acero', 'info y resistentes', 'Sin imagenes', 8, 1, '55A008', '1', 0, 20, '2021-12-08 03:59:34', '2021-12-08 03:59:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tallas`
--

CREATE TABLE `tallas` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `cantidad` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `id_producto` int(11) NOT NULL,
  `listado` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `tallas`
--

INSERT INTO `tallas` (`id`, `codigo`, `nombre`, `cantidad`, `id_producto`, `listado`, `actualizado`) VALUES
(9, 'A002', 'x1ll', '0', 3, '2021-07-15 16:14:54', '2021-07-15 16:14:54'),
(71, 'A008', 's100', '10', 9, '2021-09-07 17:45:54', '2021-09-07 17:45:54'),
(72, 'A008', 'm', '10', 9, '2021-09-07 17:45:54', '2021-09-07 17:45:54'),
(73, 'A008', 'l', '10', 9, '2021-09-07 17:45:54', '2021-09-07 17:45:54'),
(74, 'A002', 'x1ll', '0', 2, '2021-07-15 16:14:54', '2021-07-15 16:14:54'),
(75, 'A008', 's100', '0', 2, '2021-09-07 17:45:54', '2021-09-07 17:45:54'),
(76, 'A008', 'm', '0', 3, '2021-09-07 17:45:54', '2021-09-07 17:45:54'),
(77, 'A008', 'l', '0', 3, '2021-09-07 17:45:54', '2021-09-07 17:45:54'),
(78, 'A009', 's100', '10', 11, '2021-12-08 03:52:49', '2021-12-08 03:52:49'),
(79, 'A009', 'm', '10', 11, '2021-12-08 03:52:49', '2021-12-08 03:52:49'),
(80, 'A010', 's100', '10', 12, '2021-12-08 03:59:34', '2021-12-08 03:59:34'),
(81, 'A010', 'm', '10', 12, '2021-12-08 03:59:34', '2021-12-08 03:59:34'),
(82, 'A009', 's100', '10', 9, '2021-12-08 04:10:41', '2021-12-08 04:10:41'),
(83, 'A009', 'm', '10', 9, '2021-12-08 04:10:41', '2021-12-08 04:10:41'),
(84, 'A008', 's100', '10', 9, '2021-12-08 04:12:00', '2021-12-08 04:12:00'),
(85, 'A008', 'm', '10', 9, '2021-12-08 04:12:00', '2021-12-08 04:12:00'),
(86, '5A008', 's100', '10', 9, '2021-12-08 04:13:04', '2021-12-08 04:13:04'),
(87, '5A008', 'm', '10', 9, '2021-12-08 04:13:05', '2021-12-08 04:13:05'),
(88, '55A008', 's100', '10', 9, '2021-12-08 04:14:23', '2021-12-08 04:14:23'),
(89, '55A008', 'm', '10', 9, '2021-12-08 04:14:23', '2021-12-08 04:14:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombres` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `correo` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `clave` text COLLATE utf8_unicode_ci NOT NULL,
  `tipo` varchar(11) COLLATE utf8_unicode_ci NOT NULL DEFAULT '2',
  `usar_dato_factura` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `adulto` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `id_google` text COLLATE utf8_unicode_ci NOT NULL,
  `id_facebook` text COLLATE utf8_unicode_ci NOT NULL,
  `img_google` text COLLATE utf8_unicode_ci NOT NULL,
  `img_facebook` text COLLATE utf8_unicode_ci NOT NULL,
  `tasa` double NOT NULL,
  `token` text COLLATE utf8_unicode_ci NOT NULL,
  `listado` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombres`, `correo`, `clave`, `tipo`, `usar_dato_factura`, `adulto`, `id_google`, `id_facebook`, `img_google`, `img_facebook`, `tasa`, `token`, `listado`, `actualizado`) VALUES
(1, 'admin', 'administrador', '202cb962ac59075b964b07152d234b70', '1', '', '', '', '', '', '', 4000000.13, '', '2021-01-25 20:13:09', '2021-01-25 20:13:09'),
(39, 'rommer martinez', 'mundoenweb@gmail.com', '', '2', '1', '1', '103482152107423960217', '0', 'https://lh3.googleusercontent.com/a-/AOh14Gip8oA-79yxyt2O37rVrAX7EvWJxTFz4YVKx_LLUg=s96-c', '', 0, '', '2021-01-25 21:04:05', '2021-01-25 21:04:05'),
(44, 'Yordhis Osuna', 'yordhis.10@gmail.com', '202cb962ac59075b964b07152d234b70', '2', '', '', '0', '3606502186053293', '', 'https://platform-lookaside.fbsbx.com/platform/profilepic/?asid=3606502186053293&height=50&width=50&ext=1614315060&hash=AeQXiZmkk0tuNlsoZho', 0, 'd84138a816148db668ae2fe6a7660b72', '2021-01-27 04:51:00', '2021-01-27 04:51:00'),
(101, '', 'entregaListoYa.s10@gmail.com', '25d55ad283aa400af464c76d713c07ad', '2', '', '', '', '', '', '', 0, '', '2021-09-06 00:03:52', '2021-09-06 00:03:52'),
(102, 'camelon', 'camelon715.s10@gmail.com', '', '2', '', '', '12345678', '0', 'foto.png', '', 0, '', '2021-09-06 00:14:50', '2021-09-06 00:14:50'),
(103, 'camelon', 'testGoogle.s10@gmail.com', '', '2', '', '', '12345679', '0', 'foto.png', '', 0, '', '2021-09-06 00:34:55', '2021-09-06 00:34:55'),
(104, 'camelon', 'testFacebook.s10@gmail.com', '', '2', '', '', '0', '12345679', '', 'foto.png', 0, '', '2021-09-06 00:40:01', '2021-09-06 00:40:01');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carritos`
--
ALTER TABLE `carritos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `colores`
--
ALTER TABLE `colores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `datos_entrega`
--
ALTER TABLE `datos_entrega`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `datos_factura`
--
ALTER TABLE `datos_factura`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `generos`
--
ALTER TABLE `generos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `info_entrega`
--
ALTER TABLE `info_entrega`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`codigo`);

--
-- Indices de la tabla `info_factura`
--
ALTER TABLE `info_factura`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`codigo`);

--
-- Indices de la tabla `lineas`
--
ALTER TABLE `lineas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_marca` (`id_marca`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `id_genero` (`id_genero`),
  ADD KEY `colores` (`id_color`),
  ADD KEY `linea` (`id_linea`);

--
-- Indices de la tabla `tallas`
--
ALTER TABLE `tallas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_color` (`id_producto`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carritos`
--
ALTER TABLE `carritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `colores`
--
ALTER TABLE `colores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- AUTO_INCREMENT de la tabla `datos_entrega`
--
ALTER TABLE `datos_entrega`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `datos_factura`
--
ALTER TABLE `datos_factura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `generos`
--
ALTER TABLE `generos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `info_entrega`
--
ALTER TABLE `info_entrega`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `info_factura`
--
ALTER TABLE `info_factura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `lineas`
--
ALTER TABLE `lineas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `tallas`
--
ALTER TABLE `tallas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `datos_entrega`
--
ALTER TABLE `datos_entrega`
  ADD CONSTRAINT `datos_entrega_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `datos_factura`
--
ALTER TABLE `datos_factura`
  ADD CONSTRAINT `datos_factura_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_10` FOREIGN KEY (`id_genero`) REFERENCES `generos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `productos_ibfk_11` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `productos_ibfk_7` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `productos_ibfk_8` FOREIGN KEY (`id_linea`) REFERENCES `lineas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `productos_ibfk_9` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tallas`
--
ALTER TABLE `tallas`
  ADD CONSTRAINT `tallas_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
