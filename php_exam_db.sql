-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : dim. 19 fév. 2023 à 17:52
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `php_exam_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `item_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `date_publication` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL,
  `article_pic_link` varchar(255) NOT NULL,
  `id_image` varchar(255) NOT NULL,
  `extension_image` varchar(255) NOT NULL,
  `approuve` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`item_id`, `name`, `description`, `price`, `date_publication`, `author_id`, `article_pic_link`, `id_image`, `extension_image`, `approuve`) VALUES
(48, 'chiot', 'petit', 150, '19-2-2023', 34, '63f24c1f7fa33', '63f24c1f7fa31', '.jpg', 1),
(49, 'stylo', 'beau stylo noir', 5, '19-2-2023', 34, '63f24c47d8d5a', '63f24c47d8d58', '.jpg', 1),
(50, 'avion', 'je vends mon avion privé car trop petit', 100000, '19-2-2023', 35, '63f24cc09b320', '63f24cc09b31d', '.jpg', 1),
(51, 'voiture', 'je vends ma voiture car ca fait pauvre', 249000, '19-2-2023', 35, '63f24cf737f0c', '63f24cf737f09', '.jpg', 1),
(53, 'enfant', 'bon état', 400, '19-2-2023', 36, '63f24dffc1850', '63f24dffc184d', '.jpeg', 1);

-- --------------------------------------------------------

--
-- Structure de la table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `id_from_user` int(11) NOT NULL,
  `id_from_item` int(11) NOT NULL,
  `number_in_cart` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `discussions`
--

CREATE TABLE `discussions` (
  `user_rec` varchar(255) NOT NULL,
  `user_env` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `discussions`
--

INSERT INTO `discussions` (`user_rec`, `user_env`, `date`, `message`) VALUES
('2', '1', '30.01.23', 'IMQysG7IlnvQtCxM5O2snA=='),
('2', '1', '30.01.23', 'aO4Mk9/t/8s+arFzhKhDu2cSGTUTvXjykosvJdTk5G46okib/EHmAJH6Kx8tjGpt'),
('1', '2', '30.01.23', 'AVAaqoA9+phIgkHoNK+9p++xJ6Yo5p19K7AXrOgcqhY='),
('2', '8', '04.02.23', 'jaXmREVNPWLiVpVq7sXTiQ=='),
('2', '8', '04.02.23', '7pmpYlhVINXOvV7ciCaxSA=='),
('2', '8', '04.02.23', 'UvM4/pP+eVg93qAoR7R3fA=='),
('2', '8', '04.02.23', 't/tCfbKYQ2l0KsDJtanMrA=='),
('2', '8', '04.02.23', '+JLQ+4+ACH0TQ2/pwMr0Ug=='),
('2', '8', '04.02.23', 'yeG+lt4Bguww+KYUPsi5Ng=='),
('34', '36', '19.02.23', 'bEUJpgts4KU0YMX4frlDeweSrIyyBnqc0XzjhNmqCBd+DPEQFDkChQ11SexWwhOF'),
('34', '36', '19.02.23', 'QMUDGoRo6qbOkRsvJ4Ubvw=='),
('35', '34', '19.02.23', 'HgovBizJycbi571z0VC8uhQe73jYgJx7KwWTHM59+vUFvWukAqxQ3No/c+GpbH1O');

-- --------------------------------------------------------

--
-- Structure de la table `invoice`
--

CREATE TABLE `invoice` (
  `id_from_user` int(11) NOT NULL,
  `id_from_item` int(11) NOT NULL,
  `number_in_cart` int(11) NOT NULL,
  `price` float NOT NULL,
  `id` int(11) NOT NULL,
  `date` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `zipcode` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `invoice`
--

INSERT INTO `invoice` (`id_from_user`, `id_from_item`, `number_in_cart`, `price`, `id`, `date`, `address`, `city`, `zipcode`) VALUES
(36, 48, 1, 150, 52, '19-2-2023', '15 rue du général de gaulle', 'Bordeaux', 33000),
(34, 50, 1, 100000, 54, '19-2-2023', 'élysée', 'Paris', 92000);

-- --------------------------------------------------------

--
-- Structure de la table `stock`
--

CREATE TABLE `stock` (
  `stock_id` int(11) NOT NULL,
  `id_from_item_stock` int(11) NOT NULL,
  `number_in_stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `stock`
--

INSERT INTO `stock` (`stock_id`, `id_from_item_stock`, `number_in_stock`) VALUES
(23, 48, 2),
(24, 49, 10),
(25, 50, 0),
(26, 51, 1),
(28, 53, 1);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `wallet` float NOT NULL,
  `profile_pic` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `mail`, `wallet`, `profile_pic`, `role`) VALUES
(33, 'marty', '$2y$10$A2Er7a/wjVzrF6vgE/OB6u/3vKqwY01M5PEkJ5trxBOTI7lhClDtK', 'marty@gmail.com', 0, 'aaz.jpeg', '1'),
(34, 'greta', '$2y$10$BurOHClMXqLCTaNcKDsld.F15ylF2bFcAm/TkdmZKOGTW5E1Fb95O', 'greta.thunberg@ecologie.com', 900000, 'idee_pose_femme_photo_65-1024x683.webp', '0'),
(35, 'bernard', '$2y$10$Ah5GjVoTnGMAr0IEA/7bBO3A5c5LuzABtOVGkleRiQ66tyqLhrbNe', 'bernard.madoff@gmail.com', 0, 'dd.jpeg', '0'),
(36, 'marc', '$2y$10$BZT/cAB2my9mldj8vzy19emiZkChM1Eh3dZ2b0eloqLOJooxM8CM.', 'marc.dutroux@outlook.fr', 19700, 'B9722400591Z.1_20200129154359_000+G5QFDI8LI.1-0.jpg', '0');

-- --------------------------------------------------------

--
-- Structure de la table `users_blocked`
--

CREATE TABLE `users_blocked` (
  `user_blocked` varchar(255) NOT NULL,
  `user_asking_block` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`item_id`);

--
-- Index pour la table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`);

--
-- Index pour la table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`stock_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT pour la table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT pour la table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT pour la table `stock`
--
ALTER TABLE `stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
