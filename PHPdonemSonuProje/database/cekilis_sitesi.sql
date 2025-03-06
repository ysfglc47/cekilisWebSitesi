-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 31 Ara 2024, 16:16:43
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `cekilis_sitesi`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `draws`
--

CREATE TABLE `draws` (
  `id` int(11) NOT NULL,
  `draw_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `creator_username` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `draws`
--

INSERT INTO `draws` (`id`, `draw_name`, `description`, `creator_username`, `created_at`) VALUES
(1, 'deneme1', 'oduler', 'yusuf47', '2024-12-31 14:55:50');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `participants`
--

CREATE TABLE `participants` (
  `id` int(11) NOT NULL,
  `draw_id` int(11) NOT NULL,
  `participant_name` varchar(50) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `participants`
--

INSERT INTO `participants` (`id`, `draw_id`, `participant_name`, `joined_at`) VALUES
(1, 1, 'yusuf47', '2024-12-31 15:00:27'),
(2, 1, 'kullanici1', '2024-12-31 15:01:09'),
(3, 1, 'kullanici2', '2024-12-31 15:01:31'),
(4, 1, 'kullanici3', '2024-12-31 15:01:54');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(3, 'kullanici2', 'taylanemine87@gmail.com', '$2y$10$NozL7xfG9XT5VgdAotg3R.nf4cTBFaBpNb5mOkny7XkWgoWuQIVT6'),
(4, 'kullanici3', 'svxdfsdffas@gmail.com', '$2y$10$w6nA0UxhrS5PpHvt27FuheZARbITt/GsiVxV2GA2wij07ClA7hfvK'),
(5, 'kullanici1', 'sajhkgsad627462@gmail.com', '$2y$10$onYOnC6uG8GVkw0ZtMzl3ummEk7epeKgXHYcqjL2oFq3xI4TzQU8i'),
(6, 'yusuf47', '05445495787.yg@gmail.com', '$2y$10$Q6qbjlk0NrTxxbJ5HKI3w.GUYlFfvxPF/Ax.2CGK2mHdxhAmUGbyS');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `winners`
--

CREATE TABLE `winners` (
  `id` int(11) NOT NULL,
  `draw_id` int(11) NOT NULL,
  `winner_name` varchar(50) NOT NULL,
  `won_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `winners`
--

INSERT INTO `winners` (`id`, `draw_id`, `winner_name`, `won_at`) VALUES
(1, 1, 'kullanici3', '2024-12-31 15:12:04');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `draws`
--
ALTER TABLE `draws`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creator_username` (`creator_username`);

--
-- Tablo için indeksler `participants`
--
ALTER TABLE `participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `draw_id` (`draw_id`,`participant_name`),
  ADD KEY `participant_name` (`participant_name`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Tablo için indeksler `winners`
--
ALTER TABLE `winners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `draw_id` (`draw_id`),
  ADD KEY `winner_name` (`winner_name`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `draws`
--
ALTER TABLE `draws`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `participants`
--
ALTER TABLE `participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `winners`
--
ALTER TABLE `winners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `draws`
--
ALTER TABLE `draws`
  ADD CONSTRAINT `draws_ibfk_1` FOREIGN KEY (`creator_username`) REFERENCES `users` (`username`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `participants`
--
ALTER TABLE `participants`
  ADD CONSTRAINT `participants_ibfk_1` FOREIGN KEY (`draw_id`) REFERENCES `draws` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `participants_ibfk_2` FOREIGN KEY (`participant_name`) REFERENCES `users` (`username`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `winners`
--
ALTER TABLE `winners`
  ADD CONSTRAINT `winners_ibfk_1` FOREIGN KEY (`draw_id`) REFERENCES `draws` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `winners_ibfk_2` FOREIGN KEY (`winner_name`) REFERENCES `users` (`username`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
