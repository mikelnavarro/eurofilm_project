-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-05-2026 a las 22:36:55
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
-- Base de datos: `eurofilm_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `directors`
--

CREATE TABLE `directors` (
  `id` int(11) NOT NULL,
  `tmdb_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lists`
--

CREATE TABLE `lists` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `visibility` varchar(255) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lists`
--

INSERT INTO `lists` (`id`, `title`, `visibility`, `createdAt`, `user_id`) VALUES
(1, 'Favoritos', NULL, '2026-05-10 11:49:39', 2),
(2, 'Favoritos', NULL, '2026-05-11 09:56:02', NULL),
(3, 'Favoritos', NULL, '2026-05-11 10:09:46', 3),
(4, 'Favoritos', NULL, '2026-05-11 11:37:16', 4),
(5, 'Favoritos', NULL, '2026-05-11 19:28:54', 5),
(6, 'Favoritos', NULL, '2026-05-11 19:43:58', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `list_movies`
--

CREATE TABLE `list_movies` (
  `movie_id` int(11) NOT NULL,
  `list_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `list_movies`
--

INSERT INTO `list_movies` (`movie_id`, `list_id`) VALUES
(6, 1),
(7, 1),
(3, 2),
(10, 2),
(12, 2),
(14, 2),
(16, 2),
(17, 2),
(19, 2),
(1, 3),
(3, 3),
(12, 3),
(13, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `tmdb_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `poster_path` varchar(255) DEFAULT NULL,
  `director_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movies`
--

INSERT INTO `movies` (`id`, `tmdb_id`, `title`, `release_date`, `poster_path`, `director_id`) VALUES
(1, 1314481, 'El diablo viste de Prada 2', '2026-04-29', '/sz687EF7yJMS4VNEdHKT9ebkhA9.jpg', NULL),
(3, 1226863, 'Super Mario Galaxy la película', '2026-04-01', '/4Js0gYWxuvTN6b8iAaSF1cSQzBs.jpg', NULL),
(4, 1601797, '¿Quieres ser mi novia?', '2026-02-12', '/oscW8xV8EhRYj7iAhyVlBohKqxo.jpg', NULL),
(5, 1523145, 'Tu corazón se romperá', '2026-03-26', '/iGpMm603GUKH2SiXB2S5m4sZ17t.jpg', NULL),
(6, 1007757, 'Intercambiados', '2026-05-01', '/i89vUDwNhAEWUZSFYITSiv1RIbK.jpg', NULL),
(7, 22512, 'Sexo, pudor y lágrimas', '1999-06-18', '/1j3TiCXSguHi3IGumXKpQymvUKH.jpg', NULL),
(8, 1613798, 'Venganza', '2026-02-26', '/ggJGx8fwwy21gEIXYusIlHcUn8z.jpg', NULL),
(9, 83533, 'Avatar: Fuego y ceniza', '2025-12-17', '/4n1U0Mwn7djux6VKNYDRWPgS2x6.jpg', NULL),
(10, 10867, 'Malèna', '2000-10-27', '/p1DmuHTnvhsWFvWX0xnMWrLBVZ5.jpg', NULL),
(11, 1630423, 'Sangre asesina', '2026-05-06', '/16TvbhCP8CfiGEbxcbtQLYWO6WG.jpg', NULL),
(12, 931285, 'Mortal Kombat II', '2026-05-06', '/ivVKHht5jutNGnObn1y5sSDrAXn.jpg', NULL),
(13, 1239198, 'Mi querida señorita', '2026-04-17', '/3Od2J0JICLLMQfQAUbpCJg82HYg.jpg', NULL),
(14, 1318447, 'Depredador dominante', '2026-04-24', '/mV7uFS1U8iP0p4CcXZp4znBVpch.jpg', NULL),
(15, 1933, 'Los otros', '2001-08-02', '/u03kblxsv3zar3Uqt6sRfICU9L6.jpg', NULL),
(16, 1283, 'Torrente, el brazo tonto de la ley', '1998-03-13', '/bYAqKRiDVL0f38AoUKpDI32oY5r.jpg', NULL),
(17, 1038918, '美女奉行 おんな牢秘抄II', '1995-09-21', '/i3Ouy0rMUHyKeHZV3PYl106OSwe.jpg', NULL),
(18, 687163, 'Proyecto Salvación', '2026-03-15', '/7lwOTxajURKEWO6gI370NTrVdBO.jpg', NULL),
(19, 1439930, 'Marvel Television presenta The Punisher: One Last Kill', '2026-05-12', '/qkyqQqQN8HAkLezR6xWTYzz6Icv.jpg', NULL),
(20, 185417, 'Mi querida señorita', '1972-02-17', '/4LkvQraMTioGvjJxHoyaI3KVVrk.jpg', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movie_providers`
--

CREATE TABLE `movie_providers` (
  `movie_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `country` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `providers`
--

CREATE TABLE `providers` (
  `id` int(11) NOT NULL,
  `tmdb_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `movie_id` int(11) DEFAULT NULL,
  `rating` decimal(3,1) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `visibility` enum('Publica','Privada') DEFAULT NULL,
  `spoiler` tinyint(1) DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `movie_id`, `rating`, `title`, `comment`, `visibility`, `spoiler`, `createdAt`) VALUES
(12, 2, 1, 10.0, 'Muy buena peli', 'Me encanta Merly Streep. La mejor actriz de HOLLYWOOD. La mejor de todas las películas desde \'Los Puentes de Madison\'. Aquí lo hace bien, Miranda imitación de Anna Wintour, y Andy, Anne Hathaway, espectacular\r\n\r\n', 'Publica', 0, '2026-05-10 19:50:24'),
(13, NULL, 3, 5.0, 'Bonita peli para entusiasmados del videojuego', '', 'Publica', 0, '2026-05-11 09:55:39'),
(14, 3, 3, 5.0, 'Bonita peli', 'Bonita peli para entusiasmados del videojuego', 'Publica', 0, '2026-05-11 10:10:12'),
(15, 3, 12, 10.0, 'Buena película de acción', 'La mejor peli de acción con el gran actor Karl Urban y la espléndida actriz Adeline Rudolph.', 'Publica', 0, '2026-05-11 10:17:49'),
(18, 4, 6, 10.0, 'Muy bonita', 'Muy bonita la peli. Recomendada para las niños y los niños. ¡¡Preciosa!!', 'Publica', 1, '2026-05-11 12:37:14'),
(21, 2, 18, 10.0, 'Muy Bonita', 'Esta película es emocionante e impresionante. La he visto y sus efectos especiales me parecen de diez. Está muy bien. Al final casi tiene problemas con la llegada al planeta Tierra pero logra encapsular a la criatura y llegar a América (el mundo).', 'Publica', 1, '2026-05-15 19:24:51'),
(22, 2, 20, 10.0, 'Película emotiva y reflexiva', 'Me encanta cómo es Adela y José Luis López Vázquez actúa muy bien.', 'Publica', 1, '2026-05-15 20:03:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(40) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `passwordHash` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `state` enum('Activa','Inactiva') DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `bio` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `passwordHash`, `country`, `role`, `state`, `createdAt`, `bio`) VALUES
(1, 'Elena Makarovic', 'elenamakarovic', 'elenamakarovic@gmail.com', '$2y$10$NfJ.xdVfVPicdyMlPBFKtecGVeJdkjJ6E/JS7QKCtKfN2okyTk1ZG', NULL, NULL, NULL, '2026-05-08 16:03:33', NULL),
(2, 'Sandra Cornágo', 'sandra_cornago_1997', 'sandracornagonav@gmail.com', '$2y$10$uP/j3Tyjz0JRpqeovhP.6uvct3ZNKGlTPwISw4ANYnoXgz0HCtt4m', 'Alemania', NULL, NULL, '2026-05-08 16:37:25', 'Hola soy Sandrita, ¿qué tal estáis todos?'),
(3, 'mikel', 'mikelnava', 'mikelnaval2006@gmail.com', '$2y$10$xojHZ7T3QGgdG7NJtyFxEuwdwraEdryOppmethOHENUi86Wdwc90e', NULL, 'NORMAL', NULL, '2026-05-11 09:45:30', NULL),
(4, 'Mikel', 'mikel', 'mikelnavarro2006@gmail.com', '$2y$10$8yATJ1hNEbuFz5z3ivuUu.BjS4/Pgf143mKRfB1nGiVRWl.kaHryu', NULL, 'NORMAL', NULL, '2026-05-11 11:18:43', NULL),
(5, 'Elena ', 'elenaparabolica_', 'elenaparabolica@gmail.com', '$2y$10$ICRbFYgnkx2pS34m1VL2a.M1MCWOq4TT43DSX2ZfrgFoi7Naaege2', NULL, 'NORMAL', NULL, '2026-05-11 19:28:45', NULL),
(6, 'lope_de_vega', 'lope_de_vega', 'lope@gmail.com', '$2y$10$W6T.AKkr9gy.RrpNGsRdleerBLiHG0UABRFnasDcsW0nZUxqQfCWu', NULL, 'NORMAL', NULL, '2026-05-11 19:43:55', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `directors`
--
ALTER TABLE `directors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tmdb_id` (`tmdb_id`);

--
-- Indices de la tabla `lists`
--
ALTER TABLE `lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lists_user` (`user_id`);

--
-- Indices de la tabla `list_movies`
--
ALTER TABLE `list_movies`
  ADD PRIMARY KEY (`list_id`,`movie_id`),
  ADD KEY `fk_list_movies_movieid` (`movie_id`);

--
-- Indices de la tabla `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tmdb_id` (`tmdb_id`),
  ADD KEY `fk_movies_director` (`director_id`);

--
-- Indices de la tabla `movie_providers`
--
ALTER TABLE `movie_providers`
  ADD PRIMARY KEY (`movie_id`,`provider_id`,`type`),
  ADD KEY `fk_movie_providers_provider` (`provider_id`);

--
-- Indices de la tabla `providers`
--
ALTER TABLE `providers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tmdb_id` (`tmdb_id`);

--
-- Indices de la tabla `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reviews_movieid` (`movie_id`),
  ADD KEY `fk_reviews_userid` (`user_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `directors`
--
ALTER TABLE `directors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lists`
--
ALTER TABLE `lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `providers`
--
ALTER TABLE `providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `lists`
--
ALTER TABLE `lists`
  ADD CONSTRAINT `fk_lists_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `list_movies`
--
ALTER TABLE `list_movies`
  ADD CONSTRAINT `fk_list_movies_list` FOREIGN KEY (`list_id`) REFERENCES `lists` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_list_movies_listid` FOREIGN KEY (`list_id`) REFERENCES `lists` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_list_movies_movieid` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `fk_movies_director` FOREIGN KEY (`director_id`) REFERENCES `directors` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `movie_providers`
--
ALTER TABLE `movie_providers`
  ADD CONSTRAINT `fk_movie_providers_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_movie_providers_provider` FOREIGN KEY (`provider_id`) REFERENCES `providers` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_movieid` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_userid` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
