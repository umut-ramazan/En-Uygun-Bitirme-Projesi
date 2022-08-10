-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: database
-- Üretim Zamanı: 10 Ağu 2022, 20:24:49
-- Sunucu sürümü: 8.0.29
-- PHP Sürümü: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `app`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `category`
--

CREATE TABLE `category` (
  `id` int NOT NULL,
  `category_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int NOT NULL,
  `discount` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `category`
--

INSERT INTO `category` (`id`, `category_name`, `parent_id`, `discount`) VALUES
(1, 'Erkek', 0, NULL),
(2, 'Giyim', 1, NULL),
(3, 'Ayakkabı', 1, 1),
(4, 'Spor', 2, NULL),
(5, 'Kadın', 0, NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `category_product`
--

CREATE TABLE `category_product` (
  `category_id` int NOT NULL,
  `product_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `category_product`
--

INSERT INTO `category_product` (`category_id`, `product_id`) VALUES
(1, '1ed18d29-b390-6a60-8d14-81360c4abd85'),
(1, '1ed18d31-ecbc-650c-b3dc-d7168f61fad5'),
(2, '1ed18d31-ecbc-650c-b3dc-d7168f61fad5'),
(2, '1ed18d33-8c09-6e1a-9876-e71f1ec30e73'),
(3, '1ed18d29-b390-6a60-8d14-81360c4abd85'),
(3, '1ed18d2b-9300-646a-bdde-9fc410c19c93'),
(3, '1ed18d2f-8fa8-6476-83fd-7534f6d7483c');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Tablo döküm verisi `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20220810171444', '2022-08-10 17:15:03', 2102);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `shopping_cart_id_id` int DEFAULT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_option` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `orders`
--

INSERT INTO `orders` (`id`, `shopping_cart_id_id`, `user_id`, `address`, `phone_number`, `order_option`, `status`, `created_at`) VALUES
(1, 1, '1ed18d04-2428-6ef6-a017-0791819029a0', 'İstanbul bahçeli evler 1234sk. daire 4', '5367820254', 'paymentByDoor', 'process', '2022-08-10 17:49:02'),
(2, 2, '1ed18d04-2428-6ef6-a017-0791819029a0', 'İstanbul avcılar kampüs', '5367820251', 'paymentByDoor', 'process', '2022-08-10 17:50:23'),
(4, 4, '1ed18d04-2428-6ef6-a017-0791819029a0', 'istabul bağcılar kirazlı mahellesi', '5367820250', 'paymentByDoor', 'confirm', '2022-08-10 17:54:16');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `product`
--

CREATE TABLE `product` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_price` double NOT NULL DEFAULT '0',
  `product_piece` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `img_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `product`
--

INSERT INTO `product` (`id`, `product_name`, `product_content`, `product_price`, `product_piece`, `created_at`, `updated_at`, `img_path`) VALUES
('1ed18d29-b390-6a60-8d14-81360c4abd85', 'Siyah Erkek Ayakabı', '<p>Siyah 44 numara&nbsp;</p>', 100, '496', '2022-08-10 17:33:54', '2022-08-10 17:33:54', '62f3ec026ccc8-aya 2.jpg'),
('1ed18d2b-9300-646a-bdde-9fc410c19c93', 'Spor Erkek Ayakkabı', '<p>Siyah Spor Ayakkabı <em>44 Numara</em></p>', 150, '197', '2022-08-10 17:34:44', '2022-08-10 17:34:44', '62f3ec34aed6a-aya 3.jpg'),
('1ed18d2f-8fa8-6476-83fd-7534f6d7483c', 'Beyaz Spor ayakkabı', '<p><strong>Beyaz Spor Ayakkabı</strong></p>', 170, '798', '2022-08-10 17:36:31', '2022-08-10 17:36:31', '62f3ec9fb4e12-erkek-bagcikli-spor-ayakkabi-tas-beyaz--4b7a-.jpg'),
('1ed18d31-ecbc-650c-b3dc-d7168f61fad5', 'Erkek Siyah Tişört', '<div class=\"CCgQ5 vCa9Yd QfkTvb MUxGbd v0nnCb\" role=\"heading\" aria-level=\"3\">Erkek Siyah Tiş&ouml;rt</div>', 200, '47', '2022-08-10 17:37:35', '2022-08-10 17:37:35', '62f3ecdf2cd8e-gi 2.jpg'),
('1ed18d33-8c09-6e1a-9876-e71f1ec30e73', 'Erkek Mavi Uzun Kollu Tişört', '<p>Erkek Mavi Uzun Kollu Tiş&ouml;rt</p>', 120, '185', '2022-08-10 17:38:18', '2022-08-10 17:38:18', '62f3ed0ab2e00-giyim 1.jpg');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `shopping_cart`
--

CREATE TABLE `shopping_cart` (
  `id` int NOT NULL,
  `product_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `summers_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `shopping_cart`
--

INSERT INTO `shopping_cart` (`id`, `product_ids`, `summers_ids`) VALUES
(1, 'a:3:{i:0;a:1:{i:0;a:7:{s:11:\"productName\";s:20:\"Spor Erkek Ayakkabı\";s:2:\"id\";s:36:\"1ed18d2b-9300-646a-bdde-9fc410c19c93\";s:12:\"productPrice\";d:150;s:12:\"productPiece\";s:3:\"200\";s:7:\"imgPath\";s:23:\"62f3ec34aed6a-aya 3.jpg\";s:5:\"count\";i:1;s:5:\"total\";d:150;}}i:1;a:1:{i:0;a:7:{s:11:\"productName\";s:20:\"Siyah Erkek Ayakabı\";s:2:\"id\";s:36:\"1ed18d29-b390-6a60-8d14-81360c4abd85\";s:12:\"productPrice\";d:100;s:12:\"productPiece\";s:3:\"500\";s:7:\"imgPath\";s:23:\"62f3ec026ccc8-aya 2.jpg\";s:5:\"count\";i:1;s:5:\"total\";d:100;}}i:2;a:1:{i:0;a:7:{s:11:\"productName\";s:20:\"Erkek Siyah Tişört\";s:2:\"id\";s:36:\"1ed18d31-ecbc-650c-b3dc-d7168f61fad5\";s:12:\"productPrice\";d:200;s:12:\"productPiece\";s:2:\"50\";s:7:\"imgPath\";s:22:\"62f3ecdf2cd8e-gi 2.jpg\";s:5:\"count\";i:1;s:5:\"total\";d:200;}}}', 'a:2:{s:10:\"totalCount\";i:3;s:10:\"totalPrice\";d:450;}'),
(2, 'a:2:{i:0;a:1:{i:0;a:7:{s:11:\"productName\";s:20:\"Siyah Erkek Ayakabı\";s:2:\"id\";s:36:\"1ed18d29-b390-6a60-8d14-81360c4abd85\";s:12:\"productPrice\";d:100;s:12:\"productPiece\";s:3:\"499\";s:7:\"imgPath\";s:23:\"62f3ec026ccc8-aya 2.jpg\";s:5:\"count\";i:1;s:5:\"total\";d:100;}}i:1;a:1:{i:0;a:7:{s:11:\"productName\";s:20:\"Erkek Siyah Tişört\";s:2:\"id\";s:36:\"1ed18d31-ecbc-650c-b3dc-d7168f61fad5\";s:12:\"productPrice\";d:200;s:12:\"productPiece\";s:2:\"49\";s:7:\"imgPath\";s:22:\"62f3ecdf2cd8e-gi 2.jpg\";s:5:\"count\";i:2;s:5:\"total\";d:400;}}}', 'a:2:{s:10:\"totalCount\";i:3;s:10:\"totalPrice\";d:450;}'),
(4, 'a:3:{i:0;a:1:{i:0;a:7:{s:11:\"productName\";s:20:\"Siyah Erkek Ayakabı\";s:2:\"id\";s:36:\"1ed18d29-b390-6a60-8d14-81360c4abd85\";s:12:\"productPrice\";d:100;s:12:\"productPiece\";s:3:\"497\";s:7:\"imgPath\";s:23:\"62f3ec026ccc8-aya 2.jpg\";s:5:\"count\";i:1;s:5:\"total\";d:100;}}i:1;a:1:{i:0;a:7:{s:11:\"productName\";s:20:\"Spor Erkek Ayakkabı\";s:2:\"id\";s:36:\"1ed18d2b-9300-646a-bdde-9fc410c19c93\";s:12:\"productPrice\";d:150;s:12:\"productPiece\";s:3:\"198\";s:7:\"imgPath\";s:23:\"62f3ec34aed6a-aya 3.jpg\";s:5:\"count\";i:1;s:5:\"total\";d:150;}}i:2;a:1:{i:0;a:7:{s:11:\"productName\";s:20:\"Beyaz Spor ayakkabı\";s:2:\"id\";s:36:\"1ed18d2f-8fa8-6476-83fd-7534f6d7483c\";s:12:\"productPrice\";d:170;s:12:\"productPiece\";s:3:\"799\";s:7:\"imgPath\";s:63:\"62f3ec9fb4e12-erkek-bagcikli-spor-ayakkabi-tas-beyaz--4b7a-.jpg\";s:5:\"count\";i:1;s:5:\"total\";d:170;}}}', 'a:2:{s:10:\"totalCount\";i:3;s:10:\"totalPrice\";d:170;}');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user`
--

CREATE TABLE `user` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`) VALUES
('1ed18d04-2428-6ef6-a017-0791819029a0', 'musteri@gmail.com', '[\"ROLE_USER\"]', '$2y$13$odWRSbIl6XJFFjPdK36NGe7F8HX6I/uJ.ubMheKFLGTqYl80PmakS'),
('1ed18d06-46db-6fa0-90d4-d3d8a9169e04', 'admin@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$Rij6xSXk/oxysN34bQ8Ou.CfGzr79WSSLpl1SfyojfPFBnQpd7q8y');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `category_product`
--
ALTER TABLE `category_product`
  ADD PRIMARY KEY (`category_id`,`product_id`),
  ADD KEY `IDX_149244D312469DE2` (`category_id`),
  ADD KEY `IDX_149244D34584665A` (`product_id`);

--
-- Tablo için indeksler `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Tablo için indeksler `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Tablo için indeksler `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_E52FFDEE3F611409` (`shopping_cart_id_id`);

--
-- Tablo için indeksler `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `category`
--
ALTER TABLE `category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `shopping_cart`
--
ALTER TABLE `shopping_cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `category_product`
--
ALTER TABLE `category_product`
  ADD CONSTRAINT `FK_149244D312469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_149244D34584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK_E52FFDEE3F611409` FOREIGN KEY (`shopping_cart_id_id`) REFERENCES `shopping_cart` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
