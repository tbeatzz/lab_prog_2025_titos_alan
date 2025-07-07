-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-07-2025 a las 17:55:42
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
-- Base de datos: `lp_2025`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(11, 'Botas'),
(9, 'Buzos'),
(2, 'camperas'),
(12, 'Gorras'),
(1, 'Guantes'),
(8, 'Jeans'),
(7, 'Pantalones'),
(4, 'remeras'),
(10, 'Zapatillas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `codigo` varchar(25) NOT NULL,
  `descripcion` text NOT NULL,
  `categoriaId` int(10) UNSIGNED NOT NULL,
  `precio` float(12,2) NOT NULL,
  `stock` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `codigo`, `descripcion`, `categoriaId`, `precio`, `stock`) VALUES
(1, 'remera negra adidas', 'REMN', 'remera negra', 4, 100.00, 2),
(10, 'Campera Rompeviento', 'CMP001', 'Campera liviana para el viento', 2, 25000.00, 15),
(11, 'Guantes de Cuero', 'GNT001', 'Guantes térmicos de cuero', 1, 8000.00, 30),
(12, 'Remera Básica Blanca', 'REM001', 'Remera de algodón blanca', 4, 3500.00, 50),
(13, 'Remera Estampada', 'REM002', 'Remera con diseño estampado', 4, 4500.00, 40),
(14, 'Pantalón Cargo', 'PAN001', 'Pantalón cargo con múltiples bolsillos', 7, 12000.00, 20),
(15, 'Jeans Skinny', 'JNS001', 'Jeans azul ajustados', 8, 9500.00, 25),
(16, 'Buzo Oversize', 'BUZ001', 'Buzo oversize de algodón', 9, 15000.00, 18),
(17, 'Zapatillas Urbanas', 'ZAP001', 'Zapatillas urbanas de lona', 10, 18000.00, 22),
(18, 'Botas de montaña', 'BOT001', 'Botas impermeables para montaña', 11, 28000.00, 10),
(19, 'Gorra Trucker', 'GOR001', 'Gorra estilo trucker', 12, 4000.00, 35),
(20, 'Campera de Cuero', 'CMP002', 'Campera negra de cuero', 2, 45000.00, 8),
(21, 'Guantes de Lana', 'GNT002', 'Guantes de lana polar', 1, 3000.00, 40);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `cuenta` varchar(20) NOT NULL,
  `perfil` enum('Administrador','Operador') NOT NULL,
  `clave` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `estado` tinyint(1) NOT NULL,
  `fechaAlta` date NOT NULL,
  `resetPass` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `apellido`, `nombres`, `cuenta`, `perfil`, `clave`, `correo`, `estado`, `fechaAlta`, `resetPass`) VALUES
(1, 'Titos', 'Alan', 'titosalan', 'Administrador', '$2y$10$8qocFvdh7VlS8Qk01fFk3eef6qE4fqj9o4RE4VWaMmK824mUJYHcW', 'titosalan@gmail.com', 1, '2025-06-30', 0),
(5, 'operador', 'prueba', 'operadorprueba', 'Operador', '$2y$10$vVfS01rm5QdKjRTBDOssKuBn.petAgzcDIQ4x9j3q54qA3JkpM2BS', 'operador@gmail.com', 1, '2025-07-06', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categorias_unique` (`nombre`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `productos_unique` (`codigo`),
  ADD UNIQUE KEY `productos_nombre_IDX` (`nombre`,`categoriaId`) USING BTREE,
  ADD KEY `productos_categorias_FK` (`categoriaId`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuarios_unique` (`cuenta`),
  ADD UNIQUE KEY `usuarios_unique_1` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_categorias_FK` FOREIGN KEY (`categoriaId`) REFERENCES `categorias` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
